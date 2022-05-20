<?php

namespace App\Http\Controllers;

use App\citizenRequest;
use App\Inventory;
use App\Item;
use App\Notification;
use Berkayk\OneSignal\OneSignalFacade;
use Illuminate\Http\Request;

class CitizenRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $user = \Auth::user();
        if ($request->all == true) {
            if ($user->access_level == 0) {
                return citizenRequest::where([['citizen_id', $user->id]])->get();
            } else if ($user->access_level == 1) {
                return citizenRequest::where([['collector_id', $user->id]])->get();
            }
        }
        if ($user->access_level == 0) {
            return citizenRequest::where([['citizen_id', $user->id], ['status' , '<>', 'DONE']])->get();
        } else if ($user->access_level == 1) {
            return citizenRequest::where([['collector_id', $user->id], ['status' , '<>','DONE']])->get();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        dd();
        //
        /**
         * Get a validator for an incoming registration request.
         *
         * @param  array  $request
         * @return \Illuminate\Contracts\Validation\Validator
         */
        $valid = validator($request->all(), [
            'collector_id' => 'required|exists:users,id',
            'lat' => 'required',
            'lng' => 'required',
        ]);

        if ($valid->fails()) {
            return response()->json(["errors" => $valid->errors()->all()], 400);
        }

        $citizenReq = new citizenRequest();
        $citizenReq->collector_id = $request->collector_id;
        $citizenReq->citizen_id = \Auth::user()->id;
        $citizenReq->lat = $request->lat;
        $citizenReq->lng = $request->lng;
        $citizenReq->status = 'PENDING';
        $citizenReq->save();

        foreach ($request->request_items as $key => $item) {
            if (!is_null($item)) {
                $citizenReq->requestsItems()->attach($key, ['count' => $item[1]]);
            }
        }

        $citizenReq->requestsItems;
        $notifcation = new Notification();
        $notifcation->user_id = $citizenReq->collector_id;
        $notifcation->message = "You Have New Request From ".$citizenReq->citizen->name;
        $notifcation->type = 'request0';
        $notifcation->request_id = $citizenReq->id;
        $notifcation->save();
        if ($citizenReq->collector->fcm_token){
            OneSignalFacade::sendNotificationToUser(
                $notifcation->message,
                $citizenReq->collector->fcm_token,
                $url = null,
                $data = null,
                $buttons = null,
                $schedule = null
            );
        }
        return $citizenReq;
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\citizenRequest  $citizenRequest
     * @return \Illuminate\Http\Response
     */
    public function show( $citizenRequest)
    {
        //
        return citizenRequest::with('collector', 'requestsItems', 'citizen')->where('id', $citizenRequest)->get()->first();
        return $citizenRequest;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\citizenRequest  $citizenRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(citizenRequest $citizenRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\citizenRequest  $citizenRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $citizenRequest_id)
    {
        //
        $valid = validator($request->all(), [
            'status' => 'required|in:PENDING,ACCEPTED,CONFIRMED,DONE',
        ]);

        if ($valid->fails()) {
            return response()->json(["errors" => $valid->errors()->all()], 400);
        }
        $citizenRequest = citizenRequest::findOrFail($citizenRequest_id);

        $user = \Auth::user()->id;
        if ($citizenRequest->citizen_id == $user || $citizenRequest->collector_id == $user ) {
            if (\request('status') && $citizenRequest->status == 'DONE') return json_encode(["error" => "UnAuthenticated"]);
            $citizenRequest->status = \request('status');
            $citizenRequest->updated_at = now();
            $citizenRequest->save();
            if ($citizenRequest->status  == 'ACCEPTED') {
                $user_id = $citizenRequest->citizen_id;
                $fcm = $citizenRequest->citizen->fcm_token;
                $message = $citizenRequest->collector->name. " Accepted Your Request";
            } else if ($citizenRequest->status  == 'CONFIRMED') {
                $user_id = $citizenRequest->citizen_id;
                $fcm = $citizenRequest->citizen->fcm_token;
                $message = $citizenRequest->collector->name. " Confirmed Your Request";
            }
            if ($citizenRequest->status == 'DONE') {
                foreach ($citizenRequest->requestsItems as $item) {
                    $user = $citizenRequest->citizen;
                    $user->on_hold +=  ($item->pivot->count*10)*0.1;
                    $user->save();
                    $invetories = Inventory::where([['user_id', '=',$citizenRequest->collector->id], ['item_id', '=', $item->id]])->get()->first();
                    // OLD
                    if ($invetories) {
                        $invetories->count += $item->pivot->count;
                        $invetories->save();
                    } else {
                        $invetories = new Inventory();
                        $invetories->item_id = $item->pivot->item_id;
                        $invetories->count = $item->pivot->count;
                        $invetories->user_id = $citizenRequest->collector->id;
                        $invetories->save();
                    }

                }

                $notifcation = new Notification();
                $notifcation->user_id = $citizenRequest->citizen->id;
                $notifcation->message =  "Request Done Successfully";
                $notifcation->type = 'request0';
                $notifcation->request_id = $citizenRequest->id;
                $notifcation->save();

                $notifcation = new Notification();
                $notifcation->user_id = $citizenRequest->collector->id;
                $notifcation->message = "Request Done Successfully";
                $notifcation->type = 'request0';
                $notifcation->request_id = $citizenRequest->id;
                $notifcation->save();


                if ($citizenRequest->collector->fcm_token){
                    OneSignalFacade::sendNotificationToUser(
                        "Request Done Successfully",
                        $citizenRequest->collector->fcm_token,
                        $url = null,
                        $data = null,
                        $buttons = null,
                        $schedule = null
                    );

                }

                if ($citizenRequest->citizen->fcm_token){
                    OneSignalFacade::sendNotificationToUser(
                        "Request Done Successfully",
                        $citizenRequest->citizen->fcm_token,
                        $url = null,
                        $data = null,
                        $buttons = null,
                        $schedule = null
                    );
                }
            } else {
                $notifcation = new Notification();
                $notifcation->user_id = $user_id;
                $notifcation->message = $message;
                $notifcation->type = 'request0';
                $notifcation->request_id = $citizenRequest->id;
                $notifcation->save();
                if ($fcm){
                    OneSignalFacade::sendNotificationToUser(
                        $message,
                        $fcm,
                        $url = null,
                        $data = null,
                        $buttons = null,
                        $schedule = null
                    );
                }
            }


            return $citizenRequest;
        }


        return json_encode(["error" => "UnAuthenticated"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\citizenRequest  $citizenRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(citizenRequest $citizenRequest)
    {
        //
    }
}
