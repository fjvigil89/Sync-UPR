<?php

namespace Sync\Http\Controllers;

use Illuminate\Http\Request;
use Sync\ldap;
use Sync\Sigenu;
use Log;
use Carbon\Carbon;
use Collection;
use Mail;
class EstudiantesController extends Controller
{
    //
    private $bajas = "OU=Bajas,OU=_Usuarios,DC=upr,DC=edu,DC=cu";
    private $estudiantes= "OU=Estudiantes,OU=_Usuarios,DC=upr,DC=edu,DC=cu";

    public function SaberLdapStudent(Request $request, $item="Estudiantes")
    {
      ini_set('max_execution_time', 18000); //18000 segundos = 5 horas
    	$array_Update= array();
  		 $ldap = new ldap();
  		 $sigenu = new Sigenu;	    	 
  		 $lista_ldap = $ldap->saberLdap($item); 
  		 $group= array();
  		 
  		 for ($i=0; $i < count($lista_ldap)-1 ; $i++) { //count($lista_ldap)-1
  		    try{	 		
                 
                  $existe_sigenu = true;                                        
                   $estudent = $sigenu->findIdStudent(trim(ltrim($lista_ldap[$i]["employeenumber"][0]))); 
                    
                    if ($estudent == "" || $estudent == "Alguna cosa esta mal") {

                      $this->DeleteGrupoBajaStudent($lista_ldap[$i]['distinguishedname'][0]);                      
                      $ldap->Disable($lista_ldap[$i]['samaccountname'][0]);

                      Log::critical($i." -- DesHabilitando al Estudiante ".$lista_ldap[$i]["displayname"][0]." por no estar en Sigenu:");                      
                      $existe_sigenu= false;                      
                    }
                    if ($estudent == "500") {
                      # code...
                      Log::critical($i." --Hay problemas con el Servidor Api-Sigenu ");                      
                      $existe_sigenu= false;
                    }
                            
                    if($existe_sigenu)
                    {
                      
                      Log::alert($i." -- Estudiante ". $lista_ldap[$i]["distinguishedname"][0]." del Sigenu ");
                      
                      $StudentBaja = $sigenu->findBajaStudent($lista_ldap[$i]["employeenumber"][0]);
                      $StudentEgresado = $sigenu->findEgresadoStudent($lista_ldap[$i]["employeenumber"][0]);

                      if ($StudentBaja || $StudentEgresado)
                       {
                       	
                       	$this->Actualizar_Student_Upr($estudent, $lista_ldap[$i]);

                        $this->DeleteGrupoBajaStudent($lista_ldap[$i]['distinguishedname'][0]);
                        //$ldap->mover($lista_ldap[$i]['dn'], $this->bajas);  
                        $ldap->Disable($lista_ldap[$i]['samaccountname'][0]);

                        Log::warning(" Deshabilitando al Estudiante ".$lista_ldap[$i]["displayname"][0]." por ser baja del Sigenu:");
                        
                      }                                            
                      else{
                      		
                         $this->Actualizar_Student_Upr($estudent, $lista_ldap[$i]);
                          
                        }//else del if de Trabbaja 
                        
                    }//if existe_assets
  						
    	 	 }//try
    	 	catch(\Exception $e)
    		{  	        			
        		Log::critical(" No se puede actualizar al Estudiante ".$lista_ldap[$i]["distinguishedname"][0]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
	  		 	 //array_push($array_NoUpdate, $lista_ldap[$i]); 			  		 	
		  		
	  		}

  		}//end for 

    }//end method

    function DeleteGrupoBajaStudent($distinguishedname)
    {
    	$ldap = new ldap();
    	$ldap->deltogroup($distinguishedname);
    }

    function AddGrupoStudent($distinguishedname, $idEmployeed)
    {
    	$ldap = new ldap();
    	$sigenu = new Sigenu;

      $curso_tipo = $sigenu->findCursoTipo(trim(ltrim($idEmployeed)));         
    	//grupos que se les adicionar'an al usuario 
      //si el estudiante es CPE cambiarle los grupos
      if ($curso_tipo == "CRD") {
        # code...        
      	$group= [
          'Domain Users',
          'UPR-Wifi',
          'UPR-Jabber',
          'UPR-Correo-Internacional',
          'UPR-Estudiantes',
          'UPR-Internet-Est'
        ];    
      }
      else
      {
        $estudiante = $sigenu->findIdStudent(trim(ltrim($idEmployeed)));
        $user = $ldap->find_users_CI(trim(ltrim($estudiante['identification'])));        
        if($user['count']>1)//si existe como trabajador y estudiante,
        {           
          $group= [
            'Domain Users',
            'UPR-Wifi',
            'UPR-Jabber',
            'UPR-Correo-Internacional',
            'UPR-Estudiantes',
            'UPR-Internet-Est',          
          ];
        }
        else{
          $group= [
            'Domain Users',
            'UPR-Wifi',
            'UPR-Jabber',
            'UPR-Correo-Internacional',
            'UPR-Estudiantes',
          
          ];
        }        
      }

      


    	foreach ($sigenu->SaberGrupoStudent($idEmployeed) as $value) {
    		array_push($group, $value);
    	}
    	$this->DeleteGrupoBajaStudent($distinguishedname);
		  $ldap->addtogroup($distinguishedname, $group);
    }

    function Actualizar_Student_Upr($empleado, $lista_ldap)
    {
      
      try{
            $ldap = new ldap();
            $sigenu = new Sigenu();  

            
         	$facultad = $sigenu->findfacultad(trim(ltrim($empleado["id_student"])));  
	        if ($facultad == "" || $facultad == "Alguna cosa esta mal") {	        	
	            //$this->SendEmail($lista_ldap[$i]['displayname'][0],$lista_ldap[$i]['samaccountname'][0]);

	          Log::warning(" No se puede actualizar la facultad al Estudiante ".$lista_ldap["displayname"][0]." por no tener Facultad en el Sigenu:");          
	        }

	        $anno = $sigenu->findAnno(trim(ltrim($empleado["id_student"])));	        
	        if ($anno == "" || $anno == "Alguna cosa esta mal") {

	         Log::warning(" No se puede actualizar el anno academico al Estudiante ".$lista_ldap["displayname"][0]." por no tener Facultad en el Sigenu:");                                  
	        }

        	$curso_tipo = $sigenu->findCursoTipo(trim(ltrim($empleado["id_student"])));
        	
	        if ($curso_tipo == "" || $curso_tipo == "Alguna cosa esta mal") {

	         Log::warning(" No se puede actualizar tipo de curso al Estudiante ".$lista_ldap["displayname"][0]." por no tener Facultad en el Sigenu:");                                  
	        }

	        $carrera = $sigenu->findCarrera(trim(ltrim($empleado["id_student"])));
	         if ($carrera == "" || $carrera == "Alguna cosa esta mal") {

	         Log::warning(" No se puede actualizar la carrera al Estudiante ".$lista_ldap["displayname"][0]." por no tener Facultad en el Sigenu:");                                  
	        }

	        if(!$ldap->ActualizarCamposStudent($empleado, $facultad, $curso_tipo.' - '.$anno.' - '.$carrera, $lista_ldap['samaccountname'][0]))
	        {	                    
            $ldap->Disable($lista_ldap['samaccountname'][0]);
	          Log::warning(" Deshabilitando al Estudiante ".$lista_ldap["displayname"][0]." por no Poder Actualizarce:"); 

	        }
        else{ 

        	$this->AddGrupoStudent($lista_ldap['distinguishedname'][0],trim($lista_ldap['employeenumber'][0]));  
            $ldap->Enable($lista_ldap['samaccountname'][0]);

            if ($lista_ldap['samaccountname'][0] == "oberlandy.padilla" || $lista_ldap['samaccountname'][0] == "manuel.gomez" || $lista_ldap['samaccountname'][0] == "mario.arias" || $lista_ldap['samaccountname'][0] == "carlosa.reyes" || $lista_ldap['samaccountname'][0] == "luiso.rodriguez" ||$lista_ldap['samaccountname'][0] == "isabel.manresa" ||$lista_ldap['samaccountname'][0] == "maria.suarezg" ||$lista_ldap['samaccountname'][0] == "rarodriguez" ||$lista_ldap['samaccountname'][0] == "adonys.valdes" ||$lista_ldap['samaccountname'][0] == "jdiaz" ||$lista_ldap['samaccountname'][0] == "afernandez" ||$lista_ldap['samaccountname'][0] == "yessica.alvarezh" ||$lista_ldap['samaccountname'][0] == "arturo.gomez" ||$lista_ldap['samaccountname'][0] == "yoan.dominguezd" || $lista_ldap['samaccountname'][0] == "yohan.rivera" || $lista_ldap['samaccountname'][0] == "jose.munoz" || $lista_ldap['samaccountname'][0] == "dario.munoz" ) {             
            
               //Agregar ACM el grupos UPR-Internet-AlumnoAyudante
               $group= [        
                  'UPR-Internet-AlumnoAyudante'
                ];    
                $ldap->addtogroup($distinguishedname, $group); 
                Log::warning(" Adicionar grupo AlumnoAyudante a  ".$lista_ldap["displayname"][0]); 
              }
          }
          return true;
      }
      catch(\Exception $e)
          {
           
            Log::critical(" No se puede Actualizar a ".$lista_ldap["distinguishedname"][0]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");

            return false;          
        }
      
    }

    function Crear_Student_Upr()
    {
          $ldap = new ldap();
          $sigenu = new Sigenu();  
          
          $lista_sigenu = $sigenu->findNewStudent();
          $estudent = true;
          
            
            for ($i=0; $i < count($lista_sigenu)-1 ; $i++) { //count($lista_sigenu)-1
              try{  
                                                
                   $estudent = $ldap->ExisteEmpleado(trim(ltrim($lista_sigenu[$i]["id_matriculated_student"])));                    
                   $estudent_ci = $ldap->ExisteEmpleado_Ci(trim(ltrim($lista_sigenu[$i]["identification"])));                    
                   
                    if (!$estudent && ! $estudent_ci) {

                        $facultad = $sigenu->findfacultad_fx(trim(ltrim($lista_sigenu[$i]["faculty_fk"])));  

                        if ($facultad == "" || $facultad == "Alguna cosa esta mal") {           
                            //$this->SendEmail($lista_ldap[$i]['displayname'][0],$lista_ldap[$i]['samaccountname'][0]);
                          $facultad = null;
                          Log::warning(" No se puede actualizar la facultad al Estudiante ".$lista_sigenu[$i]["name"]." por no tener Facultad en el Sigenu:");
                        }


                        $carrera = $sigenu->findCarrera_fx(trim(ltrim($lista_sigenu[$i]["career_fk"])));

                         if ($carrera == "" || $carrera == "Alguna cosa esta mal") {
                          $carrera = null;  
                         Log::warning(" No se puede actualizar la carrera al Estudiante ".$lista_sigenu[$i]["name"]." por no tener Facultad en el Sigenu:");                                  
                        }

                        $anno = 1;                        

                        $curso_tipo = $sigenu->findCursoTipo_fx(trim(ltrim($lista_sigenu[$i]["course_type_fk"])));
                        if ($curso_tipo == "" || $curso_tipo == "Alguna cosa esta mal") {
                          $curso_tipo = null;
                         Log::warning(" No se puede actualizar tipo de curso al Estudiante ".$lista_sigenu[$i]["name"]." por no tener Facultad en el Sigenu:");                                  
                        }
                        if ($facultad != null && $carrera!= null && $curso_tipo != null) {
                             
                            if(!$ldap->CrearStudent($lista_sigenu[$i], $facultad, $curso_tipo.' - '.$anno.' - '.$carrera))
                            { 
                              Log::warning(" El Estudiante ya existe ".$lista_sigenu[$i]["name"]); 
                            }
                          else{ 
                              Log::warning(" Estudiante " .$lista_sigenu[$i]["name"]. " creado correctamente"); 
                            }
                        }
                      
                    }
                    else{
                      Log::warning(" El Estudiante ya existe ".$lista_sigenu[$i]["name"]); 
                    }
                  
                  
          }//try
          catch(\Exception $e)
          {                 
              Log::critical(" No se puede crear al Estudiante ". $lista_sigenu[$i]["name"]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
             //array_push($array_NoUpdate, $lista_ldap[$i]);              
            
          }

        }//end for       
    }

  function CrearEstudiante(Request $request)
  {
       $ldap = new ldap();
       $sigenu = new Sigenu();
       $user = Array();
       
      if ($sigenu->ExisteStudent($request->employeenumber)) 
      {
            
        if(!$ldap->ExisteEmpleado($request->employeenumber))
        {          

          $ldap->CrearStudentWeb($sigenu->findIdStudent($request->employeenumber));
          Log::warning(" Usuario Creado ".$request->employeenumber); 
          $student = $sigenu->findIdStudent($request->employeenumber);         
          $user= $ldap->saberLdapTrabajador($request->employeenumber); 
          
          $this->ActualizarEstudiantes($student, $user[0]);
        }
        else{
          return 'El Usuario existe en la Universidad';
        }
      }
      else{
        return "El Estudiante no pertenece a la Universidad";
      }

      $result = "<h1>El usuario ".$user[0]['cn'][0]." ya es parte de nuestros servicios</h1>";
      return $result;
      
  }

  function ActualizarEstudiantes ($student, $user)
  {
    
    try{  
        $ldap = new ldap();                       
        $sigenu = new Sigenu();

        $facultad = $sigenu->findfacultad_fx(trim(ltrim($student["faculty_fk"])));  
        
        if ($facultad == "" || $facultad == "Alguna cosa esta mal") {           
            //$this->SendEmail($lista_ldap[$i]['displayname'][0],$lista_ldap[$i]['samaccountname'][0]);
          $facultad = null;
          Log::warning(" No se puede actualizar la facultad al Estudiante ".$student["name"]." por no tener Facultad en el Sigenu:");
        }


        $carrera = $sigenu->findCarrera_fx(trim(ltrim($student["career_fk"])));

         if ($carrera == "" || $carrera == "Alguna cosa esta mal") {
          $carrera = null;  
         Log::warning(" No se puede actualizar la carrera al Estudiante ".$student["name"]." por no tener Facultad en el Sigenu:");                                  
        }

        $anno = 1;                        

        $curso_tipo = $sigenu->findCursoTipo_fx(trim(ltrim($student["course_type_fk"])));
        if ($curso_tipo == "" || $curso_tipo == "Alguna cosa esta mal") {
          $curso_tipo = null;
         Log::warning(" No se puede actualizar tipo de curso al Estudiante ".$student["name"]." por no tener Facultad en el Sigenu:");                                  
        }
        if ($facultad != null && $carrera!= null && $curso_tipo != null) {
             
            if(!$ldap->ActualizarCamposStudent($student, $facultad, $curso_tipo.' - '.$anno.' - '.$carrera, $user['samaccountname'][0]))
            { 
              Log::warning(" El Estudiante ya existe ".$student["name"]); 
            }
          else{ 
              Log::warning(" Estudiante " .$student["name"]. " creado correctamente"); 
            }
          $ldap->mover($user['dn'], $this->estudiantes);
          Log::warning(" Moviendo " .$student["name"]. " a Estudiantes"); 
        }
                  
     }//try
      catch(\Exception $e)
      {                 
          Log::critical(" No se puede crear al Estudiante ". $student["name"]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
         //array_push($array_NoUpdate, $lista_ldap[$i]);              
        
      }
  } 
}
