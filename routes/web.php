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

//Auth
Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

//Home
Route::get('/home', 'HomeController@index')->name('home');


//Customers
Route::group(['prefix' => 'customers'], function () {
    // Route::get('', 'CustomerController@index')->name('customers.index');
    // 以下name変更,リクエストURLが同じでPostの場合indexメソッドでないため
    Route::get('', 'CustomerController@index')->name('customers');
    Route::post('', 'CustomerController@select');
    Route::get('{id}','CustomerController@detail')->name('customers.detail');
    Route::post('create', 'CustomerController@create');
    Route::post('update','CustomerController@update')->name('customers.update');
    Route::post('delete','CustomerController@delete')->name('customers.delete');
    Route::post('sale/create','CustomerController@saleCreate')->name('sale.create');
    Route::post('sale/update','CustomerController@saleUpdate')->name('sale.update');
    Route::post('sale/delete','CustomerController@saleDelete')->name('sale.delete');
    Route::get('sale/display','CustomerController@saleDisplay');
});
