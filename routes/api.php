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
    Route::get('temp/{path}', 'servicesController@showTempFile');
    Route::get('genqrcode/{url}', 'servicesController@genQrCode');    
});

//Employee
Route::middleware('api')->prefix('employee')->group(function () {
    Route::get('info/{id}', 'employeeController@infoAPI'); 
});

//File
Route::middleware('api')->prefix('file')->group(function () {
    Route::post('store', 'fileController@store');    
    Route::post('delete', 'fileController@destroy');
});


Route::middleware('api')->post('/uploadBox', 'uploadController@store');
Route::middleware('api')->delete('/uploadBox', 'uploadController@destroy');
