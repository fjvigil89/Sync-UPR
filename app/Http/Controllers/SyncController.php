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
	    	 
	    	 	for ($i=0; $i < count($lista_ldap)-1 ; $i++) { 
		    	 	try{		    	 		
		    	 	 		
		    	 			$empleado = $assets->findEmpleado(trim(ltrim($lista_ldap[$i]["employeenumber"][0])));
		    	 			
		    	 	 		if ($empleado == "No Existe") {
		    	 	 			array_push($array_NoUpdate, $lista_ldap[$i]);
		    	 	 			break;
		    	 	 		}

		    	 	 		if ($empleado == "" || $empleado == "Alguna cosa esta mal") {
		    	 	 			//$ldap->mover($lista_ldap[$i]['dn'], $NoSync);	

		    	 	 			Log::critical(Carbon::now()." No se puede actualizar al empleado ".$lista_ldap[$i]["displayname"][0]." por no estar en assets:");
			  		 			array_push($array_NoUpdate, $lista_ldap[$i]);			  		 			
		    	 	 		}

		    	 	 		
		    	 	 		$TrabBaja = $assets->findBaja($lista_ldap[$i]["employeenumber"][0]);	 		
		    	 	 		if ($TrabBaja) {
		    	 	 			$ldap->mover($lista_ldap[$i]['dn'], $bajas);	
		    	 	 			$ldap->Disable($lista_ldap[$i]['samaccountname'][0]);

		    	 	 			Log::critical(Carbon::now()." No se puede actualizar al empleado ".$lista_ldap[$i]["displayname"][0]." por ser baja del assets:");
			  		 			array_push($array_NoUpdate, $lista_ldap[$i]);
			  		 			
		    	 	 		}
		    	 	 		else{
		    	 	 			
			    	 	 		$departamento = $assets->findDepartaento(trim(ltrim($empleado[0]["idCcosto"])));
			    	 	 		if ($departamento == "" || $departamento == "Alguna cosa esta mal") {
			    	 	 			
			    	 	 			//$ldap->mover($lista_ldap[$i]['dn'], $NoSync);	

			    	 	 			//$this->SendEmail($lista_ldap[$i]['displayname'][0],$lista_ldap[$i]['samaccountname'][0]);


			    	 	 			Log::critical(Carbon::now()." No se puede actualizar al empleado ".$lista_ldap[$i]["displayname"][0]." por no tener departamento en assets:");
				  		 			array_push($array_NoUpdate, $lista_ldap[$i]);			  		 			
			    	 	 		}

			    	 	 		
			    	 	 		$cargo = $assets->findCargo(trim(ltrim($empleado[0]["idCargo"])));
			    	 	 		if ($cargo == "" || $cargo == "Alguna cosa esta mal") {
			    	 	 			
			    	 	 			//$ldap->mover($lista_ldap[$i]['dn'], $NoSync);	

			    	 	 			//$this->SendEmail($lista_ldap[$i]['displayname'][0],$lista_ldap[$i]['samaccountname'][0]);

			    	 	 			Log::critical(Carbon::now()." No se puede actualizar al empleado ".$lista_ldap[$i]["displayname"][0]." por no tener cargo en assets:");
				  		 			array_push($array_NoUpdate, $lista_ldap[$i]);
				  		 			
			    	 	 		}
			    	 	 		
							 	if(!$ldap->ActualizarCamposIdEmpleado($empleado[0], $departamento, $cargo, $lista_ldap[$i]['samaccountname'][0]))
							 	{
							 		array_push($array_NoUpdate, $lista_ldap[$i]);
							 		//$ldap->mover($lista_ldap[$i]['dn'], $NoSync);	
							 		//$this->SendEmail($lista_ldap[$i]['displayname'][0],$lista_ldap[$i]['samaccountname'][0]);							 		
							 	}
							 	else{

							 		array_push($array_Update, $lista_ldap[$i]);
							 	}

							 	
							 	
							 	$profes = $assets->findDocente(trim($lista_ldap[$i]["employeenumber"][0]));
							 	
			    	 	 		if (!$profes) {
			    	 	 			
			    	 	 			$group= ['UPR-Wifi'];
			    	 	 			//$ldap->mover($lista_ldap[$i]['dn'], $NoDocente);	    	 	 			
			    	 	 		}
			    	 	 		if($profes){
			    	 	 			$group= ['UPR-Wifi'];	
			    	 	 			//$ldap->addtogroup($lista_ldap[$i]['samaccountname'], $group);


			    	 	 			//$ldap->mover($lista_ldap[$i]['dn'], $Docente);

			    	 	 			
			    	 	 		}
		    	 	 		}

						 $ldap->mover($lista_ldap[$i]['dn'], "OU=Actualizar,OU=_Usuarios,DC=upr,DC=edu,DC=cu");		
						
		    	 	 }
		    	 	catch(\Exception $e)
	        		{
	        			//$this->SendEmail($lista_ldap[$i]['displayname'][0],$lista_ldap[$i]['samaccountname'][0]);
	        			//$ldap->mover($lista_ldap[$i]['dn'], $NoSync);		        			
	            		Log::critical(Carbon::now()." No se puede actualizar al empleado ".$lista_ldap[$i]["displayname"][0]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
			  		 	array_push($array_NoUpdate, $lista_ldap[$i]);
			  		 	
				  		
			  		}

		    	} 
		    	
		$time_end = microtime(true);
		$time_total = $time_end - $time_start;

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

    function SendEmail($nombre, $email)
    {

    	$data = array(
			'name' => $nombre,
			'email' => $email
		);	

		
	
		Mail::send('Email.notification', $data, function ($message) use ($data) { 
		    $message->from('frank.vigil@upr.edu.cu', 'UPRedes');
		    //$message->sender('john@johndoe.com', 'John Doe');
			
		    $message->to($data['email'].'@upr.edu.cu', $data['name']);
		
		    //$message->cc('john@johndoe.com', 'John Doe');
		    //$message->bcc('john@johndoe.com', 'John Doe');
		
		    //$message->replyTo('john@johndoe.com', 'John Doe');
		
		    $message->subject('Sincronizador Automático de la UPR');
		
		    //$message->priority(3);
		
		    //$message->attach('pathToFile');
		});
    }
   
}
