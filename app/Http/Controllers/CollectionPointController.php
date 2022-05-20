<?php

namespace App\Http\Controllers;

use App\collectionPoint;
use App\collectionRequest;
use App\collectorRequest;
use App\Inventory;
use App\Notification;
use App\User;
use Berkayk\OneSignal\OneSignalFacade;
use Illuminate\Http\Request;

class CollectionPointController extends Controller
{
    public function imports(Request $request)
    {
        $results = collectorRequest::where('collectionPoint_id', '=', \Auth::user()->id)->orderBy('created_at', 'desc')->paginate(15);
        return view('imports.index', compact('results'));
    }

    public function importsInfo($id)
    {
        $result = collectorRequest::with('requestsItems')->where("id", $id)->get()->first();
        if ($result->collectionPoint_id != \Auth::user()->id) abort(403);
        return view('imports.show', compact('result'));
    }

    public function importsInfoUpdate($id, Request $request)
    {
        $result = collectorRequest::where('id', '=', $id)->get()->first();
        if ($request->status == 1) {
            if ($result->status == 'PENDING') {
                $result->status = 'ACCEPTED';
                $user_id = $result->collector_id;
                $fcm = $result->collector->fcm_token;
                $message = $result->collectionPoint->name . " Accepted Your Request";

                foreach ($result->requestsItems as $item) {
                    $invetories = Inventory::where([['user_id', '=', $result->collector->id], ['item_id', '=', $item->id]])->get()->first();
                    // OLD
                    if ($invetories) {
                        $invetories->count -= $item->pivot->count;
                        $invetories->save();
                    }
                }
            } else if ($result->status == 'ACCEPTED') {
                $result->status = 'CONFIRMED';

                $user_id = $result->collector_id;
                $fcm = $result->collector->fcm_token;
                $message = $result->collectionPoint->name . " Confirmed Your Request";
            }


            $notifcation = new Notification();
            $notifcation->user_id = $user_id;
            $notifcation->message = $message;
            $notifcation->type = 'request1';
            $notifcation->request_id = $result->id;
            $notifcation->save();
            if ($fcm) {
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

        $result->save();
        return redirect(route('collectionImport', $result->id));
    }

    public function exportsInfoUpdate($id, Request $request)
    {
        $result = collectionRequest::where('id', '=', $id)->get()->first();
        if ($result->collectionPoint_id != \Auth::user()->id) abort(403);

        if ($request->status == 1) {
            if ($result->status == 'CONFIRMED') $result->status = 'DONE';
            foreach ($result->requestsItems as $item) {
                $user = $result->collectionPoint;
                $user->on_hold += ($item->pivot->count * 10) * 0.3;
                $user->save();

                $user2 = $result->factory;
                $user2->on_hold += ($item->pivot->count * 10) * 0.4;
                $user2->save();
                $invetories = Inventory::where([['user_id', '=', $result->factory->id], ['item_id', '=', $item->id]])->get()->first();
                // OLD
                if ($invetories) {
                    $invetories->count += $item->pivot->count;
                    $invetories->save();
                } else {
                    $invetories = new Inventory();
                    $invetories->item_id = $item->pivot->item_id;
                    $invetories->count = $item->pivot->count;
                    $invetories->user_id = $result->factory->id;
                    $invetories->save();
                }

                $invetories = Inventory::where([['user_id', '=', $result->collectionPoint->id], ['item_id', '=', $item->id]])->get()->first();
                // OLD
                if ($invetories) {
                    $invetories->count -= $item->pivot->count;
                    $invetories->save();
                }

            }
            $notifcation = new Notification();
            $notifcation->user_id = $result->factory->id;
            $notifcation->message = "Request Done Successfully";
            $notifcation->type = 'request1';
            $notifcation->request_id = $result->id;
            $notifcation->save();

            $notifcation = new Notification();
            $notifcation->user_id = $result->collectionPoint->id;
            $notifcation->message = "Request Done Successfully";
            $notifcation->type = 'request1';
            $notifcation->request_id = $result->id;
            $notifcation->save();


            if ($result->factory->fcm_token) {
                OneSignalFacade::sendNotificationToUser(
                    "Request Done Successfully",
                    $result->factory->fcm_token,
                    $url = null,
                    $data = null,
                    $buttons = null,
                    $schedule = null
                );

            }

            if ($result->collectionPoint->fcm_token) {
                OneSignalFacade::sendNotificationToUser(
                    "Request Done Successfully",
                    $result->collectionPoint->fcm_token,
                    $url = null,
                    $data = null,
                    $buttons = null,
                    $schedule = null
                );
            }

        }
        $result->save();
        return redirect(route('collectionImport', $result->id));
    }

    public function exportCreate()
    {
        $max = [];
        $items = Inventory::where('user_id', \Auth::user()->id)->get();
        foreach ($items as $item) {
            $max[$item->item_id] = $item->count;
        }

        return view('exports.create', compact('max'));
    }

    public function exports()
    {
        $results = collectionRequest::where('collectionPoint_id', '=', \Auth::user()->id)->orderBy('created_at', 'desc')->paginate(15);
        return view('exports.index', compact('results'));
    }

    public function exportsInfo($id)
    {
        $result = collectionRequest::with('requestsItems')->where("id", $id)->get()->first();
        if ($result->collectionPoint_id != \Auth::user()->id) abort(403);
        return view('exports.show', compact('result'));
    }

    public function inventory()
    {
        $max = [];
        $items = Inventory::where('user_id', \Auth::user()->id)->get();
        foreach ($items as $item) {
            $max[$item->item_id] = $item->count;
        }
        return view('inventory.index', compact('max'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $out = User::selectRaw("*, (3959 * acos( 
                cos( radians(  " . \request('lat') . "  ) ) *
                cos( radians( lat ) ) * 
                cos( radians( lng ) - radians(" . $request->lng . ") ) + 
                sin( radians(  " . $request->lat . "  ) ) *
                sin( radians( lat ) ) 
            )) AS distance")->where('access_level', 2)->havingRaw("distance < ?", [50]);
        return $out->get();
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\collectionPoint $collectionPoint
     * @return \Illuminate\Http\Response
     */
    public function show(collectionPoint $collectionPoint)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\collectionPoint $collectionPoint
     * @return \Illuminate\Http\Response
     */
    public function edit(collectionPoint $collectionPoint)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\collectionPoint $collectionPoint
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, collectionPoint $collectionPoint)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\collectionPoint $collectionPoint
     * @return \Illuminate\Http\Response
     */
    public function destroy(collectionPoint $collectionPoint)
    {
        //
    }
}
