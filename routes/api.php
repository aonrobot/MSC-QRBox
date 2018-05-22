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

//Service    
Route::middleware('api')->prefix('services')->group(function () {
    Route::get('genqrcode/{id}', 'servicesController@genQrCode');
    Route::post('gencustomqrcode', 'servicesController@genCustomQrCode');    
});

//Employee
Route::middleware('api')->prefix('employee')->group(function () {
    Route::get('show/{id}', 'MemberController@show');    
    Route::post('store', 'MemberController@store');
    Route::post('update/{id}', 'MemberController@update');
    Route::get('destroy/{id}', 'MemberController@destroy');
    Route::get('info/{id}', 'MemberController@info');
    Route::get('isAdmin/{loginUserCrypt}', 'MemberController@isAdmin'); 
});

//File
Route::middleware('api')->prefix('file')->group(function () {
    Route::get('{login}', 'fileController@index');
    Route::post('store', 'fileController@store');    
    Route::post('delete', 'fileController@destroy');
    Route::post('update/{id}', 'fileController@update');
    
    Route::post('listfile/table', 'fileController@listFileTable');

    Route::post('share', 'fileController@share');
    Route::post('unshare', 'fileController@unShare');
    Route::post('get/isShare', 'fileController@getIsShare');
});

Route::middleware('api')->post('/uploadBox', 'uploadController@store');
Route::middleware('api')->delete('/uploadBox', 'uploadController@destroy');

//Test    
Route::middleware('api')->prefix('testFn')->group(function () {
    Route::get('mime', 'testController@mime');    
});
