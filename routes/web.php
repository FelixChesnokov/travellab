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

Route::get('/', 'App\Http\Controllers\MainController@index')->name('main.index');
Route::get('/get-cities-data/{countryCode}', 'App\Http\Controllers\MainController@getCitiesData')->name('main.getCitiesData');


Route::post('/generate-travel', 'App\Http\Controllers\MainController@generateTravel')->name('main.generateTravel');
