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
Route::resource('reserva', 'ReservaController');

Route::resource('regla', 'ReglaController');

Route::resource('respuestas_definidas', 'RespuestasDefinidasController');

Route::resource('documentos_adjunto', 'DocumentosAdjuntosController');

Route::resource('documentos_solicitar', 'DocumentoSolicitarController');

Route::resource('requisitos', 'RequisitosController');

Route::resource('usuario', 'UsuarioController');

Route::resource("hoteles","HotelController");

Route::resource("paquetes","PaquetesController");

Route::resource('clientes', 'ClienteController');

Route::resource('sistema', 'SistemaController');



