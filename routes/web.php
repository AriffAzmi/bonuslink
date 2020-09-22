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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/bonuslink/balance', 'BonuslinkController@showBalance');
Route::post('/bonuslink/add-points', 'BonuslinkController@addPoints');
Route::post('/bonuslink/void-points', 'BonuslinkController@voidPoints');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');