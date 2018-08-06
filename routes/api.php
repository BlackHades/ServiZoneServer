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

/*
  |----------------------------------------------------
  | Home Route
  |---------------------------------------------------- */
Route::post('home', 'HomeController@index');

Route::any('logout','AuthController@logout')->middleware('fincoAuth');

/*
  |----------------------------------------------------
  | Auth Routes
  |---------------------------------------------------- */
Route::post('login', 'AuthController@login');
Route::post('logout', 'AuthController@logout');
Route::post('register', ['uses' => 'AuthController@register']);

Route::group(['middleware' => ['fincoAuth']], function (){
    Route::post('logout', 'AuthController@logout');
});

Route::group(['prefix' => '/password/', 'middleware' => ['fincoAuth']], function (){
   Route::post('change','PasswordController@change');
});


/*
  |----------------------------------------------------
  | Profession Routes
  |---------------------------------------------------- */
Route::group(['prefix' => 'professions'], function() {
    Route::post('', array('uses' => 'ProfessionController@index'));
    Route::post('search', 'ProfessionController@search');
});

/*
  |----------------------------------------------------
  | Experts Routes
  |---------------------------------------------------- */
Route::post('search', 'ExpertController@search');
Route::post('experts/report', 'ExpertController@report')->middleware('fincoAuth');

/*
  |----------------------------------------------------
  | Users Routes
  |---------------------------------------------------- */
Route::group(['middleware' => ['fincoAuth'], 'prefix' => 'users'], function() {
    Route::post('edit', 'Api\UserController@edit');
    Route::post('search', 'UserController@index');
    Route::post('search', 'UserController@search');
    Route::post('logout','AuthController@removeToken');
});


/*
  |----------------------------------------------------
  | Reviews Routes
  |---------------------------------------------------- */
Route::group(['prefix' => 'reviews'], function() {
    Route::get('/', 'ReviewController@index');
    Route::post('add', 'ReviewController@add')->middleware('fincoAuth');
});

Route::get('email', function(){
    return view('emails.expert-approved');
});