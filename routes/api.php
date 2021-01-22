<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::get('admin/product/all-products','apiController@index');

Route::group(['prefix'=>'admin'], function () {

    Route::group(['prefix'=>'product','namespace'=>'product', 'middleware'=>'checkToken'], function(){
        Route::get('all-products','productController@index');
        Route::post('add-product','productController@store');
        Route::post('update-product','productController@update');
        Route::delete('delete-product','productController@unlink');
        // Route::delete('delete','productController@delete');
    });

    Route::group(['prefix'=>'subCate','namespace'=>'subCats'], function(){

    });
    Route::group(['prefix'=>'user','namespace'=>'auth'], function(){
        Route::post('register','authController@register');
        Route::post('login','authController@login');

        Route::group(['middleware' => 'checkToken'], function(){
            Route::post('check-code','authController@checkCode');
            Route::get('profile','authController@profile');
            Route::post('update-profile','authController@updateProfile');
            Route::post('send-code','authController@sendCode');
            Route::post('logout','authController@logout');
        });

    });


});

// Auth::routes(['verify' => true]);

// Route::get('/home', 'HomeController@index')->name('home');
