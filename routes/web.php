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
/*Route::middleware('auth:web')->get('/', function () {
    return view('index');
});
*/
Route::get('/', function () {
    return view('index');
});

//Sync UPR
Route::get('saber_ldap/{item}','SyncController@saberLdap');

//Inertet
Route::get('internet_profes','SyncController@InternetProfesore');
Route::get('internet_est','SyncController@InternetEstudiantes');
Route::get('internet_no_docente','SyncController@InternetNoDocentes');

//Funciones dentro de la UPR
Route::get('no_docente','SyncController@NoDocentes');
Route::get('docente','SyncController@Docentes');
Route::get('estudiantes','SyncController@Estudiantes');
Route::get('adiestrados','SyncController@Adiestrados');
Route::get('todos_users','SyncController@TodosUsuarios');

//RAS
Route::get('ras','SyncController@Ras');
Route::get('docentes_ras','SyncController@DocentesRas');
Route::get('nodocentes_ras','SyncController@NoDocentesRas');

//Kuotas
Route::get('doctor','SyncController@Doctores');
Route::get('master','SyncController@Master');
Route::get('cuadro','SyncController@Cuadro');
Route::get('rector','SyncController@Rector');

//Trabajo de secretaria
Route::get('changePwd//{assamacount}','ChangePwdController@change');
Route::get('habilitar/{assamacount}','ChangePwdController@habilitar');
Route::get('deshabilitar/{assamacount}','ChangePwdController@deshabilitar');

Route::get('update/{employeenumber}','SyncController@update');

//ver fotos
Route::get('usuarios/{samaccountname}', function(){
	return view('Imagen.imagen');
});

//envio de email
Route::get('email/sendEmail/{nombre}/{email}', 'SyncController@SendEmail');


//busqueda
Route::post('busqueda','SyncController@Buscar')->name('busqueda');

//logs
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

//Actualizar Bajas
Route::get('actualizar_bajas_profesores','SyncController@ActualizarBajasProfesores')->name('bajasProfesores');

//Actualizar Alta
Route::get('actualizar_altas_profesores','SyncController@ActualizarAltasProfesores')->name('altasProfesores');

//actualizar UPRedes
Route::get('upredes','SyncController@UPRedes')->name('upredes');

//Reportes
Route::get('ultimos_usuarios_creados','SyncController@UltimosUsuariosCreados')->name('ultimosUsuariosCreados');

//Reportes
Route::get('login','SyncController@Login')->name('Login');

//Swagger
Route::get('doc','SyncController@Doc')->name('Doc');


//reglas propias de laravelauth
Route::get('/home', 'HomeController@index')->name('home');
Auth::routes();

//Reportes
Route::get('grafica_trabajadores','SyncController@GraficaTrabajadores')->name('graficaTrabajadores');