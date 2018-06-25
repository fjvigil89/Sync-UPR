<?php

namespace Sync;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use Collection;
use Log;
use Carbon\Carbon;
class Assets extends Model
{
    //
    
	protected $client;

	function __construct()
	{		# code...
		$this->client = new Client([                              
                'base_uri' => 'http://apiassets.upr.edu.cu/',
                'headers'=>[
                			"Content-Type" => "text/html",
                			"charset" => "utf-8",
                			],
                ]);
	}

	function findEmpleado($idTrabajador)
	{
		try{
			
				$response = $this->client->get("empleados_gras?_format=json&idExpediente=".$idTrabajador);
				$data = collect(json_decode($response->getBody()->getContents(),true));							
				return $data["hydra:member"];
			
		}
		catch(\Exception $e)
        {
            Log::critical("No se puede acceder al empleado del Assets:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            //return response("Alguna cosa esta mal", 500);
            return "Alguna cosa esta mal";
        }
	}

	function findEmpleadoCi($ci)
	{
		try{
				$response = $this->client->get("empleados_gras?_format=json&noCi=".$ci);	
				$data = collect(json_decode($response->getBody()->getContents(),true));					
				return $data["hydra:member"];
				
			
		}
		catch(\Exception $e)
        {
            Log::critical("No se puede acceder al empleado del Assets:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            //return response("Alguna cosa esta mal", 500);
            return "Alguna cosa esta mal";
        }
	}

	function findDepartaento($idCosto)
	{
		try{
			
			$response = $this->client->get("centro_costos/".$idCosto."?_format=json");
			$data = collect(json_decode($response->getBody()->getContents(),true));	
			return trim($data["descCcosto"]);			
		}
		catch(\Exception $e)
        {
            Log::critical("No se puede acceder al Centro de Costo del Assets:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            //return response("Alguna cosa esta mal", 500);
            return "Alguna cosa esta mal";
        }
	}

	function findCargo($idCargo)
	{
		try{
			$response = $this->client->get("rh_cargos/".$idCargo."?_format=json");
			$data = collect(json_decode($response->getBody()->getContents(),true));				

			return trim($data['descCargo']);
		}
		catch(\Exception $e)
        {
            Log::critical("No se puede acceder al cargo del Assets:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            //return response("Alguna cosa esta mal", 500);
            return "Alguna cosa esta mal";
        }
	}

	function findDocente($idTrabajador)
	{
		try{	
				
				$response = $this->client->get("empleados_gras?_format=json&idExpediente=".$idTrabajador."&docente=1");
				$data = collect(json_decode($response->getBody()->getContents(),true));					
				
				if(trim($data["hydra:member"][0]['idExpediente']) == "")
				{	
					return false;
				}			
				return true;			
		}
		catch(\Exception $e)
        {
            Log::critical("No se puede acceder al empleado del Assets:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            //return response("Alguna cosa esta mal", 500);
            return false;
        }
	}



	function findBaja($idTrabajador)//busca rl trabajador y verifica que no sea baja
	{
		try{	
				
				$response = $this->client->get("empleados_gras?_format=json&idExpediente=".$idTrabajador."&baja=1");
				$data = collect(json_decode($response->getBody()->getContents(),true));					
				
				if(trim($data["hydra:member"][0]['idExpediente']) == "")
				{	
					return false;
				}			
				return true;			
		}
		catch(\Exception $e)
        {
            Log::critical("No se puede acceder al empleado del Assets:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            //return response("Alguna cosa esta mal", 500);
            return false;
        }
	}

	function SearchBajaProfesor()//El metodo busca todos lo Trabajadores que fueron baja en el d'ia de hoy
	{
		try{	
			   $date = Carbon::now();
				$response = $this->client->get("/empleados_gras?_format=json&baja=1&fechaBaja=".$date->toDateString());
				$data = collect(json_decode($response->getBody()->getContents(),true));		
				
				if(trim($data["hydra:member"][0]['idExpediente']) == "")
				{	
					return "No Existe";
				}			
				return $data["hydra:member"];			
		}
		catch(\Exception $e)
        {
            Log::critical("No se puede acceder al empleado del Assets:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            //return response("Alguna cosa esta mal", 500);
            return "No Existe";
        }
	}

	function SearchAltasProfesor()//El metodo busca todos lo Trabajadores que fueron Alta en el d'ia de hoy
	{
		try{	
			   $date = Carbon::now();
				$response = $this->client->get("/empleados_gras?_format=json&baja=0&alta=1&fechaContratacion=".$date->toDateString());
				$data = collect(json_decode($response->getBody()->getContents(),true));		
				
				if(trim($data["hydra:member"][0]['idExpediente']) == "")
				{	
					return "No Existe";
				}			
				return $data["hydra:member"];			
		}
		catch(\Exception $e)
        {
            Log::critical("No se puede acceder al empleado del Assets:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            //return response("Alguna cosa esta mal", 500);
            return "No Existe";
        }
	}

	function SaberGrupo($idTrabajador)
    {
    	try{
			$array = Array();

			$response = $this->client->get("empleados_gras?_format=json&idExpediente=".$idTrabajador);
			$data = collect(json_decode($response->getBody()->getContents(),true));			
			
			//Kuotas
			if(trim($data["hydra:member"][0]['idCargo']) == '9387'){ array_push($array, 'UPR-Internet-Rector');}
			if(trim($data["hydra:member"][0]['idCargo']) == '1046'){ array_push($array, 'UPR-Internet-Rector');}
			if(trim($data["hydra:member"][0]['idCargo']) == '1052'){ array_push($array, 'UPR-Internet-Rector');}
			if(trim($data["hydra:member"][0]['idCargo']) == '9385'){ array_push($array, 'UPR-Internet-NoDocente');}
			if(trim($data["hydra:member"][0]['idCargo']) == '9384'){ array_push($array, 'UPR-Internet-NoDocente');}
			if(trim($data["hydra:member"][0]['idCargo']) == '9241'){ array_push($array, 'UPR-Internet-NoDocente');}
			if(trim($data["hydra:member"][0]['idCategoria']) == '5'){ array_push($array, 'UPR-Internet-Cuadros');}
			if(trim($data["hydra:member"][0]['idCategoria']) == '6'){ array_push($array, 'UPR-Internet-Cuadros');}
			if(trim($data["hydra:member"][0]['idCategoria']) == '7'){ array_push($array, 'UPR-Internet-Cuadros');}
			if(trim($data["hydra:member"][0]['idGradoCientifico']) == '09'){ array_push($array, 'UPR-Internet-Master');}
			if(trim($data["hydra:member"][0]['idGradoCientifico']) == '08'){ array_push($array, 'UPR-Internet-Doctores');}


			//para listas
			if(trim($data["hydra:member"][0]['idCcosto']) == '4008'){ array_push($array, 'ProfesoresCRAI');}

			//para Grupos
			if(trim($data["hydra:member"][0]['idCcosto']) == '4016')
				{ 
					//array_push($array, 'Domain Admins');
					array_push($array, 'UPR-Admin-Internet-Kuota');
					array_push($array, 'UPR-Redmine');
					array_push($array, 'UPR-Ids');
					array_push($array, 'UPR-TASK-ADMINISTRATOR');
					array_push($array, 'UPR-TASK-MANAGER');
					array_push($array, 'UPR-Comunidad');
					array_push($array, 'UPR-Upredes');
					array_push($array, 'UPR-OwnCloud');
					array_push($array, 'UPR-Noc');
					array_push($array, 'UPR-Admins-Telefonos');
					array_push($array, 'UPR-Backup-admins');
					array_push($array, 'UPR-OTRS-Admins');
					array_push($array, 'UPR-Admins-Voiceip');
					array_push($array, 'UPRedes');
					array_push($array, 'wifi-admin');
					
				}
			//jubilado reincorporado
			if(trim($data["hydra:member"][0]['idCausabaja']) == '13')
				{ 
					
					array_push($array, 'UPR-Ras');
					
				}
			return $array;
		
			
		}
		catch(\Exception $e)
        {
            Log::critical("No se puede acceder al empleado del Assets:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            //return response("Alguna cosa esta mal", 500);
            return "Alguna cosa esta mal";
        }
    }

}
