<?php

namespace Sync\Http\Controllers;

use Illuminate\Http\Request;
use Sync\ldap;
use Sync\Assets;
use Log;
use Carbon\Carbon;
use Collection;
use Mail;
class ChangePwdController extends Controller
{
   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function change(Request $request, $assamacount)
    {

        $ldap = new ldap();        
        return "La contraseñas ha sido cambiada Exitosamente";

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function habilitar(Request $request, $assamacount)
    {
        
        $ldap = new ldap();
        $ldap->Enable($assamacount);
        return "el usuario $assamacount ha sido Habilitado Exitosamente";

    }

        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deshabilitar(Request $request, $assamacount)
    {
        
        $ldap = new ldap();
        $ldap->Disable($assamacount);
        return "el usuario $assamacount ha sido Habilitado Exitosamente";

    }
    
}
