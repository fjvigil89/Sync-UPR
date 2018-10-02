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

class TrabajadoresController extends Controller
{
  

    function NuevoTrabajadores()
    {
    	return view('Users.nuevos');
    }
    function CrearTrabajador(Request $request)
    {
    	 $ldap = new ldap();
   		$assets = new Assets();
      $sync = new SyncController();
      $user = Array();

   		if ($assets->ExisteEmpleado($request->employeenumber)) 
   		{

   			if(!$ldap->ExisteEmpleado($request->employeenumber))
   			{
          
   				$ldap->CrearUsuario($assets->findEmpleado($request->employeenumber)[0]);
           Log::warning(" Usuario Creado ".$request->employeenumber); 

          $empleado = $assets->findEmpleado($request->employeenumber);           
          $user= $ldap->saberLdapTrabajador($request->employeenumber); 
          $sync->Actualizar_Usuarios_Upr($empleado[0], $user[0]);
   			}
        else {
   			  return 'El Usuario existe en la Universidad';
        }
   		}
      else{
   		 return "El Trabajador no pertenece a la Universidad";
      }
      //$user= $ldap->saberLdapTrabajador($request->employeenumber);
      
      $result = "<h1>El usuario ".$user[0]['cn'][0]." ya es parte de nuestros servicios</h1>";
      return $result;
    	
    }

    //item es la unidad a actualizar (ADD(11 am) - REMOVE(2 pm))
    public function GrupoPassword(Request $request, $item) 
    {
       
       $ldap = new ldap();
       $assets = new Assets;
              
       $lista_ldap = ['elio.govea','irlenys.ibarra','luis.mendez','luis.junco','manuel.diaz','ysantalla','osmay']; 
       $group= array();
           
            for ($i=1; $i < count($lista_ldap) ; $i++) { 
              try{              
                     
                   $users = $ldap->find_users($lista_ldap[$i]);
                   
                   if ($item=='ADD') {
                     $this->AddGrupoPassword($users[0]['distinguishedname'][0]);
                     Log::warning(" Adicionando ".$users[0]["distinguishedname"][0]." a grupos de P@ssword:");
                   }
                   if ($item=='REMOVE') {
                     $this->RemoveGrupo($users[0]['distinguishedname'][0]);
                    Log::warning(" Eliminando ".$users[0]["distinguishedname"][0]." de grupos de P@ssword:"); 
                   }
                   
              
               }//try
              catch(\Exception $e)
                {                 
                  Log::critical(" No se puede actualizar al empleado ".$lista_ldap[$i]." del AD:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
                 //array_push($array_NoUpdate, $lista_ldap[$i]);              
                
              }

            }           
    }

    function AddGrupoPassword($distinguishedname)
    {
      $ldap = new ldap();
      $assets = new Assets;

      //grupos que se les adicionar'an al usuario 
      $group= [        
        'UPR-Gestion-Usuarios',
        'UPR-Gestion-Password'

      ];    

      //foreach ($assets->SaberGrupo($idEmployeed) as $value) {
      //  array_push($group, $value);
      //}

      $ldap->addtogroup($distinguishedname, $group);
    }

    function RemoveGrupo($distinguishedname)
    {
      $ldap = new ldap();

      //grupos que se quiere que no se le sean eliminado al usuario      
      $group= [        
        'UPR-Gestion-Usuarios',
        'UPR-Gestion-Password'

      ];    
      $ldap->deltogroupEspecifico($distinguishedname, $group);  
    }
}
