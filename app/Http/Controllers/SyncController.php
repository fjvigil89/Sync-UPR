<?php

namespace Sync\Http\Controllers;

use Illuminate\Http\Request;
use Sync\ldap;
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
    	 $ldap = new ldap();
    	 return $ldap->saberLdap();
    	
    }
}
