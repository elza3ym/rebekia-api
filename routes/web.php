<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', 'HomeController@index')->name('home');

Route::Patch('user/updateLocation', 'UserController@location')->middleware('auth')->name('updateLocation');
Route::get('/', function(){return redirect()->route('login');});
Route::group(['prefix' => 'collection', 'middleware' => 'auth'], function() {

    // Home Page 
    Route::get('/', 'HomeController@indexCollection');

    //  Collection Point Imports / Collector Requests to a collection Point
    Route::get('imports', 'CollectionPointController@imports')->name('collectionAllImport');
    Route::get('imports/{id}', 'CollectionPointController@importsInfo')->name('collectionImport');
    Route::put('/imports/{id}', 'CollectionPointController@importsInfoUpdate')->name('collectionImportUpdate');

    // Collection Point Exports / Factory Requests to a collection Point 
    Route::get('exports', 'CollectionPointController@exports')->name('exports');

    Route::get('exports/new', 'CollectionPointController@exportCreate')->name('collectionCreateRequest');
    Route::get('exports/factory', 'CollectionRequestController@indexFactories')->name('indexFactories');

    Route::post('exports', 'CollectionRequestController@Sessionstore')->name('newExport');
    Route::post('exports/requestStore', 'CollectionRequestController@store')->name('requestStore');

    Route::put('/exports/{id}', 'CollectionPointController@exportsInfoUpdate')->name('collectionExportUpdate');

    Route::get('exports/{id}', 'CollectionPointController@exportsInfo')->name('collectionexport');

    // Collection Point Inventory 
    Route::get('inventory', 'CollectionPointController@inventory')->name('collectionInventory');

});
Route::group(['prefix' => 'factory', 'middleware' => 'auth'], function() {
    // Home Page 
    Route::get('/', 'HomeController@indexFactory');


    Route::get('imports', 'FactoryController@imports')->name('factoryAllImport');
    Route::get('imports/{id}', 'FactoryController@importsInfo')->name('factoryImport');
    Route::put('/imports/{id}', 'FactoryController@importsInfoUpdate')->name('factoryImportUpdate');


    Route::get('inventory', 'FactoryController@inventory')->name('factoryInventory');


});
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function() {
    Route::get('/', 'HomeController@indexAdmin');
    Route::get('/users/citizen', 'UserController@indexCitizen')->name('admin.users.citizen');
    Route::get('/users/collector', 'UserController@indexCollector')->name('admin.users.collector');
    Route::get('/users/collection', 'UserController@indexCollection')->name('admin.users.collection');
    Route::get('/users/factory', 'UserController@indexFactory')->name('admin.users.factory');
    Route::get('/users/create/{access_level}', 'UserController@create')->name('admin.users.create');
    Route::post('/users', 'UserController@store')->name('admin.users.store');
    Route::get('/users/{id}', 'UserController@editView')->name('admin.users.editView');
    Route::put('/users/{id}', 'UserController@edit')->name('admin.users.edit');
    Route::delete('/users/{id}', 'UserController@destroy')->name('admin.users.delete');

    Route::get('/reports', 'ReportController@index')->name('admin.reports');
    Route::delete('/reports/{id}', 'ReportController@destroy')->name('admin.reports.delete');

    Route::get('/payment', 'UserController@release')->name('admin.payment');
    Route::post('/payment', 'UserController@doRelease')->name('doRelease');

});
Auth::routes();

Route::group(['middleware' => 'auth'], function() {

    Route::get('/home', 'HomeController@index')->name('home');

});
