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
Route::get('/', 'UserController@index');

// route to process the form
Route::post('login', array('uses' => 'UserController@doLogin'));
post('logout', 'UserController@doLogout');

Route::get('logout', array('uses' => 'UserController@doLogout'));

Route::get('home', 'AdminController@home');

/*All authenticated urls*/
Route::group(['middleware' => 'auth'], function () {
    get('user/edit/{id}', 'UserController@edit');
    post('user/update', 'UserController@update');
    get('user/delete/{id}', 'UserController@destroy');
    get('user/add', 'UserController@create');
    post('user/save', 'UserController@saveUser');
    get('user/change-password', 'UserController@changePassword');
    post('user/save-new-password', 'UserController@saveNewPassword');
    get('user/edit-profile', 'UserController@editProfile');
    post('user/save-profile-update', 'UserController@saveProfileUpdate');

    get('time-tracker', 'TrackerController@listEntries');
    get('time-tracker-add', 'TrackerController@addTracker');
    post('time-tracker-save', 'TrackerController@saveTrackerEntry');
    post('time-tracker-delete', 'TrackerController@deleteTrackerEntry');

    get('tags', 'TagController@index');
    post('tags/save', 'TagController@create');

    get('project/estimates/{id}', 'ProjectController@addEstimate');
    post('project/estimates/save', 'ProjectController@saveEstimate');
    get('project/estimates/edit/{id}', 'ProjectController@editEstimate');
    post('project/estimates/update', 'ProjectController@updateEstimate');
    post('project/get-estimates', 'ProjectController@getProjectEstimates');
    get('project/delete/{id}', 'ProjectController@destroy');

    get('clients/delete/{id}', 'ClientController@destroy');

    get('role/delete/{id}', 'RoleController@destroy');

    Route::group(['prefix' => 'manager'], function () {
        get('time-tracker-report', 'ManagerController@getTimeReport');
        get('time-tracker-download', 'ManagerController@downloadReport');
        get('project-wise-download/{sdate}/{edate}', 'ManagerController@downloadProjectWiseReport');
        get('project-wise-detailed-download/{sdate}/{edate}', 'ManagerController@downloadProjectWiseDetailedReport');
        get('date-wise-download/{sdate}/{edate}', 'ManagerController@downloadDateWiseReport');
        get('create-pie-chart/{sdate}/{edate}', 'ManagerController@createPieChart');
    });

    Route::group(['prefix' => 'api'], function () {
        get('time-report', 'ApiController@getFilterReport');
        get('get-user-list', 'ApiController@getUserList');
        get('get-project-list', 'ApiController@getProjectList');
        post('time-report-filter', 'ApiController@getFilterReportSearch');
        get('get-timeentry-by-date', 'ApiController@getTimeSheetEntryByDate');
    });
});

Route::resource('project', 'ProjectController');

Route::resource('clients', 'ClientController');

Route::resource('role', 'RoleController');

App::bind('App\Services\Interfaces\SendMailInterface', 'App\Services\SESSendMail');
