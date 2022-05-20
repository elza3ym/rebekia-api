<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'username', 'email', 'password', 'profile_pic', 'dob', 'balance', 'status', 'gender', 'access_level', 'lat', 'lng'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getRequests() {
        $user = \Auth::user();
        if ($user->access_level === 0) return citizenRequest::where('citizen_id', '=', $user->id)->get();
        if ($user->access_level === 1) return citizenRequest::where('collector_id', '=', $user->id)->get();
        if ($user->access_level === 2) return collectorRequest::where('collectionPoint_id', '=', $user->id)->get();
        if ($user->access_level === 3) return collectionRequest::where('factory_id', '=', $user->id)->get();
        if ($user->access_level === 4) {
            $citizen = citizenRequest::all();
            $collector = collectorRequest::all();
            $collection = collectionRequest::all();

            $out = array_merge(json_decode($citizen, true), json_decode($collector, true), json_decode($collection, true));
            return $out;
        }
    }
}
