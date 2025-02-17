<?php

namespace Sync;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use Collection;
use Log;
use Carbon\Carbon;
class Sigenu extends Model
{
    //
    protected $client;

	function __construct()
	{		# code...
		$this->client = new Client([                              
                'base_uri' => 'http://apisigenu.upr.edu.cu/api/',
                'headers'=>[
                			"Content-Type" => "text/html",
                			"charset" => "utf-8",
                			],
                ]);
	}

	function findCiStudent($ciEstudiante)
	{
		try{			

			$response = $this->client->get("student?identification=eq.".$ciEstudiante);			
			$data = collect(json_decode($response->getBody()->getContents(),true));
			return $data[0];
			
		}
		catch(\Exception $e)
	    {
	        Log::critical("No se puede acceder al estudiante del Sigenu:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
	        //return response("Alguna cosa esta mal", 500);
	        return "Alguna cosa esta mal";
	    }
	}
	function findIdStudent($idEstudiante)
	{
		try{		

			$response = $this->client->get("student?id_student=eq.".$idEstudiante);			
			$data = collect(json_decode($response->getBody()->getContents(),true));		
			return $data[0];
			
		}
		catch(\Exception $e)
	    {	        
	        //return response("Alguna cosa esta mal", 500);
	        if($e->getCode() >= 300)
	        	return "500";
	        else{
	        	Log::critical("No se puede acceder al estudiante del Sigenu:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
	        	return "Alguna cosa esta mal";	
	        }
	        
	    }
	}

	function ExisteStudent($idEstudiante)
	{
		try{			
			$response = $this->client->get("student?id_student=eq.".$idEstudiante);			
			$data = collect(json_decode($response->getBody()->getContents(),true));			
			return true;			

		}
		catch(\Exception $e)
	    {
	        Log::critical("No se puede acceder al estudiante del Sigenu:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
	        //return response("Alguna cosa esta mal", 500);
	        return false;
	    }
	}

	//saber si existe un estudiante pero que este activo en la UPR
	function ExisteStudent_CI($CIEstudiante)
	{
		try{			
			$response = $this->client->get("student?identification=eq.".$CIEstudiante);			
			$data = collect(json_decode($response->getBody()->getContents(),true));

			$status=false;			
			if (count($data) > 0 ) {
				foreach ($data as $value) {					
					if ($value['student_status_fk'] !=01 && $value['student_status_fk']!=05) {
				
						$status = true;
					}
				}
				
			}
				
			return $status;			

		}
		catch(\Exception $e)
	    {
	        Log::critical("No se puede acceder al estudiante del Sigenu:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
	        //return response("Alguna cosa esta mal", 500);
	        return false;
	    }
	}

	function SaberGrupoStudent($ciEstudiante)
    {
    	try{
			$array = Array();
			
			return $array;		
			
		}
		catch(\Exception $e)
        {
            Log::critical("No se puede acceder al Estudiante del Sigenu:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            //return response("Alguna cosa esta mal", 500);
            return "Alguna cosa esta mal";
        }
    }

    function findBajaStudent($idEstudiante)
    {
    	try{
			 $response = $this->client->get("student?id_student=eq.".$idEstudiante);			
			$data = collect(json_decode($response->getBody()->getContents(),true));
			//$data[0]['student_status_fk']==01    Baja	
			//$data[0]['student_status_fk']==02    Activo			
			//$data[0]['student_status_fk']==03    Prórroga de Tesis	
			//$data[0]['student_status_fk']==04    Matricula Pasiva
			//$data[0]['student_status_fk']==05    Egresado			
			if ($data[0]['student_status_fk']==01) {
				# code...
				return true;
			}			
			return false;			
		}
		catch(\Exception $e)
        {
            Log::critical("No se puede acceder al Estudiante del Sigenu:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            //return response("Alguna cosa esta mal", 500);
            return false;
        }
    }

    function findEgresadoStudent($idEstudiante)
    {
    	try{
			 $response = $this->client->get("student?id_student=eq.".$idEstudiante);			
			$data = collect(json_decode($response->getBody()->getContents(),true));
			//$data[0]['student_status_fk']==01    Baja	
			//$data[0]['student_status_fk']==02    Activo			
			//$data[0]['student_status_fk']==03    Prórroga de Tesis	
			//$data[0]['student_status_fk']==04    Matricula Pasiva
			//$data[0]['student_status_fk']==05    Egresado			
			if ($data[0]['student_status_fk']==05) {
				# code...
				return true;
			}			
			return false;			
		}
		catch(\Exception $e)
        {
            Log::critical("No se puede acceder al Estudiante del Sigenu:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            //return response("Alguna cosa esta mal", 500);
            return false;
        }
    }

    function findfacultad($idEstudiante)
    {
    	try{			
			$response = $this->client->get("student?id_student=eq.".$idEstudiante."&select=*,faculty(*)");			
			$data = collect(json_decode($response->getBody()->getContents(),true));			
			return $data[0]['faculty']['name'];
			
		}
		catch(\Exception $e)
	    {
	        Log::critical("No se puede acceder al estudiante del Sigenu:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
	        //return response("Alguna cosa esta mal", 500);
	        return "Alguna cosa esta mal";
	    }
    }

    function findfacultad_fx($idfaculty)
    {
    	try{			
			$response = $this->client->get("faculty?id_faculty=eq.".$idfaculty);			
			$data = collect(json_decode($response->getBody()->getContents(),true));	
				
			return $data[0]['name'];
			
		}
		catch(\Exception $e)
	    {
	        Log::critical("No se puede acceder al estudiante del Sigenu:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
	        //return response("Alguna cosa esta mal", 500);
	        return "Alguna cosa esta mal";
	    }
    }
    function findCarrera($idEstudiante)
    {
    	try{			
			$response = $this->client->get("student?id_student=eq.".$idEstudiante."&select=*,career(*)");			
			$carrera_id = collect(json_decode($response->getBody()->getContents(),true))[0]['career']['national_career_fk'];	

			$response1 = $this->client->get("national_career?id_national_career=eq.".$carrera_id);
			$data = collect(json_decode($response1->getBody()->getContents(),true));				
			
			return $data[0]['name'];
			
		}
		catch(\Exception $e)
	    {
	        Log::critical("No se puede acceder al estudiante del Sigenu:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
	        //return response("Alguna cosa esta mal", 500);
	        return "Alguna cosa esta mal";
	    }
    }

    function findCarrera_fx($idcareer)
    {
    	try{			
			$response = $this->client->get("career?id_career=eq.".$idcareer);						
			$carrera_id = collect(json_decode($response->getBody()->getContents(),true))[0]['national_career_fk'];	

			$response1 = $this->client->get("national_career?id_national_career=eq.".$carrera_id);
			$data = collect(json_decode($response1->getBody()->getContents(),true));				
			return $data[0]['name'];
			
		}
		catch(\Exception $e)
	    {
	        Log::critical("No se puede acceder al estudiante del Sigenu:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
	        //return response("Alguna cosa esta mal", 500);
	        return "Alguna cosa esta mal";
	    }
    }

    function findCursoTipo($idEstudiante)
    {
    	try{			
			$response = $this->client->get("student?id_student=eq.".$idEstudiante."&select=*,course_type(*)");			
			$data = collect(json_decode($response->getBody()->getContents(),true));				
			return $data[0]['course_type']['short_name'];
			
		}
		catch(\Exception $e)
	    {
	        Log::critical("No se puede acceder al estudiante del Sigenu:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
	        //return response("Alguna cosa esta mal", 500);
	        return "Alguna cosa esta mal";
	    }
    }

    function findCursoTipo_fx($idcurso_type)
    {
    	try{			
			$response = $this->client->get("course_type?id_course_type=eq.".$idcurso_type);			
			$data = collect(json_decode($response->getBody()->getContents(),true));		
			return $data[0]['short_name'];
			
		}
		catch(\Exception $e)
	    {
	        Log::critical("No se puede acceder al estudiante del Sigenu:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
	        //return response("Alguna cosa esta mal", 500);
	        return "Alguna cosa esta mal";
	    }
    }

    function findAnno($idEstudiante)
    {
    	try{			
			$response = $this->client->get("groups2students?students_fk=eq.".$idEstudiante."&select=*,groups(*)");			
			$data = collect(json_decode($response->getBody()->getContents(),true));			
			$anno = 0;
			if ($data[0]['groups']['name'] == 1 || ( $data[0]['groups']['name'] >= 100 && $data[0]['groups']['name'] < 200  )) {
				# code...
				$anno = 1;
			}
			if ($data[0]['groups']['name'] == 2 || ($data[0]['groups']['name'] >= 200 && $data[0]['groups']['name'] < 300)) {
				# code...
				$anno = 2;
			}
			if ($data[0]['groups']['name'] == 3 || ($data[0]['groups']['name'] >= 300 && $data[0]['groups']['name'] < 400)) {
				# code...
				$anno = 3;
			}
			if ($data[0]['groups']['name'] == 4 || ($data[0]['groups']['name'] >= 400 && $data[0]['groups']['name'] < 500)) {
				# code...
				$anno = 4;
			}
			if ($data[0]['groups']['name'] == 5 || ($data[0]['groups']['name'] >= 500 && $data[0]['groups']['name'] < 600)) {
				# code...
				$anno = 5;
			}
			if ($data[0]['groups']['name'] == 6 || ($data[0]['groups']['name'] >= 600 && $data[0]['groups']['name'] < 700)) {
				# code...
				$anno = 6;
			}
			return $anno;
			
		}
		catch(\Exception $e)
	    {
	        Log::critical("No se puede acceder al estudiante del Sigenu:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
	        //return response("Alguna cosa esta mal", 500);
	        return "Alguna cosa esta mal";
	    }
    }

    function findNewStudent()
    {
    	try{			
    		$date = Carbon::now();
			$response = $this->client->get("http://apisigenu.upr.edu.cu/api/matriculated_student?inscription_date=eq.".$date->toDateString());			
			$data = collect(json_decode($response->getBody()->getContents(),true));		
			
			return $data;
			
		}
		catch(\Exception $e)
	    {
	        Log::critical("No se puede acceder al estudiante del Sigenu:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
	        //return response("Alguna cosa esta mal", 500);
	        return "Alguna cosa esta mal";
	    }
    }
}
