<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Account Management
Route::POST('register','UserController@register');
Route::POST('login','UserController@login');


Route::get('/user','UserController@index');
Route::group(['middleware' => 'auth:api'], function () {
    Route::resource('requests', 'CitizenRequestController');
    Route::resource('items', 'ItemController');
    Route::resource('notifications', 'NotificationController');
    Route::resource('reports', 'ReportController');
    Route::Patch('user/updateLocation', 'UserController@location');
    Route::resource('collectors', 'CollectorController');
    Route::resource('collections', 'CollectionPointController');
});
//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


// Collectors Routes
Route::group(['middleware' => 'auth:api', 'prefix' => 'collector'], function () {
    Route::get('inventory', 'CollectorController@inventory');
    Route::resource('requests', 'CollectorRequestController');
});