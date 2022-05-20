<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Notification;
use App\User;
use Berkayk\OneSignal\OneSignalFacade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Laravel\Passport\Client;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:api")->only(['index', 'update']);
    }
    function location(Request $request) {
        $user = \Auth::user();
        $user->lat = $request->lat;
        $user->lng = $request->lng;
        $user->fcm_token = $request->globalToken;

        $user->save();
        return $user;
    }
    function register(Request $request)
    {
        /**
         * Get a validator for an incoming registration request.
         *
         * @param  array  $request
         * @return \Illuminate\Contracts\Validation\Validator
         */
        $valid = validator($request->all(), [
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'email' => 'required|string|email|max:255|unique:users',
            'name' => 'required|string|max:255',
            'gov_id' => 'required|integer',
            'picture'   =>  'required',

            'day' => 'required',
            'month' => 'required',
            'year' => 'required',

            'gender' => 'required'
        ]);

        if ($valid->fails()) {
            return response()->json(["errors" => $valid->errors()->all()], 400);
        }

        $image = $request->picture['avatar']['data'];
        $name = $request->picture['avatar']['fileName'];
        $image = str_replace('data:image/jpeg;base64,', '', $image);
        \File::put(storage_path(). '/app/public/profile/' . $name, base64_decode($image));
        $dbPath = "/storage/profile/".$name;
        $user = User::create([
            'id' => \request('gov_id'),

            'name' => \request('name'),
            'username' => \request('username'),
            'email' => \request('email'),
            'password' => Hash::make(\request('password')),
            'profile_pic'   =>  $dbPath,
            'dob' => Carbon::createFromDate(\request('year')['data'],\request('month')['position']+1, \request('day')['data'])->format('Y-m-d'),
            'balance' => 0,
            'status' => 1,
            'gender' => \request('gender')['position'],
            'access_level' => 0,

        ]);

        // And created user until here.

        $client = Client::where('password_client', 1)->first();

        // Is this $request the same request? I mean Request $request? Then wouldn't it mess the other $request stuff? Also how did you pass it on the $request in $proxy? Wouldn't Request::create() just create a new thing?

        $request->request->add([
            'grant_type'    => 'password',
            'client_id'     => $client->id,
            'client_secret' => $client->secret,
            'username'      => request('email'),
            'password'      => request('password'),
            'scope'         => null,
        ]);

        // Fire off the internal request.
        $token = Request::create(
            'oauth/token',
            'POST'
        );
        return \Route::dispatch($token);
    }
    function login(Request $request)
    {
        /**
         * Get a validator for an incoming registration request.
         *
         * @param  array  $request
         * @return \Illuminate\Contracts\Validation\Validator
         */
        $valid = validator($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($valid->fails()) {
            return response()->json(["errors" => $valid->errors()->all()], 400);
        }

        // And created user until here.

        $client = Client::where('password_client', 1)->first();

        // Is this $request the same request? I mean Request $request? Then wouldn't it mess the other $request stuff? Also how did you pass it on the $request in $proxy? Wouldn't Request::create() just create a new thing?

        $request->request->add([
            'grant_type'    => 'password',
            'client_id'     => $client->id,
            'client_secret' => $client->secret,
            'username'      => request('email'),
            'password'      => request('password'),
            'scope'         => null,
        ]);

        // Fire off the internal request.
        $token = Request::create(
            'oauth/token',
            'POST'
        );
        return \Route::dispatch($token);
    }
    function changePassword(Request $request) {
        $data = $request->all();
        $user = Auth::guard('api')->user();

        //Changing the password only if is different of null
        if( isset($data['oldPassword']) && !empty($data['oldPassword']) && $data['oldPassword'] !== "" && $data['oldPassword'] !=='undefined') {
            //checking the old password first
            $check  = Auth::guard('web')->attempt([
                'username' => $user->username,
                'password' => $data['oldPassword']
            ]);
            if($check && isset($data['newPassword']) && !empty($data['newPassword']) && $data['newPassword'] !== "" && $data['newPassword'] !=='undefined') {
                $user->password = bcrypt($data['newPassword']);
                $user->isFirstTime = false; //variable created by me to know if is the dummy password or generated by user.
                $user->token()->revoke();
                $token = $user->createToken('newToken')->accessToken;

                //Changing the type
                $user->save();

                return json_encode(array('token' => $token)); //sending the new token
            }
            else {
                return "Wrong password information";
            }
        }
        return "Wrong password information";
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = \Auth::user();
//        $user->request = $;
        $user->requests_count = count($user->getRequests());
        return response()->json($user, 200);
    }
    public function indexCitizen() {
        $access_level = 0;
        $results = User::where('access_level', $access_level)->paginate(15);
        return view('users.index', compact('results', 'access_level'));
    }

    public function indexCollector() {
        $access_level = 1;
        $results = User::where('access_level', $access_level)->paginate(15);
        return view('users.index', compact('results', 'access_level'));
    }

    public function indexCollection() {
        $access_level = 2;
        $results = User::where('access_level', $access_level)->paginate(15);
        return view('users.index', compact('results', 'access_level'));
    }

    public function indexFactory() {
        $access_level = 3;
        $results = User::where('access_level', $access_level)->paginate(15);
        return view('users.index', compact('results', 'access_level'));
    }

    public function create($access_level) {
        return view('users.create', compact('access_level'));
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

        /**
         * Get a validator for an incoming registration request.
         *
         * @param  array  $request
         * @return \Illuminate\Contracts\Validation\Validator
         */
        $valid = validator($request->all(), [
            'gov_id' => 'required',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|confirmed|string|min:6',
            'access_level' => 'required|in:0,1,2,3,4',
            'name' => 'required',
            'dob' => 'required',
        ]);

        if ($valid->fails()) {
            return response()->json(["errors" => $valid->errors()->all()], 400);
        }
        $path = $request->file('profile_pic')->store('public/profile');
        $url = Storage::url($path);


        $user = new User();
        $user->id = $request->gov_id;
        $user->name = $request->name;
        $user->username = $request->name;
        $user->email = $request->email;
        $user->profile_pic = $url;
        $user->password = \Hash::make($request->password);
        $user->dob = $request->dob;
        $user->access_level = $request->access_level;

        $user->gender = $request->gender;

        $user->save();
        if ($user->access_level == 0)  $route = 'admin.users.citizen';
        if ($user->access_level == 1)  $route = 'admin.users.collector';
        if ($user->access_level == 2)  $route = 'admin.users.collection';
        if ($user->access_level == 3)  $route = 'admin.users.factory';
        return redirect(route($route));


    }
    public function editView($id) {
        $user = User::where('id', $id)->get()->first();
        return view('users.edit', compact('user'));
    }
    public function edit(Request $request, $id) {
        $valid = validator($request->all(), [
            'gov_id' => 'required',
            'name' => 'required',
            'dob' => 'required',
        ]);

        if ($valid->fails()) {
            return response()->json(["errors" => $valid->errors()->all()], 400);
        }
        $user = User::where('id', $id)->get()->first();

        if ($request->hasFile('profile_pic')) {
            $path = $request->file('profile_pic')->store('public/profile');
            $url = Storage::url($path);
            $user->profile_pic = $url;
        }


        $user->id = $request->gov_id;
        $user->name = $request->name;
        $user->username = $request->name;
        $user->email = $request->email;
        $user->password = \Hash::make($request->password);
        $user->dob = $request->dob;

        $user->gender = $request->gender;

        $user->save();
        if ($user->access_level == 0)  $route = 'admin.users.citizen';
        else if ($user->access_level == 1)  $route = 'admin.users.collector';
        else if ($user->access_level == 2)  $route = 'admin.users.collection';
        else if ($user->access_level == 3)  $route = 'admin.users.factory';
        return redirect(route($route));
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    public function release() {
        $balance = User::sum('on_hold');

        return view('release.index', compact('balance'));
    }

    public function doRelease() {
        foreach (User::all() as $user){
            $user->balance += $user->on_hold;
            $user->on_hold = 0;
            $user->save();
            if ($user->balance > 0) {
                if ($user->fcm_token){
                    OneSignalFacade::sendNotificationToUser(
                        $user->balance."EGP Payment Released Today And You Can Withdraw It from your nearest bank",
                        $user->fcm_token,
                        $url = null,
                        $data = null,
                        $buttons = null,
                        $schedule = null
                    );
                }

                $notifcation = new Notification();
                $notifcation->user_id = $user->id;
                $notifcation->message =  $user->balance."EGP Payment Released Today And You Can Withdraw It from your nearest bank";
                $notifcation->type = 'text';
                $notifcation->request_id = 0;
                $notifcation->save();
            }

        }
        return  redirect(route('home'));

    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        //
        /**
         * Get a validator for an incoming Update request.
         *
         * @param  array  $request
         * @return \Illuminate\Contracts\Validation\Validator
         */
        $valid = validator($request->only("email", "name", "password"), [
            "name" => 'string|max:255',
            "email" => 'string|email|max:255',
            "password" => 'string|min:6',
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return \Response::json($jsonError);
        }


        $data = $request->only("email", "name","password", "device_uuid");

        $user = \Auth::user();

        $data['email']  = !empty($data['email']) && count(User::where('email', $data['email'])->get()) <= 1 ? $data['email']: $user->email;
        $data['device_uuid'] = isset($data['device_uuid']) && !empty($data['device_uuid']) ? $data['device_uuid'] : $user->device_uuid;
        $data['name'] = isset($data['name']) && !empty($data['name']) ? $data['name'] : $user->name;
        $data['password'] = isset($data['password']) && !empty($data['password']) ? \Hash::make($data['password']) : $user->password;

        $user->email = $data['email'];
        $user->device_uuid = $data['device_uuid'];
        $user->name = $data['name'];
        $user->password = $data['password'];
        $user->save();

        return \response()->json(["success" => 1]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $user = User::where('id', $id)->get()->first();
        if ($user->access_level == 0)  $route = 'admin.users.citizen';
        if ($user->access_level == 1)  $route = 'admin.users.collector';
        if ($user->access_level == 2)  $route = 'admin.users.collection';
        if ($user->access_level == 3)  $route = 'admin.users.factory';
        $user->delete();

        return redirect(route($route));

    }
}
