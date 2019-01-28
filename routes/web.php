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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/signup', 'HomeController@index')->name('home')->middleware('guest');


// Logged in Routes
Route::group(['middleware' => 'auth'], function () {
    Route::get('/metronic', 'HomeController@metronic');
    Route::get('/fudge', 'HomeController@fudge');

    //Route::get('/data/people', 'Account\AccountController@getPeople');
    Route::resource('/account', 'Account\AccountController');

    Route::get('/data/people', 'People\PeopleController@getPeople');
    Route::resource('/people', 'People\PeopleController');

    Route::get('/data/schools-by-grade/{grade}', 'People\SchoolController@schoolsByGrade');

    Route::get('/event', 'HomeController@event');
    Route::get('/group', 'HomeController@group');
    Route::get('/import-students', 'HomeController@importStudents');
});