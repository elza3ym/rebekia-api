<?php

namespace App\Http\Controllers;

use App\citizenRequest;
use App\Collector;
use App\User;
use Illuminate\Http\Request;

class CollectorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Security

        $out =  User::selectRaw("*,users.id as id, (3959 * acos( 
                cos( radians(  ".\request('lat')."  ) ) *
                cos( radians( lat ) ) * 
                cos( radians( lng ) - radians(".$request->lng.") ) + 
                sin( radians(  ".$request->lat."  ) ) *
                sin( radians( lat ) ) 
            )) AS distance")->where('access_level', 1)->havingRaw("distance < ?", [50]);
        return $out->get();
    }

    public function inventory() {
        return citizenRequest::with('requestsItemsAvailable')->where([['collector_id' , \Auth::user()->id], ['status', 'DONE']])->get();
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Collector  $collector
     * @return \Illuminate\Http\Response
     */
    public function show(Collector $collector)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Collector  $collector
     * @return \Illuminate\Http\Response
     */
    public function edit(Collector $collector)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Collector  $collector
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Collector $collector)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Collector  $collector
     * @return \Illuminate\Http\Response
     */
    public function destroy(Collector $collector)
    {
        //
    }
}
