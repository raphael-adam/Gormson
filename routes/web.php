<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

/*Route::resource('welcome', 'VacationsController');*/

Route::get('leave', 'VacationController@store');
Route::get('ics', 'IcsDataController@store');
