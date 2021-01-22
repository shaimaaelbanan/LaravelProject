<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/',function(){
    return redirect(route('login'));
});


Route::group(['prefix'=>'admin','namespace'=>'admin', 'middleware'=>['auth', 'verified']],function (){

    Route::get('dashboard',function(){
        return view('en.admin.dashboard.index');
    });

    Route::group(['prefix'=>'products','namespace'=>'product'],function (){
        Route::get('all-products','productController@index');
        Route::get('add-product','productController@create')->middleware('password.confirm');
        Route::post('create','productController@store');
        Route::get('edit-product/{route_parameter}','productController@edit');
        Route::put('update-product/{route_parameter}','productController@update');
        Route::delete('delete','productController@delete');
    });

    Route::group(['prefix'=>'user','namespace'=>'user'],function (){
    });
});
Route::group(['prefix'=>'user','namespace'=>'user'],function (){

});




Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');
