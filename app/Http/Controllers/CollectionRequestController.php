<?php

namespace App\Http\Controllers;

use App\collectionRequest;
use App\User;
use Illuminate\Http\Request;

class CollectionRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function Sessionstore(Request $request) {
        $request->session()->put('requestItems', $request->amount);
        $request->session()->put('lat', $request->lat);
        $request->session()->put('lng', $request->lng);
        return redirect(route('indexFactories'));
    }
    public function indexFactories(Request $request) {
        $data = $request->session()->all();
        $results =  User::selectRaw("*, (3959 * acos( 
                cos( radians(  ".$data["lat"]."  ) ) *
                cos( radians( lat ) ) * 
                cos( radians( lng ) - radians(".$data["lng"].") ) + 
                sin( radians(  ".$data["lat"]."  ) ) *
                sin( radians( lat ) ) 
            )) AS distance")->where('access_level', 3)->havingRaw("distance < ?", [50])->get();

        return view('exports.factories', compact('results'));
    }
    public function store(Request $request)
    {
        //
        $valid = validator($request->all(), [
            'factory_id' => 'required|exists:users,id',
        ]);

        if ($valid->fails()) {
            return response()->json(["errors" => $valid->errors()->all()], 400);
        }

        $data = $request->session()->all();

        $collecttionReq = new collectionRequest();
        $collecttionReq->collectionPoint_id = \Auth::user()->id;
        $collecttionReq->factory_id = $request->factory_id;
        $collecttionReq->lat = $data["lat"];
        $collecttionReq->lng = $data["lng"];
        $collecttionReq->status = 'PENDING';
        $collecttionReq->save();
        foreach ($data["requestItems"] as $key => $item) {
            if ($item > 0)
                $collecttionReq->requestsItems()->attach($key, ['count' => $item]);
        }
        return redirect(route('exports'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\collectionRequest  $collectionRequest
     * @return \Illuminate\Http\Response
     */
    public function show(collectionRequest $collectionRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\collectionRequest  $collectionRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(collectionRequest $collectionRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\collectionRequest  $collectionRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, collectionRequest $collectionRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\collectionRequest  $collectionRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(collectionRequest $collectionRequest)
    {
        //
    }
}
