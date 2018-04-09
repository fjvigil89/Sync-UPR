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
		    	 	 			//$this->SendEmail($lista_ldap[$i]['displayname'][0],$lista_ldap[$i]['samaccountname'][0]);
		    	 	 			array_push($array_NoUpdate, $lista_ldap[$i]);
		    	 	 			break;
		    	 	 		}


		    	 	 		if ($empleado == "" || $empleado == "Alguna cosa esta mal") {

		    	 	 			//$this->SendEmail($lista_ldap[$i]['displayname'][0],$lista_ldap[$i]['samaccountname'][0]);
		    	 	 			Log::critical(Carbon::now()." No se puede actualizar al empleado ".$lista_ldap[$i]["displayname"][0]." por no estar en assets:");
			  		 			array_push($array_NoUpdate, $lista_ldap[$i]);			  		 			
		    	 	 		}

		    	 	 			
		    	 	 		$TrabBaja = $assets->findBaja($lista_ldap[$i]["employeenumber"][0]);
		    	 	 		if ($TrabBaja) {

		    	 	 			$this->DeleteGrupoBaja($lista_ldap[$i]['distinguishedname'][0]);
		    	 	 			$ldap->mover($lista_ldap[$i]['dn'], $bajas);	
		    	 	 			$ldap->Disable($lista_ldap[$i]['samaccountname'][0]);

		    	 	 			Log::critical(Carbon::now()." No se puede actualizar al empleado ".$lista_ldap[$i]["displayname"][0]." por ser baja del assets:");
			  		 			array_push($array_NoUpdate, $lista_ldap[$i]);
			  		 			
		    	 	 		}
		    	 	 		else{
		    	 	 			
			    	 	 		$departamento = $assets->findDepartaento(trim(ltrim($empleado[0]["idCcosto"])));
			    	 	 		if ($departamento == "" || $departamento == "Alguna cosa esta mal") {
			    	 	 			

			    	 	 			//$this->SendEmail($lista_ldap[$i]['displayname'][0],$lista_ldap[$i]['samaccountname'][0]);


			    	 	 			Log::critical(Carbon::now()." No se puede actualizar al empleado ".$lista_ldap[$i]["displayname"][0]." por no tener departamento en assets:");
				  		 			array_push($array_NoUpdate, $lista_ldap[$i]);			  		 			
			    	 	 		}


			    	 	 		$cargo = $assets->findCargo(trim(ltrim($empleado[0]["idCargo"])));
			    	 	 		if ($cargo == "" || $cargo == "Alguna cosa esta mal") {

			    	 	 			//$this->SendEmail($lista_ldap[$i]['displayname'][0],$lista_ldap[$i]['samaccountname'][0]);

			    	 	 			Log::critical(Carbon::now()." No se puede actualizar al empleado ".$lista_ldap[$i]["displayname"][0]." por no tener cargo en assets:");
				  		 			array_push($array_NoUpdate, $lista_ldap[$i]);
				  		 			
			    	 	 		}

			    	 	 		
							 	if(!$ldap->ActualizarCamposIdEmpleado($empleado[0], $departamento, $cargo, $lista_ldap[$i]['samaccountname'][0]))
							 	{
							 		array_push($array_NoUpdate, $lista_ldap[$i]);
							 		$ldap->mover($lista_ldap[$i]['dn'], "OU=Actualizar,OU=_Usuarios,DC=upr,DC=edu,DC=cu");	
							 		//$this->SendEmail($lista_ldap[$i]['displayname'][0],$lista_ldap[$i]['samaccountname'][0]);							 		
							 	}
							 	else{

							 		array_push($array_Update, $lista_ldap[$i]);
							 		$profes = $assets->findDocente(trim($lista_ldap[$i]["employeenumber"][0]));
				    	 	 		if (!$profes) {
				    	 	 			
				    	 	 			$this->DeleteGrupo($lista_ldap[$i]['distinguishedname'][0]); 
				    	 	 			$this->AddGrupoNoDocente($lista_ldap[$i]['distinguishedname'][0],trim($lista_ldap[$i]['employeenumber'][0]));	 
				    	 	 			$ldap->mover($lista_ldap[$i]['dn'], $NoDocente);	    	 	 			
				    	 	 		}
				    	 	 		if($profes){
				    	 	 			
				    	 	 			$this->DeleteGrupo($lista_ldap[$i]['distinguishedname'][0]);				
				    	 	 			$this->AddGrupoDocente($lista_ldap[$i]['distinguishedname'][0], trim($lista_ldap[$i]['employeenumber'][0]));
				    	 	 			$ldap->mover($lista_ldap[$i]['dn'], $Docente);				
				    	 	 		}		
				    	 	 		$ldap->Enable($lista_ldap[$i]['samaccountname'][0]);		    	 	 		
							 	}
							 	
		    	 	 		}
						 	
						
		    	 	 }
		    	 	catch(\Exception $e)
	        		{
	        			//$this->SendEmail($lista_ldap[$i]['displayname'][0],$lista_ldap[$i]['samaccountname'][0]);
	        			//$ldap->mover($lista_ldap[$i]['dn'], "OU=Actualizar,OU=_Usuarios,DC=upr,DC=edu,DC=cu");
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
  			/*'AdminOUCUM',
  			'AdminOUCUMVinnales',
  			'AdminOUCUMPalma',
  			'AdminOUCUMGuane',
  			'AdminOUCUMPalacios',
  			'AdminOUCUMSandino',
  			'AdminOUCUMMantua',
  			'AdminOUCUMSanLuis',
  			'AdminOUCUMSanJuan',
  			'AdminOUCUMConsolacion',
  			'AdminOUCUMMinas',  			
  			'AdminOUCeces',
  			'AdminOUVR1',
  			'AdminOUVRP',
  			'AdminOUVR2',
  			'AdminOUSoroa',
  			'AdminOUFCT',
  			'AdminOUFCFA',
  			'AdminOUFCEE',
  			'AdminOUFCSH',
  			'AdminOUFAMSA',  			  			
  			'AdminOUCRAI',
  			'AdminOURectorado', 
  			'AdminOUMarxismo',
  			'AdminOUGLOBAL',
			'AdminOUFCF',
  			'AdminOUFEI',
  			'AdminOUFEM',
  			'AdminOUDG1',
  			'AdminOUDG2',
  			'AdminOUResidencia',*/
  			'Admin-Upr',
  			/*'FAMSA-Agro1',
  			'FAMSA-Agro2',
  			'FAMSA-Agro3',
  			'FAMSA-Agro4',
  			'FAMSA-Agro5',
  			'FCE-Conta1',
  			'FCE-Conta2',
  			'FCE-Conta3',
  			'FCE-Conta4',
  			'FCE-Conta5',
  			'FCE-Eco1',
  			'FCE-Eco2',
  			'FCE-Eco3',
  			'FCE-Eco4',
  			'FCE-Eco5',
  			'FCE-Indus1',
  			'FCE-Indus2',
  			'FCE-Indus3',
  			'FCE-Indus4',
  			'FCE-Indus5',
  			'FCSH-Derecho2',
  			'FCSH-Derecho3',
  			'FCSH-Derecho4',
  			'FCSH-Derecho5',
  			'FCSH-Derecho1',
  			'FCSH-Periodismo2',
  			'FCSH-Periodismo3',
  			'FCSH-Periodismo4',
  			'FCSH-Periodismo5',
  			'FCSH-Periodismo1',
  			'FCSH-Socio2',
  			'FCSH-Socio3',
  			'FCSH-Socio4',
  			'FCSH-Socio5',
  			'FCSH-Socio1',
  			'FCT-Geo1',
  			'FCT-Geo2',
  			'FCT-Geo3',
  			'FCT-Geo4',
  			'FCT-Geo5',
  			'FCT-Info1',
  			'FCT-Info2',
  			'FCT-Info3',
  			'FCT-Info4',
  			'FCT-Info5',
  			'FCT-Meca1',
  			'FCT-Meca2',
  			'FCT-Meca3',
  			'FCT-Meca4',
  			'FCT-Meca5',
  			'FCT-Tele1',
  			'FCT-Tele2',
  			'FCT-Tele3',
  			'FCT-Tele4',
  			'FCT-Tele5',
  			'FFA-Agro1',
  			'FFA-Agro2',
  			'FFA-Agro3',
  			'FFA-Agro4',
  			'FFA-Agro5',
  			'FFA-Forestal1',
  			'FFA-Forestal2',
  			'FFA-Forestal3',
  			'FFA-Forestal4',
  			'FFA-Forestal5',*/
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
  			/*'FCF-CA1',
  			'FCF-CA2',
  			'FCF-CA3',
  			'FCF-CA4',
  			'FCF-CA5',
  			'FCF-CA6',
  			'FCF-CPE1',
  			'FCF-CPE2',
  			'FCF-CPE3',
  			'FCF-CPE4',
  			'FCF-CPE5',
  			'FCF-CPE6',*/
  			'EMedia',
  			/*'FCT-Educ-Const1',
  			'FCT-Educ-Const2',
  			'FCT-Educ-Const3',
  			'FCT-Educ-Const4',
  			'FCT-Educ-Const5',
  			'FCT-Educ-Elect1',
  			'FCT-Educ-Elect2',
  			'FCT-Educ-Elect3',
  			'FCT-Educ-Elect4',
  			'FCT-Educ-Elect5',
  			'FCT-Educ-Meca1',
  			'FCT-Educ-Meca2',
  			'FCT-Educ-Meca3',
  			'FCT-Educ-Meca4',
  			'FCT-Educ-Meca5',
  			'1ersemestre',  			
  			'FEM-BG1',
  			'FEM-BG2',
  			'FEM-BG3',
  			'FEM-BG4',
  			'FEM-BG5',
  			'FEM-BQ1',
  			'FEM-BQ2',
  			'FEM-BQ3',
  			'FEM-BQ4',
  			'FEM-BQ5',
  			'FEM-ESP1',
  			'FEI_Art1',
  			'FEM-ESP2',
  			'FEM-ESP3',
  			'FEI_Art2',
  			'FEM-ESP4',
  			'FEM-ESP5',
  			'FEM-LI1',
  			'FEM-LI2',
  			'FEM-LI3',
  			'FEM-LI4',
  			'FEM-LI5',
  			'FEM-LE1',
  			'FEM-LE2',
  			'FEM-LE3',
  			'FEM-LE4',
  			'FEM-LE5',
  			'FEM-MH1',
  			'FEM-MH2',
  			'FEM-MH3',
  			'FEI_Art3',
  			'FEM-MH4',
  			'FEM-MH5',
  			'FEM-MF1',
  			'FEM-MF2',
  			'FEI_Art4',
  			'FEM-MF3',
  			'FEM-MF4',
  			'FEM-MF5',
  			'FEI_Art5',
  			'FEI_Esp1',
  			'FEI_Esp2',
  			'FEI_Esp3',
  			'FEI_Esp4',
  			'FEI_Esp5',
  			'FEI_Log1',
  			'FEI_Log2',
  			'FEI_Log3',
  			'FEI_Log4',
  			'FEI_Log5',
  			'FEI_Ped1',
  			'FEI_Ped2',
  			'FEI_Ped3',
  			'FEI_Ped4',
  			'FEI_Ped5',
  			'FEI_Pres1',*/
  			'FEM-PROFESORES',
  			/*'FEI_Pres2',
  			'FEI_Pres3',
  			'FEI_Pres4',
  			'FEI_Pres5',
  			'FEI_Prim1',
  			'FEI_Prim2',
  			'FEI_Prim3',
  			'FEI_Prim4',
  			'FEI_Prim5',*/
  			'FEI_Dep_Art',
  			'FEI_Dep_Esp',
  			'FEI_Dep_Form',
  			'FEI_Dep_Ped',
  			'FEI_Dep_Pres',
  			'FEI_Dep_Prim',
  			'FEI_Fac',
  			'aeacp',  			
  			//'FCE-CPE-Industrial' 			 			
  			  			

    	];
	   	$ldap->deltogroup($distinguishedname, $group);	
    }

    function DeleteGrupoBaja($distinguishedname)
    {
    	$ldap = new ldap();
    	$ldap->deltogroup($distinguishedname);
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
                  
                  $list_aux= ['cn'=>$Docente[$i]['cn'][0], 'description' => $Docente[$i]['description'][0], 'physicaldeliveryofficename' => $Docente[$i]['physicaldeliveryofficename'][0]];
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
             
              Log::critical(Carbon::now()." No se puede Ver Los usuarios del Docente ".$Docente[$i]["distinguishedname"][0]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            
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
                  
                  $list_aux= ['cn'=>$estudiantes[$i]['cn'][0], 'description' => $estudiantes[$i]['description'][0]='estudiante', 'physicaldeliveryofficename' => $estudiantes[$i]['physicaldeliveryofficename'][0]='facultad'];
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
             
              Log::critical(Carbon::now()." No se puede Ver Los usuarios del estudiantes ".$estudiantes[$i]["distinguishedname"][0]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            
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
                  
                  $list_aux= ['cn'=>$NoDocente[$i]['cn'][0], 'description' => $NoDocente[$i]['description'][0], 'physicaldeliveryofficename' => $NoDocente[$i]['physicaldeliveryofficename'][0]];
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
             
              Log::critical(Carbon::now()." No se puede Ver Los usuarios del NoDocente ".$NoDocente[$i]["distinguishedname"][0]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            
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
                  
                  $list_aux= ['cn'=>$NoDocente[$i]['cn'][0], 'description' => $NoDocente[$i]['description'][0], 'physicaldeliveryofficename' => $NoDocente[$i]['physicaldeliveryofficename'][0]];
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
             
              Log::critical(Carbon::now()." No se puede Ver Los usuarios del NoDocente ".$NoDocente[$i]["distinguishedname"][0]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            
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
                  
                  $list_aux= ['cn'=>$Docente[$i]['cn'][0], 'description' => $Docente[$i]['description'][0], 'physicaldeliveryofficename' => $Docente[$i]['physicaldeliveryofficename'][0]];
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
             
              Log::critical(Carbon::now()." No se puede Ver Los usuarios del Docente ".$Docente[$i]["distinguishedname"][0]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            
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
                  
                  $list_aux= ['cn'=>$estud[$i]['cn'][0], 'description' => $estud[$i]['description'][0]='estudiante', 'physicaldeliveryofficename' => $estud[$i]['physicaldeliveryofficename'][0]='facultad'];
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
             
              Log::critical(Carbon::now()." No se puede Ver Los usuarios del OU Estudiantes ".$estud[$i]["distinguishedname"][0]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            
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
                  
                  $list_aux= ['cn'=>$ras[$i]['cn'][0], 'description' => $ras[$i]['description'][0], 'physicaldeliveryofficename' => $ras[$i]['physicaldeliveryofficename'][0]];
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
             
              Log::critical(Carbon::now()." No se puede Ver Los usuarios del RAS ".$ras[$i]["distinguishedname"][0]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            
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
                  
                  $list_aux= ['cn'=>$doctores[$i]['cn'][0], 'description' => $doctores[$i]['description'][0], 'physicaldeliveryofficename' => $doctores[$i]['physicaldeliveryofficename'][0]];
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
             
              Log::critical(Carbon::now()." No se puede Ver Los usuarios del doctores ".$doctores[$i]["distinguishedname"][0]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            
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
                  
                  $list_aux= ['cn'=>$master[$i]['cn'][0], 'description' => $master[$i]['description'][0], 'physicaldeliveryofficename' => $master[$i]['physicaldeliveryofficename'][0]];
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
             
              Log::critical(Carbon::now()." No se puede Ver Los usuarios del master ".$master[$i]["distinguishedname"][0]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            
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
                  
                  $list_aux= ['cn'=>$cuadro[$i]['cn'][0], 'description' => $cuadro[$i]['description'][0], 'physicaldeliveryofficename' => $cuadro[$i]['physicaldeliveryofficename'][0]];
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
             
              Log::critical(Carbon::now()." No se puede Ver Los usuarios del cuadro ".$cuadro[$i]["distinguishedname"][0]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            
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
                  
                  $list_aux= ['cn'=>$rector[$i]['cn'][0], 'description' => $rector[$i]['description'][0], 'physicaldeliveryofficename' => $rector[$i]['physicaldeliveryofficename'][0]];
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
             
              Log::critical(Carbon::now()." No se puede Ver Los usuarios del rector ".$rector[$i]["distinguishedname"][0]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            
          }
    }
    function OU_No_Revisar($ras)
    {     
      $exist = true;
      if($ras['distinguishedname'][0] == "")$exist = false;
      if(strstr($ras['distinguishedname'][0], '_Bajas'))$exist = false;
      if(strstr($ras['distinguishedname'][0], 'Bajas'))$exist = false;
      if(strstr($ras['distinguishedname'][0], '_UsuariosEspeciales'))$exist = false;
      if(strstr($ras['distinguishedname'][0], '_Jubilados'))$exist = false;
      if(strstr($ras['distinguishedname'][0], 'Facultades'))$exist = false;
      if(strstr($ras['distinguishedname'][0], 'No Sync'))$exist = false;
      if(strstr($ras['distinguishedname'][0], '_Postgrado'))$exist = false;
      if(strstr($ras['distinguishedname'][0], '_Institucionales'))$exist = false;

      return $exist;      

    }


}
