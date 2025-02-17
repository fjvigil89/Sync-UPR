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

//saber Ccosto
Route::get('/trabajadores/{idCcosto}', 'ApiController@SearchbyCcosto');

//saber fotos
Route::get('/user/{samaccountname}', 'ApiController@thumbnailphoto');

//saber login
Route::get('apilogin/{username}/{password}/{attrib}', 'ApiController@AuthLdap');




