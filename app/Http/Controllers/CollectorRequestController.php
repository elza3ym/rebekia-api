<?php

namespace App\Http\Controllers;

use App\collectorRequest;
use App\Inventory;
use App\Notification;
use Berkayk\OneSignal\OneSignalFacade;
use Illuminate\Http\Request;

class CollectorRequestController extends Controller
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
            return collectorRequest::with('collector', 'collectionPoint')->where([['collector_id', $user->id]])->get();
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
        //
        $valid = validator($request->all(), [
            'collectionPoint_id' => 'required|exists:users,id',
            'lat' => 'required',
            'lng' => 'required',
        ]);
        if ($valid->fails()) {
            return response()->json(["errors" => $valid->errors()->all()], 400);
        }
        $collectorReq = new collectorRequest();
        $collectorReq->collector_id = \Auth::user()->id;
        $collectorReq->collectionPoint_id = $request->collectionPoint_id;
        $collectorReq->lat = $request->lat;
        $collectorReq->lng = $request->lng;
        $collectorReq->status = 'PENDING';
        $collectorReq->save();


        foreach ($request->request_items as $key => $item) {
            if (!is_null($item)) {
                $collectorReq->requestsItems()->attach($key, ['count' => $item[1]]);
            }
        }

        $collectorReq->requestsItems;


        return $collectorReq;

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\collectorRequest  $collectorRequest
     * @return \Illuminate\Http\Response
     */
    public function show($collectorRequest)
    {
        //
        $out = collectorRequest::with('collector', 'requestsItems', 'collectionPoint')->where([['id', $collectorRequest]])->get()->first();
        $out->collection = $out->collectionPoint;
        return $out;

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\collectorRequest  $collectorRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(collectorRequest $collectorRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\collectorRequest  $collectorRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $collectorRequest)
    {
        //
        //

        $result = collectorRequest::findOrFail($collectorRequest);
        $result->status = "DONE";
        $result->save();

        foreach ($result->requestsItems as $item) {
            $user = $result->collector;
            $user->on_hold +=  ($item->pivot->count*10)*0.2;
            $user->save();
            $invetories = Inventory::where([['user_id', '=',$result->collectionPoint->id], ['item_id', '=', $item->id]])->get()->first();
            // OLD
            if ($invetories) {
                $invetories->count += $item->pivot->count;
                $invetories->save();
            } else {
                $invetories = new Inventory();
                $invetories->item_id = $item->pivot->item_id;
                $invetories->count = $item->pivot->count;
                $invetories->user_id = $result->collectionPoint->id;
                $invetories->save();
            }

        }

        $notifcation = new Notification();
        $notifcation->user_id = $result->collector->id;
        $notifcation->message =  "Request Done Successfully";
        $notifcation->type = 'request1';
        $notifcation->request_id = $result->id;
        $notifcation->save();

        $notifcation = new Notification();
        $notifcation->user_id = $result->collectionPoint->id;
        $notifcation->message = "Request Done Successfully";
        $notifcation->type = 'request1';
        $notifcation->request_id = $result->id;
        $notifcation->save();


        if ($result->collector->fcm_token){
            OneSignalFacade::sendNotificationToUser(
                "Request Done Successfully",
                $result->collector->fcm_token,
                $url = null,
                $data = null,
                $buttons = null,
                $schedule = null
            );

        }

        if ($result->collectionPoint->fcm_token){
            OneSignalFacade::sendNotificationToUser(
                "Request Done Successfully",
                $result->collectionPoint->fcm_token,
                $url = null,
                $data = null,
                $buttons = null,
                $schedule = null
            );
        }

        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\collectorRequest  $collectorRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(collectorRequest $collectorRequest)
    {
        //
    }
}
