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
class ApiController extends Controller
{
    //
    function AuthLdap(Request $request)
    {
    	$ldap= new Ldap();
    	$login =$ldap->Auth($request->user, $request->password);
    	if ($login) {

    		Log::alert(" Se logueo el usuario". $request->user ." de la UPR ");

    		$attrib = explode(',', $request->attrib);    		
    	  	$data= $ldap->Info($request->user, $request->password, $attrib);       	  	
    	  	return JsonResponse::create($data, 200, array('Content-Type'=>'application/json; charset=utf-8' ));
          	

       }
       Log::alert(" Fallo al loguear el usuario". $request->user.'con el pass'.$request->password ." de la UPR ");
       return response()->json($login,404);
    }


    function thumbnailphoto($samaccountname)
    {   	

    	 $ldap = new ldap();
    	 return $ldap->thumbnailphoto($samaccountname);     	 

    }
}
