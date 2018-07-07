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

class TrabajadoresController extends Controller
{
  

    function NuevoTrabajadores()
    {
    	return view('Users.nuevos');
    }
    function CrearTrabajador(Request $request)
    {
    	 $ldap = new ldap();
   		$assets = new Assets();

   		if ($assets->ExisteEmpleado($request->employeeid)) 
   		{
   			if(!$ldap->ExisteEmpleado($request->employeeid))
   			{
   				$ldap->CrearUsuario($assets->findEmpleado($request->employeeid));
   			}
   			return 'El Usuario existe en la Universidad';
   		}

   		return "El Trabajador no pertenece a la Universidad";
    	
    }
}
