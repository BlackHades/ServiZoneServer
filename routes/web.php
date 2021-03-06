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

use Illuminate\Support\Collection;

Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
    Route::get('/user/block/{id}', ['uses' => 'UserController@block']);
    Route::get('/user/unblock/{id}', ['uses' => 'UserController@unblock']);
    Route::get('/expert/block/{id}', ['uses' => 'UserController@block']);

    Route::get('/expert/unblock/{id}', ['uses' => 'UserController@unblock']);
    Route::get('/expert/approve/{id}', ['uses' => 'ExpertController@approve']);
});

Route::get('example', ['uses' => 'UserController@example']);
Route::get('all', ['uses' => 'HomeController@all']);

Route::get('extract/links', ['uses' => 'ExpertExtractController@extractLinks']);
Route::get('extract/numbers', ['uses' => 'ExpertExtractController@phone']);
Route::get('extract/register/links', ['uses' => 'ExpertExtractController@registerLinks']);

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});



Route::get('test', function (){
    $data = new Collection();
    $data->name = "Name";
    $data->code = "Cide";
   dd($data, $data->name);
});

Route::get('clear', function (){
   \Illuminate\Support\Facades\Artisan::call('cache:clear');
   \Illuminate\Support\Facades\Artisan::call('config:cache');
   \Illuminate\Support\Facades\Artisan::call('view:clear');
   dd('done');
});

Route::get('email', function(){
//    $beautymail = app()->make(Snowfire\Beautymail\Beautymail::class);
//    $beautymail->send('emails.welcome', [], function($message)
//    {
//        $message
//            ->from('bar@example.com')
//            ->to('foo@example.com', 'John Smith')
//            ->subject('Welcome!');
//    });
    return view('emails.welcome');

});