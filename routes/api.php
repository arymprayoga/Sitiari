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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::apiResources(['admin' => 'API\AdminController']);

//index
Route::get('admin','API\AdminController@index');

//admin store
Route::post('admin','API\AdminController@store');

//admin update
Route::put('admin{id}','API\AdminController@update');

//admin destroy
Route::delete('admin{id}','API\AdminController@destroy');

//admin show pekerja 
Route::get('admin/showPekerja','API\AdminController@showPekerja');

//admin add pekerja
Route::post('admin/addPekerja','API\AdminController@addPekerja');

//admin edit pekerja
Route::put('admin/editPekerja{id}','API\AdminController@editPekerja');

//admin hapus pekerja
Route::delete('admin/hapusPekerja{id}','API\AdminController@hapusPekerja');
