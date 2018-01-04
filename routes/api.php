<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Rutas de la API de resorTraffic
Route::resource('reserva', 'ReservaController',['only'=>[
	'index','store','show','update','destroy'
	]]);

Route::resource('regla', 'ReglaController',['only'=>[
	'index','store','show','update','destroy'
	]]);

Route::resource('respuestas_definidas', 'RespuestasDefinidasController',['only'=>[
	'index','store','show','update','destroy'
	]]);

Route::resource('documentos_adjunto', 'DocumentosAdjuntosController',['only'=>[
	'index','store','show','update','destroy'
	]]);

Route::resource('documentos_solicitar', 'DocumentoSolicitarController',['only'=>[
	'index','store','show','update','destroy'
	]]);

Route::resource('requisitos', 'RequisitosController',['only'=>[
	'index','store','show','update','destroy'
	]]);

Route::resource('usuario', 'UsuarioController',['only'=>[
	'index','store','show','update','destroy'
	]]);

Route::resource('departamento', 'DepartamentoController',['only'=>[
	'index','store','show','update','destroy'
	]]);

Route::resource('cuentasCorreo', 'CuentaCorreoController',['only'=>[
	'index','store','show','update','destroy'
	]]);

Route::resource("hoteles","HotelController",['only'=>[
	'index','store','show','update','destroy'
	]]);

Route::resource("area_mensajeria","AreaMensajeriaController",['only'=>[
	'index','store','show','update','destroy'
	]]);

Route::get("informacion/{id}/hoteles","HotelController@hotelall");

Route::get("informacion/{id}/paquete","HotelController@hotelAllPaquete");
Route::get("paquete/{id}/active","PaquetesController@activate");
Route::get("paquete/{req}/actualizar/{id}","PaquetesController@updaterequisito");
Route::get("paquete/{disponible}/actualizarcalendario/{id}","PaquetesController@updatecalendario");

Route::resource("paquetes","PaquetesController",['only'=>[
	'index','store','show','update','destroy'
	]]);

Route::resource('clientes', 'ClienteController',['only'=>[
	'index','store','show','update','destroy'
	]]);

Route::resource('servicio', 'ServicioController',['only'=>[
	'index'
	]]);

Route::resource("marcas","MarcasController",['only'=>[
	'index','store','show','update','destroy'
	]]);

//Otras rutas.........................................................





