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
/*
  |----------------------------------------------------
  | Auth Routes
  |---------------------------------------------------- */

//No Token



Route::group(['prefix' => 'v1/'], function (){

    //Auth
    Route::post('login', 'Api\AuthController@login');
    Route::post('logout', 'Api\AuthController@logout');
    Route::post('register', ['uses' => 'Api\AuthController@register']);
    Route::post('social', ['uses' => 'Api\AuthController@social']);


    //Token Check
    Route::group(['middleware' => ['fincoAuth']], function (){
        Route::post('home', 'HomeController@index');
        Route::post('logout', 'Api\AuthController@logout');
        Route::post('upload/avatar','Api\UserController@uploadAvatar');
        Route::post('contact-us','Api\ContactController@create');

        Route::group(['prefix' => '/password/'], function (){
            Route::post('change','Api\PasswordController@change');
            Route::post('change/new','Api\PasswordController@changeNoToken');
        });

        Route::group(['prefix' => '/service/'], function (){
            Route::post('create', 'Api\ServiceController@create');
            Route::post('user','Api\ServiceController@getByUserId');
            Route::post('update/details', 'Api\ServiceController@updateDetails');
            Route::post('update/address', 'Api\ServiceController@updateAddress');
            Route::post('upload/avatar', 'Api\ServiceController@uploadAvatar');
            Route::post('delete', 'Api\ServiceController@delete');
            Route::post('search', 'Api\ServiceController@search');
            Route::post('close', 'Api\ServiceController@close');
        });

        /*
|----------------------------------------------------
| Users Routes
|---------------------------------------------------- */
        Route::group(['prefix' => 'users'], function() {
            Route::post('edit', 'Api\UserController@edit');
            Route::post('search', 'UserController@index');
            Route::post('search', 'UserController@search');
            Route::post('logout','AuthController@removeToken');
        });

        Route::group(['prefix' => '/review/'], function () {
            Route::post('add', 'Api\ReviewController@add');
            Route::post('service', 'Api\ReviewController@getByServiceId');

        });
    });


    Route::get('review/{service_id}', 'Api\ReviewController@getByServiceId');


    Route::group(['prefix' => '/password/'], function (){
        Route::post('forgot','Api\PasswordController@forgot');
    });
       
    Route::group(['prefix' => '/profession/'], function (){
        Route::get('all', 'Api\ProfessionController@all');
        Route::post('user','Api\ServiceController@getByUserId');
    });

    Route::post('verify/token', 'Api\VerificationController@token');
});

/*
  |----------------------------------------------------
  | Profession Routes
  |---------------------------------------------------- */
 
 
 /* |----------------------------------------------------
  | Experts Routes
  |---------------------------------------------------- */
Route::post('search', 'ExpertController@search');
Route::post('experts/report', 'ExpertController@report')->middleware('fincoAuth');




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

