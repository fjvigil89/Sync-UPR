<?php

namespace Sync\Http\Controllers;

use Illuminate\Http\Request;
use Sync\ldap;
use Sync\Assets;
use Log;
use Carbon\Carbon;
class SyncController extends Controller
{
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saberLdap(Request $request)
    {
    	 $array_NoUpdate= array();
    	 $ldap = new ldap();
    	 $assets = new Assets;

    	 $lista_ldap = $ldap->saberLdap();    	    	 
    	 
    	 
	    	 //dentro de un for con la cantidad de la lista_ldap
	    	 for ($i=0; $i < count($lista_ldap)-1 ; $i++) { 
	    	 	try{
	    	 		
	    	 	 		//$empleado = $assets->findEmpleadoCi($lista_ldap[$i]["employeeid"][0]);
	    	 			$empleado = $assets->findEmpleado($lista_ldap[$i]["employeenumber"][0]);

	    	 	 		if ($empleado == "No Existe") {
	    	 	 			array_push($array_NoUpdate, $lista_ldap[$i]["displayname"][0]);
	    	 	 			break;
	    	 	 		}

	    	 	 		$departamento = $assets->findDepartaento(trim($empleado[0]["idCcosto"]));    	 	 	
					 

					 	if(!$ldap->ActualizarCamposIdEmpleado($empleado[0], $departamento))
					 	{
					 		array_push($array_NoUpdate, $lista_ldap[$i]["displayname"][0]);
					 	}
					
	    	 	 }
	    	  	catch(\Exception $e)
        		{
        			
            		Log::critical(Carbon::now()." No se puede actualizar al empleado ".$lista_ldap[$i]["displayname"][0]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
		  		 	array_push($array_NoUpdate, $lista_ldap[$i]["displayname"][0]);
			  		
		  		}

	    	}
	    		 
		 dd($array_NoUpdate);
		
	    
    	
    }

    function thumbnailphoto($samaccountname)
    {   	

    	 $ldap = new ldap();
    	 return $ldap->thumbnailphoto($samaccountname);     	 

    }

   
}
