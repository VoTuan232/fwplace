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

Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'checkLogin'], function () {
    Route::get('/', 'DashboardController@index');
    Route::resource('positions', 'PositionController');
    Route::resource('workspaces', 'WorkspaceController');
    Route::resource('programs', 'ProgramController');
    Route::resource('locations', 'LocationController');
    Route::resource('users', 'UserController');
    Route::group(['prefix' => 'schedule', 'as' => 'schedule.'], function () {
        Route::get('/', 'WorkingScheduleController@chooseWorkplace')->name('workplace.list');
        Route::get('/{id}', 'WorkingScheduleController@viewByWorkplace')->name('workplace.view');
        Route::get('/{id}/get', 'WorkingScheduleController@getData')->name('workplace.get_data');
        Route::get('location/{id}', 'WorkingScheduleController@viewByLocation')->name('location.month');
    });
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/logout', 'HomeController@logout');
Route::resource('user', 'UserController')->middleware('checkUser');
