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
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home/{company}', 'HomeController@showCompany');

// you left off on trying to get the url sent through axios request so you can get the company you're viewing!
Route::get('/action-log/{url}', 'ActionLogController@index');
Route::post('/action-log', 'ActionLogController@store');

Route::get('/archived', 'ActionLogController@archived');
Route::get('/archived/{company}', 'ActionLogController@showArchive');
Route::post('/archive', 'ActionLogController@archive');

Route::get('/deleted', 'ActionLogController@deleted');
Route::get('/deleted/{company}', 'ActionLogController@showDeleted');
Route::post('/delete', 'ActionLogController@softDelete');

Route::get('/user-directory', 'UserDirectory@index');

Route::get('/add-user', 'UserController@create');
Route::post('/add-user', 'UserController@store');

Route::get('/manage-users', 'UserController@edit');
Route::post('/manage-users', 'UserController@update');

Route::get('/add-company', 'CompanyController@create');
Route::post('/add-company', 'CompanyController@store');

Route::get('/manage-companies', 'CompanyController@edit');
Route::post('/manage-companies', 'CompanyController@update');

Route::post('/upload-product-file', 'ProductDocumentController@store');
Route::post('/removeProductDocument', 'ProductDocumentController@destroy');

Route::post('/upload-data-file', 'TestDataController@store');
Route::post('/removeTestDocument', 'TestDataController@destroy');

Route::post('/upload-data', 'FormController@dataUpload');

Route::post('/assign-to', 'FormController@assignTo');

//Route::post('/upload-file', ['as' => 'upload-file.post', 'uses' => 'FormController@fileUpload']);

Route::post('/current-status', 'CurrentStatusController@store');

Route::get('/first-time-login', 'FirstTimeLogin@index')->name('first-time-login');
Route::post('/first-time-login', 'FirstTimeLogin@store');

//For Vue development only
Route::get('/vue', 'VueController@index');