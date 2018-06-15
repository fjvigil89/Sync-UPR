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

    private $bajas = "OU=Bajas,OU=_Usuarios,DC=upr,DC=edu,DC=cu";
    private $NoSync = "OU=No Sync,OU=_Usuarios,DC=upr,DC=edu,DC=cu";
    private $Docente = "OU=Trabajador Docente,OU=_Usuarios,DC=upr,DC=edu,DC=cu";
    private $NoDocente= "OU=Trabajador NoDocente,OU=_Usuarios,DC=upr,DC=edu,DC=cu";
    private $Upredes = "OU=_GrupoRedes,OU=_Usuarios,DC=upr,DC=edu,DC=cu";
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request     
     * @return \Illuminate\Http\Response
     */
    public function saberLdap(Request $request, $item) //item es la unidad a actualizar (Estudiantes, Docentes, No Docentes, Bajas)
    {
       
    	 $time_start = microtime(true);    	 
  		 $array_NoUpdate= array();
  		 $array_Update= array();
  		 $ldap = new ldap();
  		 $assets = new Assets;	    	 
  		 $lista_ldap = $ldap->saberLdap($item); 
  		 $group= array();
  	    	 
  	    	 	for ($i=0; $i < count($lista_ldap)-1 ; $i++) { 
  		    	 	try{		    	 		
  		    	 	 		
                  $lugar = true;
                  $existe_asstes = true;

                  //para descartar grupos de distintas Unidades Organizativas
                  if(strstr($lista_ldap[$i]['distinguishedname'][0], 'Builtin')) $lugar = false;
                  if(strstr($lista_ldap[$i]['distinguishedname'][0], 'Users')) $lugar = false;
                  if(strstr($lista_ldap[$i]['distinguishedname'][0], '2ble')) $lugar = false;
                  if(strstr($lista_ldap[$i]['distinguishedname'][0], 'Facultades')) $lugar = false;
                  if(strstr($lista_ldap[$i]['distinguishedname'][0], 'Gestion')) $lugar = false;
                  if(strstr($lista_ldap[$i]['distinguishedname'][0], 'No Sync')) $lugar = false;
                  if(strstr($lista_ldap[$i]['distinguishedname'][0], 'Soroa')) $lugar = false;
                  
                     
                   if($lugar)
                   {  
                       $empleado = $assets->findEmpleado(trim(ltrim($lista_ldap[$i]["employeenumber"][0]))); 
                        
                        if ($empleado == "No Existe") {

                           Log::critical($i." -- No se puede actualizar al empleado ".$lista_ldap[$i]["displayname"][0]." por no estar en assets:");
                          array_push($array_NoUpdate, $lista_ldap[$i]);
                          $existe_asstes= false;
                          $ldap->mover($lista_ldap[$i]['dn'], "OU=Actualizar,OU=_Usuarios,DC=upr,DC=edu,DC=cu"); 
                           Log::warning(" No se puede actualizar al empleado ".$lista_ldap[$i]["displayname"][0]." por no tener departamento en assets:"); 
                        }


                        if ($empleado == "" || $empleado == "Alguna cosa esta mal") {

                          //$this->SendEmail($lista_ldap[$i]['displayname'][0],$lista_ldap[$i]['samaccountname'][0]);
                          Log::critical($i." -- No se puede actualizar al empleado ".$lista_ldap[$i]["displayname"][0]." por no estar en assets:");
                          array_push($array_NoUpdate, $lista_ldap[$i]);  
                          $existe_asstes= false;
                          $ldap->mover($lista_ldap[$i]['dn'], "OU=Actualizar,OU=_Usuarios,DC=upr,DC=edu,DC=cu");  
                           Log::warning(" No se puede actualizar al empleado ".$lista_ldap[$i]["displayname"][0]." por no tener departamento en assets:");
                        }
                        
                        if($existe_asstes)
                        {
                          Log::alert($i." -- Empleado ". $lista_ldap[$i]["distinguishedname"][0]." del assets ");

                          
                          $TrabBaja = $assets->findBaja($lista_ldap[$i]["employeenumber"][0]);
                          if ($TrabBaja) {

                            $this->DeleteGrupoBaja($lista_ldap[$i]['distinguishedname'][0]);
                            $ldap->mover($lista_ldap[$i]['dn'], $this->bajas);  
                            $ldap->Disable($lista_ldap[$i]['samaccountname'][0]);

                            Log::warning(" Moviendo al empleado ".$lista_ldap[$i]["displayname"][0]." por ser baja del assets:");
                            array_push($array_NoUpdate, $lista_ldap[$i]);
                            
                          }
                          else{
                             $this->Actualizar_Usuarios_Upr($empleado[0], $lista_ldap[$i]);
                              
                            }//else del if de Trabbaja 
                        }//if existe_assets
                   }//if lugar     						 	
  						
  		    	 	 }//try
  		    	 	catch(\Exception $e)
  	        		{  	        			
  	            	Log::critical(" No se puede actualizar al empleado ".$lista_ldap[$i]["distinguishedname"][0]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
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

    function AddGrupoNoDocente($distinguishedname, $idEmployeed)
    {
    	$ldap = new ldap();
    	$assets = new Assets;

    	//grupos que se les adicionar'an al usuario 
    	$group= [
    		'Domain Users',
    		'UPR-Wifi',
    		'UPR-Jabber',
    		'UPR-Correo-Internacional',
        'UPR-NoDocentes'
    	];   	

    	foreach ($assets->SaberGrupo($idEmployeed) as $value) {
    		array_push($group, $value);
    	}

		$ldap->addtogroup($distinguishedname, $group);
    }

    function AddGrupoDocente($distinguishedname, $idEmployeed)
    {
    	$ldap = new ldap();    
    	$assets = new Assets;
    	//grupos que se les adicionar'an al usuario     	
    	$group = [
    		'Domain Users',
    		'UPR-Wifi',
    		'UPR-Jabber',
    		'UPR-Internet-Profes',
    		'UPR-Correo-Internacional',
        'UPR-Docentes'
    	];

    	foreach ($assets->SaberGrupo($idEmployeed) as $value) {
    		array_push($group, $value);
    	}

		$ldap->addtogroup($distinguishedname, $group);
    }

    function AddGrupoUPredes($distinguishedname, $idEmployeed)
    {
      $ldap = new ldap();    
      $assets = new Assets;
      //grupos que se les adicionar'an al usuario       
      $group = [
        'Domain Users',        
      ];

      foreach ($assets->SaberGrupo($idEmployeed) as $value) {
        array_push($group, $value);
      }

      $ldap->addtogroup($distinguishedname, $group);
    }

    function DeleteGrupo($distinguishedname)
    {
    	$ldap = new ldap();

    	//grupos que se quiere que no se le sean eliminado al usuario
    	$group= [    		
    		'UPR-Internet-NoDocente',
    		'UPR-Internet-Est',  			
  			'UPR-Internet-Postgrado',
  			'UPR-Internet-AlumnoAyudante',
  			'UPR-Internet-Alumno5',  			  			
  			'UPR-Internet-PreDoctores',  			
    		'UPR-Ras', 
    		'UPR-Ext-est',  
  			'UPR-Postgrados',    			
  			'UPR-Internet-5to',
  			'UPR-Internet-EscuelaDoctoral',			
  			'UPR-Correo-Internacional-Est',
  			'UPR-Moodle-Admins',  			  			
  			'Admin-Upr',  			
  			'Famsa-Agro',
  			'Contabilidad',
  			'Economia',
  			'Industrial',
  			'Fcsh-Todos',
  			'Comunicacion',
  			'Derecho',
  			'Humanidades',
  			'Idioma',
  			'Sociocultural',
  			'Energia',
  			'Fisica',
  			'Geo',
  			'Info',
  			'Mat',
  			'Meca',
  			'Tele',
  			'Agropecuaria',
  			'Biologia',
  			'Forestal',
  			'Quimica',  			
  			'FCT',  			  			
  			'Famsa-Todos',
  			'Fce-Todos',
  			'Fct-Todos',
  			'Ffa-Todos',
  			'Marxismo',  			
  			'Cef',  			 			
  			'consejofct',
  			//'androidfct',  			  			
  			'CEDAF',
  			'CADeporte',
  			'DEFR',
  			'FCF-DD',
  			'FCF-EFisica',
  			'FCF-Todos',  			
  			'EMedia',  			
  			'FEM-PROFESORES',  			
  			'FEI_Dep_Art',
  			'FEI_Dep_Esp',
  			'FEI_Dep_Form',
  			'FEI_Dep_Ped',
  			'FEI_Dep_Pres',
  			'FEI_Dep_Prim',
  			'FEI_Fac',
  			'aeacp',  			
    	];
	   	$ldap->deltogroup($distinguishedname, $group);	
    }

    function DeleteGrupoBaja($distinguishedname)
    {
    	$ldap = new ldap();
    	$ldap->deltogroup($distinguishedname);
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

    function InternetProfesore()
    {         
       
        try{
             $time_start = microtime(true); 
             $ldap = new ldap();
             $Docente =$ldap->InternetProfesores();
             $lista_Doc = array();

             for ($i=0; $i < count($Docente)-1 ; $i++) { 

                $exist = $this->OU_No_Revisar($Docente[$i]);
                if ($exist)
                {                
                  
                  $list_aux= ['cn'=>$Docente[$i]['cn'][0],'samaccountname'=>$Docente[$i]['samaccountname'][0] ,'description' => $Docente[$i]['description'][0], 'physicaldeliveryofficename' => $Docente[$i]['physicaldeliveryofficename'][0]];
                  array_push($lista_Doc, $list_aux );  
                }                
                
              }
             
            $time_end = microtime(true);
            $time_total = $time_end - $time_start;

            
             return view('reportes',[            
            'arrayProcesados'=>$lista_Doc, 
            'time' => $time_total,            
            'total' => count($lista_Doc)-1,
            'reporte' => 'Internet Docentes'
          ]);

          }
          catch(\Exception $e)
            {
             
              Log::critical(" No se puede Ver Los usuarios del Docente ".$Docente[$i]["distinguishedname"][0]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            
          }
    }

    function InternetEstudiantes()
    {     

       try{
             $time_start = microtime(true); 
             $ldap = new ldap();
             $estudiantes =$ldap->InternetEstudiante();
             $lista_estud = array();

             for ($i=0; $i < count($estudiantes)-1 ; $i++) { 

                $exist = $this->OU_No_Revisar($estudiantes[$i]);
                if ($exist)
                {                
                  
                  $list_aux= ['cn'=>$estudiantes[$i]['cn'][0],'samaccountname'=>$estudiantes[$i]['samaccountname'][0] , 'description' => $estudiantes[$i]['description'][0]='estudiante', 'physicaldeliveryofficename' => $estudiantes[$i]['physicaldeliveryofficename'][0]='facultad'];
                  array_push($lista_estud, $list_aux );  
                }                
                
              }
             
            $time_end = microtime(true);
            $time_total = $time_end - $time_start;

            
             return view('reportes',[            
            'arrayProcesados'=>$lista_estud, 
            'time' => $time_total,            
            'total' => count($lista_estud)-1,
            'reporte' => 'Internet Estudiantes'
          ]);

          }
          catch(\Exception $e)
            {
             
              Log::critical(" No se puede Ver Los usuarios del estudiantes ".$estudiantes[$i]["distinguishedname"][0]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            
          }
    }

    function InternetNoDocentes()
    {     

       
       try{
             $time_start = microtime(true); 
             $ldap = new ldap();
             $NoDocente =$ldap->InternetNoDocentes();
             $lista_noDoc = array();

             for ($i=0; $i < count($NoDocente)-1 ; $i++) { 

                $exist = $this->OU_No_Revisar($NoDocente[$i]);
                if ($exist)
                {                
                  
                  $list_aux= ['cn'=>$NoDocente[$i]['cn'][0],'samaccountname'=>$NoDocente[$i]['samaccountname'][0] , 'description' => $NoDocente[$i]['description'][0], 'physicaldeliveryofficename' => $NoDocente[$i]['physicaldeliveryofficename'][0]];
                  array_push($lista_noDoc, $list_aux );  
                }                
                
              }
             
            $time_end = microtime(true);
            $time_total = $time_end - $time_start;

            
             return view('reportes',[            
            'arrayProcesados'=>$lista_noDoc, 
            'time' => $time_total,            
            'total' => count($lista_noDoc)-1,
            'reporte' => 'Internet No Docentes'
          ]);

          }
          catch(\Exception $e)
            {
             
              Log::critical(" No se puede Ver Los usuarios del NoDocente ".$NoDocente[$i]["distinguishedname"][0]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            
          }
    }

    function NoDocentes()
    {     

       
       try{
             $time_start = microtime(true); 
             $ldap = new ldap();
             $NoDocente =$ldap->NoDocentes();
             $lista_noDoc = array();

             for ($i=0; $i < count($NoDocente)-1 ; $i++) { 

                $exist = $this->OU_No_Revisar($NoDocente[$i]);
                if ($exist)
                {                
                  
                  $list_aux= ['cn'=>$NoDocente[$i]['cn'][0],'samaccountname'=>$NoDocente[$i]['samaccountname'][0] , 'description' => $NoDocente[$i]['description'][0], 'physicaldeliveryofficename' => $NoDocente[$i]['physicaldeliveryofficename'][0]];
                  array_push($lista_noDoc, $list_aux );  
                }                
                
              }
             
            $time_end = microtime(true);
            $time_total = $time_end - $time_start;

            
             return view('reportes',[            
            'arrayProcesados'=>$lista_noDoc, 
            'time' => $time_total,            
            'total' => count($lista_noDoc)-1,
            'reporte' => 'No Docentes'
          ]);

          }
          catch(\Exception $e)
            {
             
              Log::critical(" No se puede Ver Los usuarios del NoDocente ".$NoDocente[$i]["distinguishedname"][0]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            
          }
    }

    function Docentes()
    {           
       try{
             $time_start = microtime(true); 
             $ldap = new ldap();
             $Docente =$ldap->Docentes();
             $lista_Doc = array();

             for ($i=0; $i < count($Docente)-1 ; $i++) { 

                $exist = $this->OU_No_Revisar($Docente[$i]);
                if ($exist)
                {                
                  
                  $list_aux= ['cn'=>$Docente[$i]['cn'][0],'samaccountname'=>$Docente[$i]['samaccountname'][0] , 'description' => $Docente[$i]['description'][0], 'physicaldeliveryofficename' => $Docente[$i]['physicaldeliveryofficename'][0]];
                  array_push($lista_Doc, $list_aux );  
                }                
                
              }
             
            $time_end = microtime(true);
            $time_total = $time_end - $time_start;

            
             return view('reportes',[            
            'arrayProcesados'=>$lista_Doc, 
            'time' => $time_total,            
            'total' => count($lista_Doc)-1,
            'reporte' => 'Docentes'
          ]);

          }
          catch(\Exception $e)
            {
             
              Log::critical(" No se puede Ver Los usuarios del Docente ".$Docente[$i]["distinguishedname"][0]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            
          }
    }

    function Estudiantes()
    {     

       
       try{
             $time_start = microtime(true); 
             $ldap = new ldap();
             $estud =$ldap->Estudiante();
             $lista_estud = array();

             for ($i=0; $i < count($estud)-1 ; $i++) { 

                $exist = $this->OU_No_Revisar($estud[$i]);
                if ($exist)
                {                
                  
                  $list_aux= ['cn'=>$estud[$i]['cn'][0],'samaccountname'=>$estud[$i]['samaccountname'][0] , 'description' => $estud[$i]['description'][0]='estudiante', 'physicaldeliveryofficename' => $estud[$i]['physicaldeliveryofficename'][0]='facultad'];
                  array_push($lista_estud, $list_aux );  
                }                
                
              }
             
            $time_end = microtime(true);
            $time_total = $time_end - $time_start;

            
             return view('reportes',[            
            'arrayProcesados'=>$lista_estud, 
            'time' => $time_total,            
            'total' => count($lista_estud)-1,
            'reporte' => 'Estudiantes'
          ]);

          }
          catch(\Exception $e)
            {
             
              Log::critical(" No se puede Ver Los usuarios del OU Estudiantes ".$estud[$i]["distinguishedname"][0]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            
          }
    }

    function Ras()
    {   
        try{
             $time_start = microtime(true); 
             $ldap = new ldap();
             $ras =$ldap->UsuariosRas();
             $lista_ras = array();

             for ($i=0; $i < count($ras)-1 ; $i++) { 

                $exist = $this->OU_No_Revisar($ras[$i]);
                if ($exist)
                {                
                  
                  $list_aux= ['cn'=>$ras[$i]['cn'][0],'samaccountname'=>$ras[$i]['samaccountname'][0] , 'description' => $ras[$i]['description'][0], 'physicaldeliveryofficename' => $ras[$i]['physicaldeliveryofficename'][0]];
                  array_push($lista_ras, $list_aux );  
                }                
                
              }
             
            $time_end = microtime(true);
            $time_total = $time_end - $time_start;

            
             return view('reportes',[            
            'arrayProcesados'=>$lista_ras, 
            'time' => $time_total,            
            'total' => count($lista_ras)-1,
            'reporte' => 'Ras'
          ]);

          }
          catch(\Exception $e)
            {
             
              Log::critical(" No se puede Ver Los usuarios del RAS ".$ras[$i]["distinguishedname"][0]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            
          }
    }
    function DocentesRas()
    {   
        try{
             $time_start = microtime(true); 
             $ldap = new ldap();
             $ras =$ldap->DocentesRas();
             $lista_ras = array();

             for ($i=0; $i < count($ras)-1 ; $i++) { 

                $exist = $this->OU_No_Revisar($ras[$i]);
                if ($exist)
                {                
                  
                  $list_aux= ['cn'=>$ras[$i]['cn'][0],'samaccountname'=>$ras[$i]['samaccountname'][0] , 'description' => $ras[$i]['description'][0], 'physicaldeliveryofficename' => $ras[$i]['physicaldeliveryofficename'][0]];
                  array_push($lista_ras, $list_aux );  
                }                
                
              }
             
            $time_end = microtime(true);
            $time_total = $time_end - $time_start;

            
             return view('reportes',[            
            'arrayProcesados'=>$lista_ras, 
            'time' => $time_total,            
            'total' => count($lista_ras)-1,
            'reporte' => 'Docentes con Ras'
          ]);

          }
          catch(\Exception $e)
            {
             
              Log::critical(" No se puede Ver Los usuarios del RAS ".$ras[$i]["distinguishedname"][0]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            
          }
    }

    function NoDocentesRas()
    {   
        try{
             $time_start = microtime(true); 
             $ldap = new ldap();
             $ras =$ldap->NoDocentesRas();
             $lista_ras = array();

             for ($i=0; $i < count($ras)-1 ; $i++) { 

                $exist = $this->OU_No_Revisar($ras[$i]);
                if ($exist)
                {                
                  
                  $list_aux= ['cn'=>$ras[$i]['cn'][0],'samaccountname'=>$ras[$i]['samaccountname'][0] , 'description' => $ras[$i]['description'][0], 'physicaldeliveryofficename' => $ras[$i]['physicaldeliveryofficename'][0]];
                  array_push($lista_ras, $list_aux );  
                }                
                
              }
             
            $time_end = microtime(true);
            $time_total = $time_end - $time_start;

            
             return view('reportes',[            
            'arrayProcesados'=>$lista_ras, 
            'time' => $time_total,            
            'total' => count($lista_ras)-1,
            'reporte' => 'No Docentes con Ras'
          ]);

          }
          catch(\Exception $e)
            {
             
              Log::critical(" No se puede Ver Los usuarios del RAS ".$ras[$i]["distinguishedname"][0]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            
          }
    }

    function Doctores()
    {           
       try{
             $time_start = microtime(true); 
             $ldap = new ldap();
             $doctores =$ldap->KuotaDoctor();
             $lista_Doctor = array();

             for ($i=0; $i < count($doctores)-1 ; $i++) { 

                $exist = $this->OU_No_Revisar($doctores[$i]);
                if ($exist)
                {                
                  
                  $list_aux= ['cn'=>$doctores[$i]['cn'][0],'samaccountname'=>$doctores[$i]['samaccountname'][0] , 'description' => $doctores[$i]['description'][0], 'physicaldeliveryofficename' => $doctores[$i]['physicaldeliveryofficename'][0]];
                  array_push($lista_Doctor, $list_aux );  
                }                
                
              }
             
            $time_end = microtime(true);
            $time_total = $time_end - $time_start;

            
             return view('reportes',[            
            'arrayProcesados'=>$lista_Doctor, 
            'time' => $time_total,            
            'total' => count($lista_Doctor)-1,
            'reporte' => 'Kuota de Doctores'
          ]);

          }
          catch(\Exception $e)
            {
             
              Log::critical(" No se puede Ver Los usuarios del doctores ".$doctores[$i]["distinguishedname"][0]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            
          }
    }

    function Master()
    {           
       try{
             $time_start = microtime(true); 
             $ldap = new ldap();
             $master =$ldap->KuotaMater();
             $lista_Master = array();

             for ($i=0; $i < count($master)-1 ; $i++) { 

                $exist = $this->OU_No_Revisar($master[$i]);
                if ($exist)
                {                
                  
                  $list_aux= ['cn'=>$master[$i]['cn'][0],'samaccountname'=>$master[$i]['samaccountname'][0] , 'description' => $master[$i]['description'][0], 'physicaldeliveryofficename' => $master[$i]['physicaldeliveryofficename'][0]];
                  array_push($lista_Master, $list_aux );  
                }                
                
              }
             
            $time_end = microtime(true);
            $time_total = $time_end - $time_start;

            
             return view('reportes',[            
            'arrayProcesados'=>$lista_Master, 
            'time' => $time_total,            
            'total' => count($lista_Master)-1,
            'reporte' => 'Kuota de Master'
          ]);

          }
          catch(\Exception $e)
            {
             
              Log::critical(" No se puede Ver Los usuarios del master ".$master[$i]["distinguishedname"][0]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            
          }
    }

    function Cuadro()
    {           
       try{
             $time_start = microtime(true); 
             $ldap = new ldap();
             $cuadro =$ldap->KuotaCuadro();
             $lista_cuadro = array();

             for ($i=0; $i < count($cuadro)-1 ; $i++) { 

                $exist = $this->OU_No_Revisar($cuadro[$i]);
                if ($exist)
                {                
                  
                  $list_aux= ['cn'=>$cuadro[$i]['cn'][0],'samaccountname'=>$cuadro[$i]['samaccountname'][0] , 'description' => $cuadro[$i]['description'][0], 'physicaldeliveryofficename' => $cuadro[$i]['physicaldeliveryofficename'][0]];
                  array_push($lista_cuadro, $list_aux );  
                }                
                
              }
             
            $time_end = microtime(true);
            $time_total = $time_end - $time_start;

            
             return view('reportes',[            
            'arrayProcesados'=>$lista_cuadro, 
            'time' => $time_total,            
            'total' => count($lista_cuadro)-1,
            'reporte' => 'Kuota de Cuadro'
          ]);

          }
          catch(\Exception $e)
            {
             
              Log::critical(" No se puede Ver Los usuarios del cuadro ".$cuadro[$i]["distinguishedname"][0]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            
          }
    }
    
    function Rector()
    {           
       try{
             $time_start = microtime(true); 
             $ldap = new ldap();
             $rector =$ldap->KuotaRector();
             $lista_Rector = array();

             for ($i=0; $i < count($rector)-1 ; $i++) { 

                $exist = $this->OU_No_Revisar($rector[$i]);
                if ($exist)
                {                
                  
                  $list_aux= ['cn'=>$rector[$i]['cn'][0],'samaccountname'=>$rector[$i]['samaccountname'][0] , 'description' => $rector[$i]['description'][0], 'physicaldeliveryofficename' => $rector[$i]['physicaldeliveryofficename'][0]];
                  array_push($lista_Rector, $list_aux );  
                }                
                
              }
             
            $time_end = microtime(true);
            $time_total = $time_end - $time_start;

            
             return view('reportes',[            
            'arrayProcesados'=>$lista_Rector, 
            'time' => $time_total,            
            'total' => count($lista_Rector)-1,
            'reporte' => 'Kuota de Rector'
          ]);

          }
          catch(\Exception $e)
            {
             
              Log::critical(" No se puede Ver Los usuarios del rector ".$rector[$i]["distinguishedname"][0]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            
          }
    }

    function Adiestrados()
    {           
       try{
             $time_start = microtime(true); 
             $ldap = new ldap();
             $adiestrados =$ldap->Adiestrados();
             $lista_adiestrados = array();

             for ($i=0; $i < count($adiestrados)-1 ; $i++) { 

                $exist = $this->OU_No_Revisar($adiestrados[$i]);
                if ($exist)
                {                
                  
                  $list_aux= ['cn'=>$adiestrados[$i]['cn'][0],'samaccountname'=>$adiestrados[$i]['samaccountname'][0] , 'description' => $adiestrados[$i]['description'][0], 'physicaldeliveryofficename' => $adiestrados[$i]['physicaldeliveryofficename'][0]];
                  array_push($lista_adiestrados, $list_aux );  
                }                
                
              }
             
            $time_end = microtime(true);
            $time_total = $time_end - $time_start;

            
             return view('reportes',[            
            'arrayProcesados'=>$lista_adiestrados, 
            'time' => $time_total,            
            'total' => count($lista_adiestrados)-1,
            'reporte' => 'Adiestrados en la UPR'
          ]);

          }
          catch(\Exception $e)
            {
             
              Log::critical(" No se puede Ver Los usuarios del adiestrados ".$adiestrados[$i]["distinguishedname"][0]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            
          }
    }

    function UltimosUsuariosCreados()
    {           
       try{
             $time_start = microtime(true); 
             $ldap = new ldap();
             $usuarios_creados =$ldap->SaberUltimasUserCreador();
             $lista_usuarios_creados = array();

             for ($i=0; $i < count($usuarios_creados)-1 ; $i++) { 
                  
                  $list_aux= ['cn'=>$usuarios_creados[$i]['cn'][0],'samaccountname'=>$usuarios_creados[$i]['samaccountname'][0] , 'description' => $usuarios_creados[$i]['description'][0], 'physicaldeliveryofficename' => $usuarios_creados[$i]['physicaldeliveryofficename'][0]];
                  array_push($lista_usuarios_creados, $list_aux );
              }
             
            $time_end = microtime(true);
            $time_total = $time_end - $time_start;

            
             return view('reportes',[            
            'arrayProcesados'=>$lista_usuarios_creados, 
            'time' => $time_total,            
            'total' => count($lista_usuarios_creados)-1,
            'reporte' => 'Adiestrados en la UPR'
          ]);

          }
          catch(\Exception $e)
            {
             
              Log::critical(" No se puede Ver Los usuarios del adiestrados ".$usuarios_creados[$i]["distinguishedname"][0]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            
          }
    }

    function TodosUsuarios(){
      try{
             $time_start = microtime(true); 
             $ldap = new ldap();
             $docentes =$ldap->Docentes();
             $noDocentes =$ldap->NoDocentes();
             $estudiantes =$ldap->Estudiante();
             $lista_usuarios = array();

             for ($i=0; $i < count($docentes)-1 ; $i++) { 

                $exist = $this->OU_No_Revisar($docentes[$i]);
                if ($exist)
                {                
                  
                  $list_aux= ['cn'=>$docentes[$i]['cn'][0],'samaccountname'=>$docentes[$i]['samaccountname'][0] , 'description' => $docentes[$i]['description'][0], 'physicaldeliveryofficename' => $docentes[$i]['physicaldeliveryofficename'][0]];
                  array_push($lista_usuarios, $list_aux );  
                }                
                
              }

              for ($i=0; $i < count($noDocentes)-1 ; $i++) { 

                $exist1 = $this->OU_No_Revisar($noDocentes[$i]);
                if ($exist1)
                {                
                  
                  $list_aux1= ['cn'=>$noDocentes[$i]['cn'][0],'samaccountname'=>$noDocentes[$i]['samaccountname'][0] , 'description' => $noDocentes[$i]['description'][0], 'physicaldeliveryofficename' => $noDocentes[$i]['physicaldeliveryofficename'][0]];
                  array_push($lista_usuarios, $list_aux1 );  
                }                
                
              }
             
             for ($i=0; $i < count($estudiantes)-1 ; $i++) { 

                $exist2 = $this->OU_No_Revisar($estudiantes[$i]);
                if ($exist2)
                {                
                  
                  $list_aux2= ['cn'=>$estudiantes[$i]['cn'][0],'samaccountname'=>$estudiantes[$i]['samaccountname'][0] , 'description' => $estudiantes[$i]['description'][0]='estudiante', 'physicaldeliveryofficename' => $estudiantes[$i]['physicaldeliveryofficename'][0]='facultad'];
                  array_push($lista_usuarios, $list_aux2 );  
                }                
                
              }

            $time_end = microtime(true);
            $time_total = $time_end - $time_start;

            
             return view('reportes',[            
            'arrayProcesados'=>$lista_usuarios, 
            'time' => $time_total,            
            'total' => count($lista_usuarios)-1,
            'reporte' => 'Todos los usuarios en la UPR'
          ]);

          }
          catch(\Exception $e)
            {
             
              Log::critical(" No se puede Ver Los usuarios del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            
          }
    }

    function OU_No_Revisar($ras)
    {     
      $exist = true;
      if($ras['distinguishedname'][0] == "")$exist = false;
      if(strstr($ras['distinguishedname'][0], '_Bajas'))$exist = false;
      if(strstr($ras['distinguishedname'][0], 'Bajas'))$exist = false;
      if(strstr($ras['distinguishedname'][0], '_UsuariosEspeciales'))$exist = false;
      #if(strstr($ras['distinguishedname'][0], '_Jubilados'))$exist = false;
      if(strstr($ras['distinguishedname'][0], 'Facultades'))$exist = false;
      if(strstr($ras['distinguishedname'][0], 'No Sync'))$exist = false;
      if(strstr($ras['distinguishedname'][0], '_Postgrado'))$exist = false;
      if(strstr($ras['distinguishedname'][0], '_Institucionales'))$exist = false;

      return $exist;      

    }

    function Buscar(Request $request)
    {    
      $user = $this->provider->search()->find($request->search);
      dd($user);
      
      try{
             $exist = true;
             $time_start = microtime(true); 
             $ldap = new ldap();
             $busaqueda =$ldap->Busqueda($request->search);

             $lista_busaqueda = array();

             for ($i=0; $i < count($busaqueda)-1 ; $i++) {
                if(strstr($busaqueda[$i]['distinguishedname'][0], 'Computers'))$exist = false;
                if(strstr($busaqueda[$i]['distinguishedname'][0], 'Estudiantes'))$exist = false;
                if(strstr($busaqueda[$i]['distinguishedname'][0], 'Graduados'))$exist = false;
                if(strstr($busaqueda[$i]['distinguishedname'][0], 'Bajas'))$exist = false;
                if(strstr($busaqueda[$i]['distinguishedname'][0], '_Bajas'))$exist = false;
                if(strstr($busaqueda[$i]['distinguishedname'][0], '_Postgrado'))$exist = false;
                if(strstr($busaqueda[$i]['distinguishedname'][0], '_Institucionales'))$exist = false;
                if(strstr($busaqueda[$i]['distinguishedname'][0], 'No Sync'))$exist = false;
                if(strstr($busaqueda[$i]['distinguishedname'][0], '_UsuariosEspeciales'))$exist = false;

                                    
                  if($exist)
                  {
                    $list_aux= ['cn'=>$busaqueda[$i]['cn'][0],'samaccountname'=>$busaqueda[$i]['samaccountname'][0],'description' => $busaqueda[$i]['description'][0],'physicaldeliveryofficename' => $busaqueda[$i]['physicaldeliveryofficename'][0]
                    ];                    
                    array_push($lista_busaqueda, $list_aux );
                  }                  
                  
                   
                  
              }
             
            $time_end = microtime(true);
            $duration = $time_end - $time_start;
            $hours = (int)($duration/60/60);
            $minutes = (int)($duration/60)-$hours*60;
            $seconds = (int)$duration-$hours*60*60-$minutes*60;
            $time_total="horas ".$hours." minutos ".$minutes." segundos ".$seconds;
             return view('search',[            
            'arrayProcesados'=>$lista_busaqueda, 
            'time' => $time_total,            
            'total' => count($lista_busaqueda)-1,            
          ]);

          }
          catch(\Exception $e)
            {
             
              Log::critical(" No se puede Ver Los usuarios del Busaqueda ".$busaqueda[$i]["distinguishedname"][0]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            
          }
    }

    function ActualizarBajasProfesores(Request $request)
    {                 
      try{
             $ldap = new ldap();
             $assets = new Assets();
             $baja =$assets->SearchBajaProfesor();
             if ($baja != "No Existe") {              
               # code...              
                  
                   for ($i=0; $i < count($baja) ; $i++) {

                        
                        Log::critical("Dandole de Baja al usuario ".$baja[$i]['nombre']." Day ".Carbon::now());   
                        
                        $lista=$ldap->saberLdapTrabajador($baja[$i]['idExpediente']);
                        $this->DeleteGrupoBaja($lista[$i]['distinguishedname'][0]);
                        $ldap->mover($lista[$i]['dn'], $this->bajas);  
                        $ldap->Disable($lista[$i]['samaccountname'][0]);
                        
                    }                   
                  
                  return $baja;
                
             }
             else
              {
                Log::critical("No Hay Trabajadores de Baja ".Carbon::now());
              } 

              return $baja;

          }
          catch(\Exception $e)
            {
             
              Log::critical(" No se puede da de Baja a ".$baja[$i]["nombre"]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            
          }
    }

   function ActualizarAltasProfesores(Request $request)
    {                 
      try{
             $ldap = new ldap();
             $assets = new Assets();
             $alta =$assets->SearchAltasProfesor();
             $exist = true;
             if ($alta != "No Existe") {  
                   for ($i=0; $i < count($alta) ; $i++) {

                        Log::critical("Dandole de Alta al usuario ".$alta[$i]['nombre']." Day ".Carbon::now());   
                        
                        $exist_assets=$ldap->ExistUsuario($alta[$i]['idExpediente']);
                                        
                        if ($exist_assets) { 
                            $aux = $ldap->Busqueda($alta[$i]['idExpediente']);  
                           if(strstr($aux[0]['distinguishedname'][0], 'Nuevos'))$exist = false;

                           if ($exist) {
                              $this->Actualizar_Usuarios_Upr($alta[$i], $aux[0]);
                              
                           }//if existe
                        }//if exist_assets
                        else
                        {
                          if (!$ldap->CrearUsuario($alta[$i])) {
                            # code...
                            Log::critical(" No se puede da de Alta a ".$alta[$i]["nombre"]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
                          }
                           Log::Info(" Se dio de Alta a ".$alta[$i]["nombre"]." en el AD");
                        }
                        
                    }                   
                  
                  return $alta;
                
             }
             else
              {
                Log::critical("No Hay Trabajadores de Alta ".Carbon::now());
              } 

              return $alta;

          }
          catch(\Exception $e)
            {
             
              Log::critical(" No se puede da de Alta a ".$alta[$i]["nombre"]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            
          }
    }

    function Actualizar_Usuarios_Upr($empleado, $lista_ldap)
    {
      
      try{
            $ldap = new ldap();
            $assets = new Assets();
         $departamento = $assets->findDepartaento(trim(ltrim($empleado["idCcosto"])));
        if ($departamento == "" || $departamento == "Alguna cosa esta mal") {
            //$this->SendEmail($lista_ldap[$i]['displayname'][0],$lista_ldap[$i]['samaccountname'][0]);

          Log::warning(" No se puede actualizar al empleado ".$lista_ldap["displayname"][0]." por no tener departamento en assets:");          
        }   

        $cargo = $assets->findCargo(trim(ltrim($empleado["idCargo"])));
        if ($cargo == "" || $cargo == "Alguna cosa esta mal") {

          Log::critical(" No se puede actualizar al empleado ".$empleado["nombre"][0]." por no tener cargo en assets:");                                  
        }
        
        if(!$ldap->ActualizarCamposIdEmpleado($empleado, $departamento, $cargo, $lista_ldap['samaccountname'][0]))
        {
                    
          $ldap->mover($lista_ldap['dn'], "OU=Actualizar,OU=_Usuarios,DC=upr,DC=edu,DC=cu");
          Log::warning(" Moviendo al empleado ".$lista_ldap["displayname"][0]." por no Poder Actualizarce:"); 

        }
        else{ 

            $profes = $assets->findDocente(trim($lista_ldap["employeenumber"][0]));                   
              if (!$profes) {
                
                //$this->DeleteGrupo($lista_ldap['distinguishedname'][0]); 
                $this->AddGrupoNoDocente($lista_ldap['distinguishedname'][0],trim($lista_ldap['employeenumber'][0]));  
                $ldap->mover($lista_ldap['dn'], $this->NoDocente);
                Log::warning(" Moviendo al empleado ".$lista_ldap["displayname"][0]." a -- No Docentes --:");                                     
              }
              if($profes){
                
                //$this->DeleteGrupo($lista_ldap['distinguishedname'][0]);        
                $this->AddGrupoDocente($lista_ldap['distinguishedname'][0], trim($lista_ldap['employeenumber'][0]));
                $ldap->mover($lista_ldap['dn'], $this->Docente);
                Log::warning(" Moviendo al empleado ".$lista_ldap["displayname"][0]." a -- Docentes --:");        
              }

            $ldap->Enable($lista_ldap['samaccountname'][0]);
          }
          return true;
      }
      catch(\Exception $e)
          {
           
            Log::critical(" No se puede Actualizar a ".$lista_ldap["distinguishedname"][0]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");

            return false;          
        }
      
    }

    function UPRedes()
    {      
       $ldap = new ldap();
       $assets = new Assets();
       $upredes = $ldap->findUPRedes();           

       for ($i=0; $i < count($upredes)-1 ; $i++) {
          try{

            $this->AddGrupoUPredes($upredes[$i]['distinguishedname'][0],trim($upredes[$i]['employeenumber'][0]));  
            $ldap->mover($upredes[$i]['dn'], $this->Upredes);
            Log::warning(" Moviendo al empleado ".$upredes[$i]["displayname"][0]." a -- Upredes --:");      
            }
          catch(\Exception $e)
            {
             
              Log::critical(" No se puede mover a ".$upredes[$i]["displayname"][0]." a UPRedes: {$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
          }
         }
      //return "UPREDES";           
          
    }

    function Login()
    {
      return view('Login.login');
    }

   function Doc()
    {
      return view('swagger.swagger');
    }

}
 