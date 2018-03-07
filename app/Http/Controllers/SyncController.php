<?php

namespace Sync\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Sync\ldap;
use Sync\Assets;
use Log;
use Carbon\Carbon;
use Collection;
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
    	$time_start = microtime(true);
	    	 $array_NoUpdate= array();
	    	 $array_Update= array();
	    	 $ldap = new ldap();
	    	 $assets = new Assets;	    	 
	    	 $NoSync = "OU=No Sync,OU=_Usuarios,DC=upr,DC=edu,DC=cu";
	    	 $Docente = "OU=Trabajador Docente,OU=_Usuarios,DC=upr,DC=edu,DC=cu";
	    	 $NoDocente= "OU=Trabajador NoDocente,OU=_Usuarios,DC=upr,DC=edu,DC=cu";
	    	 $bajas = "OU=Bajas,OU=_Usuarios,DC=upr,DC=edu,DC=cu";
	    	 $lista_ldap = $ldap->saberLdap(); 
	    	 
	    	 $group= array();
	    	 //dd($lista_ldap);
	    	 	for ($i=0; $i < count($lista_ldap)-1 ; $i++) { 
		    	 	try{		    	 		
		    	 	 		
		    	 			$empleado = $assets->findEmpleado($lista_ldap[$i]["employeenumber"][0]);
		    	 			
		    	 	 		if ($empleado == "No Existe") {
		    	 	 			array_push($array_NoUpdate, $lista_ldap[$i]);
		    	 	 			break;
		    	 	 		}

		    	 	 		if ($empleado == "" || $empleado == "Alguna cosa esta mal") {
		    	 	 			//$ldap->mover($lista_ldap[$i]['dn'], $NoSync);	
		    	 	 			Log::critical(Carbon::now()." No se puede actualizar al empleado ".$lista_ldap[$i]["displayname"][0]." por no estar en assets:");
			  		 			array_push($array_NoUpdate, $lista_ldap[$i]);
			  		 			
			  		 			break;
		    	 	 		}

		    	 	 		
		    	 	 		$TrabBaja = $assets->findBaja($lista_ldap[$i]["employeenumber"][0]);
		    	 	 		if ($TrabBaja) {		    	 	 			
		    	 	 			$ldap->mover($lista_ldap[$i]['dn'], $bajas);	

		    	 	 			Log::critical(Carbon::now()." No se puede actualizar al empleado ".$lista_ldap[$i]["displayname"][0]." por no tener departamento en assets:");
			  		 			array_push($array_NoUpdate, $lista_ldap[$i]);
			  		 			
			  		 			//break;
		    	 	 		}
		    	 	 		else{

			    	 	 		$departamento = $assets->findDepartaento(trim($empleado[0]["idCcosto"]));
			    	 	 		if ($departamento == "" || $departamento == "Alguna cosa esta mal") {
			    	 	 			
			    	 	 			//$ldap->mover($lista_ldap[$i]['dn'], $NoSync);	


			    	 	 			Log::critical(Carbon::now()." No se puede actualizar al empleado ".$lista_ldap[$i]["displayname"][0]." por no tener departamento en assets:");
				  		 			array_push($array_NoUpdate, $lista_ldap[$i]);
				  		 			
				  		 			//break;
			    	 	 		}

			    	 	 		
			    	 	 		$cargo = $assets->findCargo(trim($empleado[0]["idCargo"]));

			    	 	 		if ($cargo == "" || $cargo == "Alguna cosa esta mal") {
			    	 	 			
			    	 	 			//$ldap->mover($lista_ldap[$i]['dn'], $NoSync);	
			    	 	 			Log::critical(Carbon::now()." No se puede actualizar al empleado ".$lista_ldap[$i]["displayname"][0]." por no tener cargo en assets:");
				  		 			array_push($array_NoUpdate, $lista_ldap[$i]);
				  		 			

				  		 			//break;
			    	 	 		}
			    	 	 		
							 	if(!$ldap->ActualizarCamposIdEmpleado($empleado[0], $departamento, $cargo, $lista_ldap[$i]['samaccountname'][0]))
							 	{
							 		array_push($array_NoUpdate, $lista_ldap[$i]);
							 		//$ldap->mover($lista_ldap[$i]['dn'], $NoSync);	
							 		//return false;
							 	}
							 	else{

							 		array_push($array_Update, $lista_ldap[$i]);
							 	}

							 	
							 	
							 	$profes = $assets->findDocente(trim($lista_ldap[$i]["employeenumber"][0]));
							 	
			    	 	 		if (!$profes) {
			    	 	 			
			    	 	 			$group= ['UPR-Wifi'];
			    	 	 			$ldap->mover($lista_ldap[$i]['dn'], $NoDocente);	    	 	 			
			    	 	 		}
			    	 	 		if($profes){
			    	 	 			$group= ['UPR-Wifi'];	
			    	 	 			$ldap->mover($lista_ldap[$i]['dn'], $Docente);	    	 	 			
			    	 	 			//$ldap->addtogroup($lista_ldap[$i]['samaccountname'], $group);
			    	 	 		}
		    	 	 		}

						 $ldap->mover($lista_ldap[$i]['dn'], "OU=Actualizar,OU=_Usuarios,DC=upr,DC=edu,DC=cu");		
						 	
						
		    	 	 }
		    	 	catch(\Exception $e)
	        		{
	        			$ldap->mover($lista_ldap[$i]['dn'], $NoSync);		        			
	            		Log::critical(Carbon::now()." No se puede actualizar al empleado ".$lista_ldap[$i]["displayname"][0]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
			  		 	array_push($array_NoUpdate, $lista_ldap[$i]);
			  		 	//return false;
				  		
			  		}

		    	} 
		    	
		$time_end = microtime(true);
		$time_total = $time_end - $time_start;

		//array_push($array_NoUpdate, $time_total); 
		//return JsonResponse::create($lista_ldap, 200, array('Content-Type'=>'application/json; charset=utf-8' ));		

		return view('update',[
			'array'=>$array_NoUpdate,
			'arrayProcesados'=>$array_Update, 
			'time' => $time_total,
			'noUpdate' => count($array_NoUpdate),
			'update' => count($array_Update),
			'total' => count($lista_ldap)-1,
		]);
    	
    }

    function thumbnailphoto($samaccountname)
    {   	

    	 $ldap = new ldap();
    	 return $ldap->thumbnailphoto($samaccountname);     	 

    }

    public function update($employeenumber) 
    {
    	dd($employeenumber);
    	 //dentro de un for con la cantidad de la lista_ldap
		    	//for ($i=0; $i < count($lista_ldap)-1 ; $i++) { 
		    	 	try{
		    	 		
		    	 	 		//$empleado = $assets->findEmpleadoCi($lista_ldap[$i]["employeeid"][0]);
		    	 			//$empleado = $assets->findEmpleado($lista_ldap[$i]["employeenumber"][0]);
		    	 			$empleado = $assets->findEmpleado(trim($employeenumber));

		    	 			/*
		    	 	 		if ($empleado == "No Existe") {
		    	 	 			array_push($array_NoUpdate, $lista_ldap[$i]["displayname"][0]);
		    	 	 			//break;
		    	 	 		}*/

		    	 	 		$departamento = $assets->findDepartaento(trim($empleado[0]["idCcosto"]));    	 	 	

		    	 	 		$cargo = $assets->findCargo(trim($empleado[0]["idCargo"]));

						 

						 	if(!$ldap->ActualizarCamposIdEmpleado($empleado[0], $departamento, $cargo, $lista_ldap[$i]['samaccountname'][0]))
						 	{
						 		//array_push($array_NoUpdate, $lista_ldap[$i]["displayname"][0]);
						 		return false;
						 	}
						 	
						 	return true;
						
		    	 	 }
		    	 	catch(\Exception $e)
	        		{
	        			
	            		Log::critical(Carbon::now()." No se puede actualizar al empleado ".$employeenumber." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
			  		 	//array_push($array_NoUpdate, $lista_ldap[$i]["displayname"][0]);
			  		 	return false;
				  		
			  		}

		    	//}
    }

   
}
