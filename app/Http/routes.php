<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('user/login', 'UserController@login')->name('user_login');
Route::get('user/handleLogin', 'UserController@handleLogin')->name('user_handleLogin');
Route::get('place/getMaxPlace', 'PlaceController@getMaxPlace')->name('place_getMaxPlace');
Route::get('place/getNextPlace', 'PlaceController@getNextPlace')->name('place_getNextPlace');
Route::get('schedules/getSchedulesAll', 'SchedulesController@getSchedulesAll')->name('schedules_getSchedulesAll');
Route::post('schedules/getSchedulesList', 'SchedulesController@getSchedulesList')->name('schedules_getSchedulesList');

Route::get('getServicePhone', 'ServicePhoneController@getServicePhone')->name('getServicePhone');
Route::get('getUserNotice', 'CommonController@getUserNotice')->name('getUserNotice');
