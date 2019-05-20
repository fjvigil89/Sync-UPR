<?php

namespace Sync\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Sync\ldap;
use Sync\Assets;
use Log;
use Carbon\Carbon;
use Collection;
use Mail;
use Intervention\Image\Facades\Image;

/**
 * Class ApiController
 *
 * @package Sync\Http\Controllers
 */
class ApiController extends Controller
{
    //
    /**
     * @SWG\Get(path="/api/apilogin/{username}/{password}/{attrib}",
     *   tags={"login"},
     *   summary="Este metodo permite que los usuarios se autentiquen",
     *   description="",
     *   operationId="login",
     *   produces={"application/json", "application/xml"},
     *     @SWG\Parameter(
     *         description="nombre de usuario",     
     *         in="path",
     *         name="username",
     *         required=true,
     *         type="string"
     *     ),
     *      @SWG\Parameter(
     *         description="password del usuario",     
     *         in="path",
     *         name="password",
     *         required=true,
     *         type="string"
     *     ),    
     *      @SWG\Parameter(
     *         description="arreglo separade por coma de parametros",     
     *         in="path",
     *         name="attrib",
     *         required=false,
     *         type="string"
     *     ),    
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",     
     *   ),
     *   @SWG\Response(response=400, description="Invalid username/password supplied")
     * )
    */
    function AuthLdap($username, $password, $attrib)
    {
    	$ldap= new Ldap();
    	$login =$ldap->Auth($username, $password);        
    	if ($login) {

    		Log::alert(" Se logueo el usuario". $username ." de la UPR ");

    		$attrib = explode(',', $attrib);    		
    	  	$data= $ldap->Info($username, $password, $attrib);       	  	
    	  	return JsonResponse::create($data, 200, array('Content-Type'=>'application/json; charset=utf-8' ));
          	

       }
       Log::alert(" Fallo al loguear el usuario". $username.'con el pass'.$password ." de la UPR ");
       return response()->json($login,404);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Get(
     *     path="/api/user/{samaccountname}",
     *     description="Retorna la foto del Usuario de la UPR",
     *     operationId="api.thumbnailphoto",
     *     produces={"application/json"},
     *     tags={"thumbnailphoto"},
	 *     @SWG\Parameter(
	 *     name="samaccountname",
	 *     in="path",
	 *     description="Target customer.",
	 *     required=true,
	 *     type="string"
	 *   ),
     *     @SWG\Response(
     *         response=200,
     *         description="Dashboard overview."
     *     ),
     *     @SWG\Response(
     *         response=401,
     *         description="Unauthorized action.",
     *     )
     * )
     */
    function thumbnailphoto($samaccountname)
    {   	

    	 $ldap = new ldap();
    	 return $ldap->thumbnailphoto($samaccountname);     	 
         
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Get(
     *     path="/api/trabajadores/{idCcosto}",
     *     description="Retorna los Trabajadores de un Centro de Costo de la UPR",
     *     operationId="api.idCcosto",
     *     produces={"application/json"},
     *     tags={"Saber usuarios por Centro de Costo"},
     *     @SWG\Parameter(
     *     name="idCcosto",
     *     in="path",
     *     description="Target customer.",
     *     required=true,
     *     type="string"
     *   ),
     *     @SWG\Response(
     *         response=200,
     *         description="Dashboard overview."
     *     ),
     *     @SWG\Response(
     *         response=401,
     *         description="Unauthorized action.",
     *     )
     * )
     */
    function SearchbyCcosto($idCcosto)
    {
      $assets = new Assets();      
      $data = $assets->searchbyCcosto($idCcosto);
      if ($data == "No Existe") {
          # code...
      }
            
      //return JsonResponse::create($data, 200, array('Content-Type'=>'application/json; charset=utf-8' ));
      return $data;
    }
}
