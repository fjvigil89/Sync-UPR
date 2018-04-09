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
    return view('index');
});

//Sync UPR
Route::get('saber_ldap','SyncController@saberLdap');

//Inertet
Route::get('internet_profes','SyncController@InternetProfesore');
Route::get('internet_est','SyncController@InternetEstudiantes');
Route::get('internet_no_docente','SyncController@InternetNoDocentes');

//Funcione
Route::get('no_docente','SyncController@NoDocentes');
Route::get('docente','SyncController@Docentes');
Route::get('estudiantes','SyncController@Estudiantes');

//RAS
Route::get('ras','SyncController@Ras');

//Kuotas
Route::get('doctor','SyncController@Doctores');
Route::get('master','SyncController@Master');
Route::get('cuadro','SyncController@Cuadro');
Route::get('rector','SyncController@Rector');


//Route::get('saber_ldap','SyncController@saberLdap');

Route::get('update/{employeenumber}','SyncController@update');

Route::get('{samaccountname}', function(){
	return view('Imagen.imagen');
});

Route::get('email/sendEmail/{nombre}/{email}', 'SyncController@SendEmail');
