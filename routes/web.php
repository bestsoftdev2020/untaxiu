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

Route::get('admin', 'MloginController@login') ;
Route::get('admin/login', 'MloginController@login') ;
Route::get('admin/logout', 'MloginController@logout') ;
Route::get('admin/index', 'MindexController@index') ;
Route::get('admin/lock', 'MloginController@lock') ;
Route::get('admin/profile', 'MloginController@profile') ;
Route::get('admin/changeaccount', 'MloginController@changeaccount') ;
Route::get('admin/changepass', 'MloginController@changepass') ;

Route::get('admin/driverindex', 'MdriverController@index') ;
Route::get('admin/driverapprove', 'MdriverController@approve') ;
Route::get('admin/driversuspend', 'MdriverController@suspend') ;
Route::get('admin/driverdelete', 'MdriverController@delete') ;

Route::get('admin/customerindex', 'McustomerController@index') ;
// Route::get('admin/customerapprove', 'McustomerController@approve') ;
// Route::get('admin/customersuspend', 'McustomerController@suspend') ;
Route::get('admin/customerdelete', 'McustomerController@delete') ;

Route::get('admin/configindex', 'MconfigController@index') ;
Route::get('admin/changepayment', 'MconfigController@changepayment') ;
Route::get('admin/changegoogle', 'MconfigController@changegoogle') ;

Route::get('admin/tripsindex', 'MtripsController@index') ;

Route::get('admin/withdrawindex', 'MwithdrawController@index') ;
Route::get('admin/withdrawsend', 'MwithdrawController@send') ;
Route::get('admin/withdrawcancel', 'MwithdrawController@cancel') ;

Route::get('admin/carsindex', 'McarsController@index') ;
Route::get('admin/carssave', 'McarsController@save') ;
Route::get('admin/carsdelete', 'McarsController@delete') ;
Route::get('admin/carsdefault', 'McarsController@default') ;