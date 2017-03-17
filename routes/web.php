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

/*
|--------------------------------------------------------------------------
| Object Parsing
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'parse'], function () {
    Route::post('people', [
        'as'   => 'parse.people',
        'uses' => 'Parse@people',
    ]);
});

Route::get('/', function () {
    return view('welcome');
});
