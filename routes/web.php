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

/*Route::get('/', function () {
    return view('index');
});*/

Route::get('/','SyncController@saberLdap');

//Route::get('saber_ldap','SyncController@saberLdap');

Route::get('update/{employeenumber}','SyncController@update');

Route::get('{samaccountname}', function(){
	return view('Imagen.imagen');
});

Route::get('email/sendEmail/{nombre}/{email}', 'SyncController@SendEmail');
