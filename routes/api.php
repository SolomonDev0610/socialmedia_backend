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
//Route::group(['middleware'=>'jwt.auth'], function () {
//********** common ************
    Route::post('changeProfilePhoto', 'CommonController@uploadPhoto');
    Route::post('uploadPhoto', 'CommonController@uploadPhoto');
    Route::get('getUserInfo', 'CommonController@getUserInfo');
    Route::post('saveUserInfo', 'CommonController@saveUserInfo');

//********** politicalParties ************
    Route::post('createPoliticalParty', 'PoliticalPartyController@createPoliticalParty');
    Route::post('createPoliticalParty', 'PoliticalPartyController@updatePoliticalParty');
    Route::get('getPoliticalPartyList', 'PoliticalPartyController@getPoliticalPartyList');
    Route::get('getPoliticalPartyDetail', 'PoliticalPartyController@getPoliticalPartyDetail');
    Route::post('deletePoliticalParty', 'PoliticalPartyController@deletePoliticalParty');

//********** Posts ************
    Route::apiResource('/posts', 'PostController');
    Route::get('get_total_score', 'PostController@get_total_score');
    Route::get('get_scores_by_party', 'PostController@get_scores_by_party');
    Route::get('post_count', 'PostController@post_count');

//********** Comments ************
    Route::apiResource('/comments', 'CommentController');
    Route::get('getChildComments', 'CommentController@getChildComments');
    Route::get('getAllLevelChildIds', 'CommentController@getAllLevelChildIds');
    Route::get('upVote', 'CommentController@upVote');
    Route::get('downVote', 'CommentController@downVote');
    Route::get('Comment_count', 'CommentController@Comment_count');
//});
