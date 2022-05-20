<?php

namespace App\Http\Controllers;

use App\collectionRequest;
use App\collectorRequest;
use App\Factory;
use App\Inventory;
use Illuminate\Http\Request;

class FactoryController extends Controller
{

    public function imports(){
        $results = collectionRequest::where('factory_id', '=', \Auth::user()->id)->orderBy('created_at', 'desc')->paginate(15);
        return view('factoryImports.index', compact('results'));
    }
    public function importsInfo($id) {
        $result = collectionRequest::with('requestsItems')->where("id", $id)->get()->first();
        if ($result->factory_id != \Auth::user()->id) abort(403);
        return view('factoryImports.show', compact('result'));
    }


    public function importsInfoUpdate($id, Request $request){
        $result = collectionRequest::where('id', '=', $id)->get()->first();
        if ($request->status == 1) {
            if ($result->status == 'PENDING') $result->status = 'ACCEPTED';
            else if ($result->status == 'ACCEPTED') $result->status = 'CONFIRMED';
            else if ($result->status == 'CONFIRMED') $result->status = 'DONE';
        }
        $result->save();
        return redirect(route('factoryImport', $result->id));
    }
    public function inventory() {
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Factory  $factory
     * @return \Illuminate\Http\Response
     */
    public function show(Factory $factory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Factory  $factory
     * @return \Illuminate\Http\Response
     */
    public function edit(Factory $factory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Factory  $factory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Factory $factory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Factory  $factory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Factory $factory)
    {
        //
    }
}
