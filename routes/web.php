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
Route::get('/logout', 'Auth\LoginController@logout');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/signup', 'HomeController@index')->name('home')->middleware('guest');


// Logged in Routes
Route::group(['middleware' => 'auth'], function () {
    Route::get('/metronic', 'HomeController@metronic');
    Route::get('/fudge', 'HomeController@fudge');


    // Protect access the People photos + thumbs
    Route::get('/image/{aid}/{type}/{filename}', 'Misc\FileController@getPhoto'); //->where('filename', '^(.+)\/([^\/]+)$');
    //Route::get('/storage/{aid}/thumbs/{filename}', 'Misc\FileController@getThumb'); //->where('filename', '^(.+)\/([^\/]+)$');

    //Route::get('/data/people', 'Account\AccountController@getPeople');
    Route::resource('/account', 'Account\AccountController');

    // People
    Route::get('/data/people', 'People\PeopleController@getPeople');
    Route::get('/data/people/search-add', 'People\PeopleController@searchAddPeople');
    Route::any('/people/{id}/status/{status}', 'People\PeopleController@status');
    Route::get('/people/{id}/del', 'People\PeopleController@destroy');
    Route::post('/people/{id}/photo', 'People\PeopleController@updatePhoto');
    Route::resource('/people', 'People\PeopleController');

    // Households
    Route::get('/data/household/members/{id}', 'People\HouseholdController@getMembers');
    Route::get('/data/household/households/{id}', 'People\HouseholdController@getHouseholds');
    Route::get('/household/{hid}/member/{pid}/add', 'People\HouseholdController@addMember');
    Route::get('/household/{hid}/member/{pid}/del', 'People\HouseholdController@delMember');
    Route::post('/household/update', 'People\HouseholdController@updateHousehold');
    Route::resource('/household', 'People\HouseholdController');

    // Schools
    Route::get('/data/schools-by-grade/{grade}', 'People\SchoolController@schoolsByGrade');

    // Events
    Route::get('/data/event/dates/{id}', 'Event\EventController@getDates');
    Route::get('/data/event/people/{id}', 'Event\EventController@getPeople');
    Route::get('/event/{id}/settings/', 'Event\EventController@settings');
    Route::get('/event/{id}/attendance/{date}', 'Event\EventController@attendance');
    Route::any('/event/{id}/status/{status}', 'Event\EventController@status');
    Route::get('/event/{id}/del', 'Event\EventController@destroy');
    Route::post('/event/{id}/photo', 'Event\EventController@updatePhoto');
    Route::resource('/event', 'Event\EventController');

    // Event Instance
    Route::get('/event/instance/{id}/del', 'Event\EventInstanceController@destroy');
    Route::resource('/event/instance', 'Event\EventInstanceController');

    // Checkin
    Route::get('/data/checkin/people/{id}', 'Event\CheckinController@getPeople');
    Route::get('/checkin/{id}/register/student', 'Event\CheckinController@studentForm');
    Route::post('/checkin/{id}/register/student', 'Event\CheckinController@studentRegister');
    Route::get('/checkin/{id}/register/volunteer', 'Event\CheckinController@volunteerForm');
    Route::post('/checkin/{id}/register/volunteer', 'Event\CheckinController@volunteerRegister');
    Route::resource('/checkin', 'Event\CheckinController');

    // Attendance
    Route::resource('/attendance', 'Event\AttendanceController');



    Route::get('/group', 'HomeController@group');
    Route::get('/import-students', 'HomeController@importStudents');


    // Search
    Route::view('/search', 'search');
    Route::get('/user/find', 'People\SearchController@searchUsers');
});