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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('users','UsersController@index');

Auth::routes();
Route::get ( '/errors/403', 'Auth\ErrorsController@error403' )->name("403");
Route::name('index')->get('', 'HomeController@index');


Route::group(['prefix' => 'home', 'middleware' => 'auth'], function(){
    Route::name('home')
        ->get('', 'HomeController@index');
    Route::name('home.index')
        ->get('{action}', 'HomeController@index')
        ->where('action', '(?:challenge|admin)');
});

Route::group(['prefix' => 'mapsheet', 'middleware' => 'auth'], function(){
    Route::name('mapsheet')
        ->get('/{curriculumId}', 'MapSheetController@index')->where('curriculumId', '[0-9]+');
});

Route::group(['prefix' => 'unit', 'middleware' => 'auth'], function(){
    Route::name('unit')
        ->get('/{id}', 'UnitController@index')
        ->where('id', '[0-9]+');
});

Route::group(['prefix' => 'pdfdownload', 'middleware' => 'auth'], function(){
    Route::name('pdfdownload')
        ->get('/{type}/{workbookId}', 'PdfDownloadController@index')
        ->where('type', '(?:q|a)')
        ->where('workbookId', '[0-9]+');
});

Route::group(['prefix' => 'manualdownload', 'middleware' => 'auth'], function(){
    Route::name('manualdownload')
        ->get('/{type}', 'ManualDownloadController@index')
        ->where('type', '(?:school|challengeuser|relation)');
});

Route::group(['prefix' => 'recordinput', 'middleware' => 'auth'], function(){
    Route::name('recordinput')
        ->get('/{id}', 'RecordInputController@index')
        ->where('id', '[0-9]+');
    Route::name('recordinput.save')
        ->post('save', 'RecordInputController@save');
});

Route::group(['prefix' => 'aggregation', 'middleware' => 'auth'], function(){
    Route::name('aggregation')
        ->get('/{curriculumId?}/{mode?}/{cityId?}/{schoolId?}/', 'AggregationController@index')
        ->where('curriculumId', '[0-9]+')
        ->where('mode', '(city|school|class)')
        ->where('cityId', '[0-9]+')
        ->where('schoolId', '[0-9]+');

    Route::name('aggregation')
        ->post('/{curriculumId?}/{mode?}/{cityId?}/{schoolId?}/', 'AggregationController@index')
        ->where('curriculumId', '[0-9]+')
        ->where('mode', '(city|school|class)')
        ->where('cityId', '[0-9]+')
        ->where('schoolId', '[0-9]+');

    Route::name('aggregation.footer')
        ->post('/footer/', 'AggregationController@getFooterData');

    Route::name('exportExcel_1')
        ->post('export', 'AggregationController@index');

    Route::name('exportExcel_2')
        ->post('/{curriculumId?}/export', 'AggregationController@index');

    Route::name('exportExcel_3')
        ->post('/{curriculumId?}/{mode?}/export', 'AggregationController@index');

    Route::name('exportExcel_4')
        ->post('/{curriculumId?}/{mode?}/{cityId?}/export', 'AggregationController@index');

    Route::name('exportExcel_5')
        ->post('/{curriculumId?}/{mode?}/{cityId?}/{schoolId?}/export', 'AggregationController@index');

});

Route::group(['prefix' => 'individual', 'middleware' => 'auth'], function(){
    Route::name('individual')
        ->get('{curriculumId?}', 'IndividualController@index')
        ->where('curriculumId', '[0-9]+');
    Route::name('individual')
        ->post('{curriculumId?}', 'IndividualController@index')
        ->where('curriculumId', '[0-9]+');

    Route::name('individual.footer')
        ->post('/footer/', 'IndividualController@getFooterData');
    Route::name('exportExcel_1')
        ->post('export', 'IndividualController@index');
    Route::name('exportExcel_2')
        ->post('{curriculumId?}/export', 'IndividualController@index');
});

Route::group(['prefix' => 'setting', 'middleware' => 'auth'], function(){
    Route::name('setting')
        ->get('', 'SettingController@index');
    Route::name('setting.save')
        ->post('save', 'SettingController@save');
});

Route::group(['prefix' => 'messages', 'middleware' => 'auth'], function(){
    Route::name('messages')
        ->get('', 'MessagesController@index');
});

Route::group(['prefix' => 'users', 'middleware' => 'auth'], function(){
    Route::name('users')
        ->post('', 'UsersController@index');

    Route::name('users.index')
        ->post('index', 'UsersController@index');

    Route::name('users.update')
        ->post('update/{id}', 'UsersController@update')
        ->where('id', '[0-9]+');
    Route::name('exportExcel')
        ->post('export', 'UsersController@index');
});

Route::group(['prefix' => 'messages', 'middleware' => 'auth'], function(){
    //Ajax update message
    Route::name('msg.update')
        ->post('update/msg/{id}', 'MessagesController@updateMsg')
        ->where('id', '[0-9]+');

    //Ajax update condition
    Route::name('cond.update')
        ->post('update/cond/{id}', 'MessagesController@updateCond')
        ->where('id', '[0-9]+');
});



Route::group(['prefix' => 'classfiltering', 'middleware' => 'auth'], function(){
    //Ajax binding data combobox: get school by city id
    Route::name('classfiltering.get.schools')
        ->get('school/{cityId?}', 'ClassFilteringController@getSchoolByCityId')
        ->where('cityId', '[0-9]+');

    //Ajax binding data combobox: get class by school id
    Route::name('classfiltering.get.classes')
        ->get('class/{schoolId?}', 'ClassFilteringController@getClassBySchoolId')
        ->where('schoolId', '[0-9]+');
});

Route::group(['prefix' => 'backupdownload', 'middleware' => 'auth'], function(){
    Route::name('backupdownload')
        ->get('/{count?}', 'BackupDownloadController@index')
        ->where('count', '[012]');
});