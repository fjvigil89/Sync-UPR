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
class GraficaController extends Controller
{
    //
    function GraficaTrabajadores()
    {
      $data = [
          "labels" => ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes','Sábado','Domingo'],
          "series" => [ [5, 2, 4, 2, 10, 15, 10]]
      ];
      return $data;

    }

    function GraficaTotalUsuariosPorYipo()
    {
      $ldap = new ldap();
      $docente =$ldap->Docentes();
      $estud =$ldap->Estudiante();
      $NoDocente =$ldap->NoDocentes();

      $data = [                    
          "labels"=> ['Docentes', 'No Docentes', 'Estudiantes'],
		  "series"=> [$docente['count'], $NoDocente['count'], $estud['count']]
      ];
      return $data;
    }
    function GraficausUariosPorUnidadesOrganizativas(){
      $ldap = new ldap();
      $docente =$ldap->Docentes();
      $estud =$ldap->Estudiante();
      $NoDocente =$ldap->NoDocentes();
      
    	$data = [                    
          "labels"=> ['Docentes', 'No Docentes', 'Estudiantes'],
		  "series"=> [$docente['count'], $NoDocente['count'], $estud['count']]
      ];
  		return $data;
    }
}
