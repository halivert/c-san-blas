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

// Route::get('/', function () {
//     return view('welcome');
// });
Auth::routes();

Route::name('home.')->group(function() {
    Route::get('/', 'HomeController@index')->name('index');
    Route::post('/', 'HomeController@login')->name('login');
    Route::get('/marked-houses', 'HomeController@markedHouses')
        ->name('marked-houses');
    Route::get('/exhibitors', 'HomeController@exhibitors')
        ->name('exhibitors');
});
