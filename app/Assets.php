<?php

namespace Sync;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use Collection;
use Log;
class Assets extends Model
{
    //
    
	protected $client;

	function __construct()
	{		# code...
		$this->client = new Client([                              
                'base_uri' => 'http://apiassets.upr.edu.cu/',
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
			$response = $this->client->get("areas_responsabilidads?_format=json&idCcosto=".$idCosto);
			$data = collect(json_decode($response->getBody()->getContents(),true));	

			if(trim($data["hydra:member"][0]['descArearesponsabilidad']) == "")
				{
					$response = $this->client->get("centro_costos/".$idCosto."?_format=json");
					$data = collect(json_decode($response->getBody()->getContents(),true));	
					
					return trim($data["descCcosto"]);
				}

			return trim($data["hydra:member"][0]['descArearesponsabilidad']);
		}
		catch(\Exception $e)
        {
            Log::critical("No se puede acceder al área de responzabilidad del Assets:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
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

}
