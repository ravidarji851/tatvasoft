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

Route::get('/', function () {
    return view('welcome');
});
Route::prefix('event')->group(function(){

	Route::get('/','App\Http\Controllers\EventController@index')->name('event.list');
	Route::get('/create','App\Http\Controllers\EventController@create')->name('event.create');
	Route::post('/store','App\Http\Controllers\EventController@store')->name('event.store');
	Route::get('/view/{id?}','App\Http\Controllers\EventController@view')->name('event.view');
	Route::get('/edit/{id?}','App\Http\Controllers\EventController@edit')->name('event.edit');
	Route::post('/update/','App\Http\Controllers\EventController@update')->name('event.update');
	Route::post('/delete/','App\Http\Controllers\EventController@delete')->name('event.delete');



	Route::get('/ajax/list','App\Http\Controllers\EventController@list')->name('event.ajax.list');



});

