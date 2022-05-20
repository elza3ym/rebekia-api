<?php

namespace App\Http\Controllers;

use App\collectionPoint;
use App\collectionRequest;
use App\collectorRequest;
use Berkayk\OneSignal\OneSignalClient;
use Berkayk\OneSignal\OneSignalFacade;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        $user = \Auth::user()->access_level;

        if ($user == 2) return redirect('/collection');
        if ($user == 3) return redirect('/factory');
        if ($user == 4) return redirect('/admin');
    }
    public function indexCollection()
    {
        $latest_requests = collectorRequest::with('collector')->where('collectionPoint_id','=' ,\Auth::user()->id)->orderBy('created_at', 'desc')->limit(4)->get();
        $stats = collectionPoint::getRequestsStats();
        return view('home', compact('stats', 'latest_requests'));
    }
    public function indexFactory()
    {
        $latest_requests = collectionRequest::with('collectionPoint')->where('factory_id','=' ,\Auth::user()->id)->orderBy('created_at', 'desc')->limit(4)->get();
        return view('homeFactory', compact( 'latest_requests'));
    }

    public function indexAdmin()
    {

        return view('homeAdmin');
    }
}
