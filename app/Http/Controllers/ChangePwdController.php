<?php

namespace changePwd\Http\Controllers;

use Illuminate\Http\Request;
use changePwd\ldap;
class ChangePwdController extends Controller
{
   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function change(Request $request)
    {
        $ldap = new ldap();
        $username = $request->username;
        $passwd = $request->passwd;
        $newpasswd = $request->newpasswd;
        $repitNewpasswd = $request->repetir_password;
        if(!$ldap->Exist($username))
        {
            return "El usuario no existe en el directorio de la Universidad de Pinar del Río";
        }
        
        if(!$ldap->changePassword($username,$passwd,$newpasswd,$repitNewpasswd))
        {
            return $ldap->GetMessage();
        }

        return "La contraseñas ha sido cambiada Exitosamente";

    }

    
}
