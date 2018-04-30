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

// Auth
Route::view('login', 'login')->name('login');
Route::get('logout', 'LoginController@logout')->name('logout');
Route::post('/do.login', 'LoginController@do');

Route::get('test.login', function(){
    $result = Auth::attempt(['samaccountname' => 'auttawir', 'password' => 'admin123456']);
    $user = $user = Adldap::search()->users()->find('auttawir');
    echo Auth::check() ? 'yes' : 'no';
    echo '<br>';
    print_r($user);
});

//Share
Route::prefix('share')->group(function () {
    Route::get('{id}', 'ShareController@show');
});

Route::group(['middleware' => ['auth', 'auth.admin'], 'prefix' => 'admin'], function () {

    Route::get('/', 'AdminController@index')->name('admin.index');

    Route::prefix('user')->group(function () {
        Route::get('/', 'MemberController@index')->name('admin.user');
    });

});

Route::group(['middleware' => ['auth']], function () {

	//Service
    Route::prefix('services')->group(function () {
        Route::get('genqrcode/{id}', 'servicesController@genQrCode');
    });

    //File
    Route::prefix('file')->group(function () {
        Route::get('{id}', 'fileController@show');
    });

    //React Application
    Route::get('/{path?}', function () {
        return view('home');
    })->where('path', '\b(?!ignoreme|ignoreyou)\b\S+')->name('react');

});



