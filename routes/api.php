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
//Route::post('login', 'PubUserController@login')->name('api.jwt.login');

//------------- certificate --------------
Route::post('register', 'CertificateController@register');
Route::post('token', 'CertificateController@authenticate');
Route::post('emailVerify', 'CommonController@emailVerify');

##################### auth ################################
Route::group(['middleware'=>'jwt.auth'], function () {
//********* profile *********
    Route::get('getProfile', 'ProfileController@getProfile');
    Route::get('getAdminProfile', 'ProfileController@getAdminProfile');
//********** common ************
    Route::post('uploadPhoto', 'CommonController@uploadPhoto');
    Route::post('getUserInfo', 'CommonController@getUserInfo');
});
