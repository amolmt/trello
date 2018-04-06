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

Route::get('/', 'BoardController@index');

Route::post('/create-board', 'BoardController@store');

Route::get('/showBoard', 'BoardController@show');

Route::post('/create-card', 'CardController@store');

Route::get('/showCard', 'CardController@show');

Route::get('/indexCard', 'CardController@index');

Route::post('/delete-card', 'CardController@destroy');

Route::post('/delete-board', 'CardController@destroyBoard');