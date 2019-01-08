<?php

namespace Sync;

use Illuminate\Database\Eloquent\Model;
use Log;
use Collection;
use Carbon\Carbon;
use Sync\Assets;
class ldap extends Model
{
    //
    private $ldapserver = 'ad.upr.edu.cu';
	private	$ldapuser = "Administrator";  
	private	$ldappass= "mistake*ad.20";
    private $use_ldap=true;
    private $ldap_dn="DC=upr,DC=edu,DC=cu";
    private $ldap_usr_dom="@upr.edu.cu";
    private $ldap_host="ldap://10.2.24.35";
    private $message = array(); 

    

    public function  GetMessage()
    {
    	return $this->message;
    }

     function isLdapUser($username,$password,$ldap){
        try{    
            //$ldap = ldap_connect($this->ldap_host,389);    

            ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
            ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);
     
            $ldapBind= ldap_bind($ldap, $username. $this->ldap_usr_dom, $password);            
            //ldap_unbind($ldap);   

            return true; 
          }
        catch(\Exception $e)
        {
            Log::critical("Problemas con la Authentication del LDAP:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return false;
        }
     }
    
    function Auth($username, $password, $adGroupName = false){        
            
        $ldap = ldap_connect($this->ldap_host);
        if (!$ldap)
            throw new Exception("Cant connect ldap server", 1);
        

        return $this->isLdapUser($username, $password, $ldap);     
        
    }

    function Info($username, $password, $attrib, $adGroupName = false){
        try{
	        global $ldap_host,$ldap_dn;
	            
	        $ldap = ldap_connect($this->ldap_host);
	        if (!$ldap)
	            throw new Exception("Cant connect ldap server", 1);
	        
	       if($this->isLdapUser($username, $password, $ldap)){  
	            
	            $results = ldap_search($ldap,$this->ldap_dn,'(sAMAccountName=' . $username . ')',$attrib);
	      
	            $user_data = ldap_get_entries($ldap, $results);
	            
	              
	            return $user_data;
	        }

    	}
    	catch(\Exception $e)
        {
            Log::critical("Problemas con la Info del LDAP:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return false;
        }
        
    }

    function Exist($username){
    	try{
	        global $ldap_host,$ldap_dn,$ldap_usr_dom;
	        
	        $exist = true;  
	        $ldap = ldap_connect($this->ldap_host);
	        if (!$ldap)
	            throw new Exception("Cant connect ldap server", 1);
	            
	        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
	        ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);     
	        
	        $ldapBind= ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass);
	        
	        $attrib = array('distinguishedname');           
	          //isLdapUser($username, $password, $ldap);    
	               
	        $results = ldap_search($ldap,$this->ldap_dn,'(sAMAccountName=' . $username . ')',$attrib);  
	        $user_data = ldap_get_entries($ldap, $results);
	            
	        if($user_data[0]['distinguishedname'][0] == "")$exist = false;  
	        if(strstr($user_data[0]['distinguishedname'][0], '_Bajas')) $exist = false;
	        
	        return $exist;
    	}
       catch(\Exception $e)
        {
            Log::critical("No se puede Cambiar el Password:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return false;//response("Alguna cosa esta mal", 500);
        }

    }

    function find_users($username){
    	try{
	        global $ldap_host,$ldap_dn,$ldap_usr_dom;
	        
	        $exist = true;  
	        $ldap = ldap_connect($this->ldap_host);
	        if (!$ldap)
	            throw new Exception("Cant connect ldap server", 1);
	            
	        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
	        ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);     
	        
	        $ldapBind= ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass);
	        
	        $attrib = array('distinguishedname');           

	        $filter='(&(!(useraccountcontrol=514))(samaccountname='.$username.'))';
	            

	        $results = @ldap_search($ldap,$this->ldap_dn,$filter,$attrib);  

	        $user_data = @ldap_get_entries($ldap, $results);
	            
	        //if($user_data[0]['distinguishedname'][0] == "")$exist = false;  
	        //if(strstr($user_data[0]['distinguishedname'][0], '_Bajas')) $exist = false;
	        //dd($user_data);
	        return $user_data;
    	}
       catch(\Exception $e)
        {
            Log::critical("No se puede Cambiar el Password:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return response("Alguna cosa esta mal", 500);
        }

    }

    function find_users_CI($userCi){
    	try{
	        global $ldap_host,$ldap_dn,$ldap_usr_dom;
	        
	        $exist = true;  
	        $ldap = ldap_connect($this->ldap_host);
	        if (!$ldap)
	            throw new Exception("Cant connect ldap server", 1);
	            
	        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
	        ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);     
	        
	        $ldapBind= ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass);
	        
	        $attrib = array('thumbnailphoto','telephonenumber','streetaddress','sn','physicaldeliveryofficename','name','mail','jpegphoto','employeenumber','employeeid','distinguishedname','displayname','description','department','cn','samaccountname', 'givenname');           

	        $filter='(&(!(useraccountcontrol=514))(employeeid='.$userCi.'))';
	            

	        $results = @ldap_search($ldap,$this->ldap_dn,$filter,$attrib);  

	        $user_data = @ldap_get_entries($ldap, $results);
	            
	        //if($user_data[0]['distinguishedname'][0] == "")$exist = false;  
	        //if(strstr($user_data[0]['distinguishedname'][0], '_Bajas')) $exist = false;
	        //dd($user_data);
	        return $user_data;
    	}
       catch(\Exception $e)
        {
            Log::critical("No se puede Cambiar el Password:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return response("Alguna cosa esta mal", 500);
        }

    }



    function ExistCI($ci){
    	try{
	        global $ldap_host,$ldap_dn,$ldap_usr_dom;
	        
	        $exist = true;  
	        $ldap = ldap_connect($this->ldap_host);
	        if (!$ldap)
	            throw new Exception("Cant connect ldap server", 1);
	            
	        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
	        ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);     
	        
	        $ldapBind= ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass);
	        
	        $attrib = array('distinguishedname');           
	          //isLdapUser($username, $password, $ldap);    
	               
	        $results = ldap_search($ldap,$this->ldap_dn,'(employeeid=' . $ci . ')',$attrib);  
	        $user_data = ldap_get_entries($ldap, $results);
	            
	        if($user_data[0]['distinguishedname'][0] == "")$exist = false;  
	        if(strstr($user_data[0]['distinguishedname'][0], '_Bajas')) $exist = false;
	        
	        return $exist;
    	}
       catch(\Exception $e)
        {
            Log::critical("No se puede Cambiar el Password:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return false;//response("Alguna cosa esta mal", 500);
        }

    }
	///permite saber si un usuario esta en el ldap por su numero de empleado
	///esto se usa para crear un usuario nuevo desde el sistema
    function ExisteEmpleado($employeeid){
    	try{
    		 //global $ldap_host,$ldap_dn,$ldap_usr_dom;
	        $exist = true;  
	        $ldap = ldap_connect($this->ldap_host);
	        if (!$ldap)
	            throw new Exception("Cant connect ldap server", 1);
	            
	        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
	        ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);     
	        
	        $ldapBind= ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass);
	        
	        $attrib = array('distinguishedname');           
	          //isLdapUser($username, $password, $ldap);    
	               
	        $results = ldap_search($ldap,$this->ldap_dn,'(employeeNumber=' . $employeeid . ')',$attrib);  
	        $user_data = ldap_get_entries($ldap, $results);

	        if($user_data['count'] == 0){$exist = false;}  	        
	        
	        return $exist;
    	}
       catch(\Exception $e)
        {
            Log::critical("No se puede Cambiar el Password:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return false;//response("Alguna cosa esta mal", 500);
        }
    }

    function ExisteEmpleado_Ci($employeeid){
    	try{
	        $exist = true;  
	        $ldap = ldap_connect($this->ldap_host);
	        if (!$ldap)
	            throw new Exception("Cant connect ldap server", 1);
	            
	        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
	        ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);     
	        
	        $ldapBind= ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass);
	        
	        $attrib = array('distinguishedname');           
	          //isLdapUser($username, $password, $ldap);    
	               
	        $results = ldap_search($ldap,$this->ldap_dn,'(employeeid=' . $employeeid . ')',$attrib);  
	        $user_data = ldap_get_entries($ldap, $results);

	        if($user_data['count'] == 0){$exist = false;}  	        
	        
	        return $exist;
    	}
       catch(\Exception $e)
        {
            Log::critical("No se puede Cambiar el Password:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return false;//response("Alguna cosa esta mal", 500);
        }
    }

    function ExistUsuario($employeenumber){
    	try{
	        global $ldap_host,$ldap_dn,$ldap_usr_dom;
	        
	        $exist = true;  
	        $ldap = ldap_connect($this->ldap_host);
	        if (!$ldap)
	            throw new Exception("Cant connect ldap server", 1);
	            
	        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
	        ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);     
	        
	        $ldapBind= ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass);
	        
	        $attrib = array('distinguishedname');           
	          //isLdapUser($username, $password, $ldap);    
	               
	        $results = ldap_search($ldap,$this->ldap_dn,'(employeenumber=' . $employeenumber . ')',$attrib);  
	        $user_data = ldap_get_entries($ldap, $results);
	            
	        if($user_data[0]['distinguishedname'][0] == "")$exist = false;  
	        //if(strstr($user_data[0]['distinguishedname'][0], '_Bajas')) $exist = false;
	        
	        return $exist;
    	}
       catch(\Exception $e)
        {
            Log::critical("No se puede Cambiar el Password:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return false;//response("Alguna cosa esta mal", 500);
        }

    }

	function changePassword($user,$oldPassword,$newPassword,$newPasswordCnf){
		  global $message;
		  global $message_css;
		    
		  error_reporting(0);
		  
		  
		  $ldap = ldap_connect($this->ldap_host,389);
		  if (!$ldap)
	            throw new Exception("Cant connect ldap server", 1);
	            
          ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
          ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);  
		  
		  $ldapBind= @ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass)or die("<br>Error: Couldn't bind to server using supplied credentials!"); 
		  
		  // bind anon and find user by uid
		   $attrib = array("*", "ou",'distinguishedname','mail', 'uid', "passwordretrycount", "passwordhistory", "samaccountname");           
	          //isLdapUser($username, $password, $ldap);    
	               
	      //$user_search = ldap_search($ldap,$this->ldap_dn,'(sAMAccountName=' . $user . ')',$attrib);  
		  $user_search = @ldap_search($ldap,$this->ldap_dn,"(|(samaccountname=$user)(mail=$user))");
		  $user_get = @ldap_get_entries($ldap, $user_search);
		  $user_entry = @ldap_first_entry($ldap, $user_search);
		  $user_dn = @ldap_get_dn($ldap, $user_entry);
		  $user_id = $user_get[0]["samaccountname"][0];
		  $user_givenName = $user_get[0]["givenname"][0];
		  $user_search_arry = $attrib;
		  $user_search_filter = "(|(samaccountname=$user)(mail=$user))";
		  $user_search_opt = @ldap_search($ldap,$user_dn,$user_search_filter,$user_search_arry);
		  $user_get_opt = @ldap_get_entries($ldap, $user_search_opt);
		  $passwordRetryCount = $user_get_opt[0]["passwordretrycount"][0];
		  $passwordhistory = $user_get_opt[0]["passwordhistory"][0];
		  


		  //$message[] = "Username: " . $user_id;
		  //$message[] = "DN: " . $user_dn;
		  //$message[] = "Current Pass: " . $oldPassword;
		  //$message[] = "New Pass: " . $newPassword;
		   
		  /* Start the testing */
		  if ( $passwordRetryCount == 3 ) {
		    $message[] = "Error E101 - Your Account is Locked Out!!!";
		    return false;
		  }
		  if (@ldap_bind($ldap, $user_dn, $oldPassword) === false) {
		    $message[] = "Error E101 - Current Username or Password is wrong.";
		    return false;
		  }
		  if ($newPassword != $newPasswordCnf ) {
		    $message[] = "Error E102 - Your New passwords do not match!";
		    return false;
		  }

		  $encoded_newPassword = "{SHA}" . base64_encode( pack( "H*", sha1( $newPassword ) ) );

		  $history_arr = @ldap_get_values($ldap,$user_dn,"passwordhistory");

		  if ( $history_arr ) {
		    $message[] = "Error E102 - Your new password matches one of the last 10 passwords that you used, you MUST come up with a new password.";
		    return false;
		  }

		  if (strlen($newPassword) < 8 ) {
		    $message[] = "Error E103 - Your new password is too short.<br/>Your password must be at least 8 characters long.";
		    return false;
		  }

		  if (!preg_match("/[0-9]/",$newPassword)) {
		    $message[] = "Error E104 - Your new password must contain at least one number.";
		    return false;
		  }

		  if (!preg_match("/[a-zA-Z]/",$newPassword)) {
		    $message[] = "Error E105 - Your new password must contain at least one letter.";
		    return false;
		  }

		  if (!preg_match("/[A-Z]/",$newPassword)) {
		    $message[] = "Error E106 - Your new password must contain at least one uppercase letter.";
		    return false;
		  }


		  if (!preg_match("/[a-z]/",$newPassword)) {
		    $message[] = "Error E107 - Your new password must contain at least one lowercase letter.";
		    return false;
		  }

		  if (!$user_get) {
		    $message[] = "Error E200 - Unable to connect to server, you may not change your password at this time, sorry.";
		    return false;
		  }
		  
		  $auth_entry = @ldap_first_entry($ldap, $user_search);
		  $mail_addresses = @ldap_get_values($ldap, $auth_entry, "mail");
		  $given_names = @ldap_get_values($ldap, $auth_entry, "givenName");
		  $password_history = @ldap_get_values($ldap, $auth_entry, "passwordhistory");
		  $mail_address = $mail_addresses[0];
		  $first_name = $given_names[0];
		   

		   
		  /* And Finally, Change the password */
		  $entry = array();
		  $entry["userpassword"] = "$encoded_newPassword";
		  
		  	$newPassword1 = "\"" . $newPassword . "\"";
		    $len = strlen($newPassword1);
		    $newPassw = "";
		    for($i=0;$i<$len;$i++) {
		        $newPassw .= "{$newPassword1{$i}}\000";
		    }
		   	$encodedPass = array('userpassword' =>base64_encode($newPassw)); 
		    //dd($ldap);
		   	//ldap_modify($ldap, $userDn, array('unicodePwd' => $newPass));

		  if (!@ldap_modify($ldap,$user_dn,$encodedPass)){
		    $error = @ldap_error($ldap);
		    $errno = @ldap_errno($ldap);
		    $message[] = "E201 - Your password cannot be change, please contact the administrator.";
		    $message[] = "$errno - $error";
		  } else {
		    $message_css = "yes";	    
		    $message[] = "The password for $user_id has been changed.<br/>An informational email as been sent to $mail_address.<br/>Your new password is now fully Active.";
		        mail($mail_address,"Password change notice","Dear $first_name,
					Your password on http://support.example.com for account $user_id was just changed. If you did not make this change, please contact support@example.com.
					If you were the one who changed your password, you may disregard this message. 
					Thanks-Matt");
		  }

		   dd($message);

	}

	function saberLdap($item){
		try{
			 $ldap_dn_GrupoRedes='';
			 
			 if($item == 'Docentes')
			 {
			 	$ldap_dn_GrupoRedes= "OU=Trabajador Docente,OU=_Usuarios,DC=upr,DC=edu,DC=cu";
			 }
			 if($item == 'NoDocentes')
			 {
			 	$ldap_dn_GrupoRedes= "OU=Trabajador NoDocente,OU=_Usuarios,DC=upr,DC=edu,DC=cu";
			 }
			 if($item == 'Estudiantes')
			 {
			 	$ldap_dn_GrupoRedes= "OU=Estudiantes,OU=_Usuarios,DC=upr,DC=edu,DC=cu";
			 }
			 if($item == 'Bajas')
			 {
			 	$ldap_dn_GrupoRedes= "OU=Bajas,OU=_Usuarios,DC=upr,DC=edu,DC=cu";
			 }
			 if ($item == 'Actualizar') {
			 	# code...
			 	$ldap_dn_GrupoRedes="OU=Actualizar,OU=_Usuarios,DC=upr,DC=edu,DC=cu";
			 }
			 if ($item == 'test') {
			 	# code...
			 	$ldap_dn_GrupoRedes="OU=test,OU=_Usuarios,DC=upr,DC=edu,DC=cu";
			 	//$ldap_dn_GrupoRedes="OU=Actualizar,OU=_Usuarios,DC=upr,DC=edu,DC=cu";
			 }
			 
			 $ldap = ldap_connect($this->ldap_host,389);
		  	 
		  	 if (!$ldap)
	            throw new Exception("Cant connect ldap server", 1);
	            
	          ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
	          ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);  

	         $ldapBind= @ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass)or die("<br>Error: Couldn't bind to server using supplied credentials!"); 
		 
	         $attrib = array('thumbnailphoto','telephonenumber','streetaddress','sn','physicaldeliveryofficename','name','mail','jpegphoto','employeenumber','employeeid','distinguishedname','displayname','description','department','cn','samaccountname', 'givenname');

	        $filter='(&(samaccountname=*)(objectClass=user))';
	        $results = @ldap_search($ldap,$ldap_dn_GrupoRedes,$filter,$attrib);  
		    $user_data = @ldap_get_entries($ldap, $results);
	    
	    	

		    return $user_data;

	    }
       catch(\Exception $e)
        {
            Log::critical("No se puede acceder a los usuarios:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return response("Alguna cosa esta mal", 500);
        }

	}

	function saberLdapTrabajador($employeenumber){
		try{
			 
			 $ldap = ldap_connect($this->ldap_host,389);
		  	 
		  	 if (!$ldap)
	            throw new Exception("Cant connect ldap server", 1);
	            
	          ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
	          ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);  

	         $ldapBind= @ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass)or die("<br>Error: Couldn't bind to server using supplied credentials!"); 
		 
	         $attrib = array('thumbnailphoto','telephonenumber','streetaddress','sn','physicaldeliveryofficename','name','mail','jpegphoto','employeenumber','employeeid','distinguishedname','displayname','description','department','cn','samaccountname', 'givenname');

	        $filter="employeenumber=".$employeenumber;
	        $results = @ldap_search($ldap,$this->ldap_dn,$filter,$attrib);  
		    $user_data = @ldap_get_entries($ldap, $results);

		    return $user_data;

	    }
       catch(\Exception $e)
        {
            Log::critical("No se puede acceder a los usuarios:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return response("Alguna cosa esta mal", 500);
        }

	}

	function ActualizarCamposIdEmpleado($empleado, $departamento, $cargo, $username){
		  try{
			  global $message;
			  global $message_css;
			    
			  error_reporting(0);
				$ldap = ldap_connect($this->ldap_host,389);
			  if (!$ldap)
		            throw new Exception("Cant connect ldap server", 1);
		            
	          ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
	          ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);  
			  
			  $ldapBind= @ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass)or die("<br>Error: Couldn't bind to server using supplied credentials!"); 
			  
			  // bind anon and find user by uid
			    $attrib = array('unicodepwd','cn','thumbnailphoto','telephonenumber','streetaddress','sn','physicaldeliveryofficename','name','mail','jpegphoto','employeenumber','employeeid','distinguishedname','displayname','description','department','cn','samaccountname', 'givenname'); 
	       

		        $results = @ldap_search($ldap,$this->ldap_dn,'(|(employeenumber='.trim($empleado['idExpediente']).')(employeeid='.trim($empleado['noCi']).')(samaccountname='.$username.'))',$attrib); 		        

		        

			    $user_data = ldap_get_entries($ldap, $results);
			  	$user_entry = @ldap_first_entry($ldap, $results);
			  	$user_dn = @ldap_get_dn($ldap, $user_entry);
	 			$user_id = $user_data[0]["samaccountname"][0];
	       		$nek = $this->normaliza($user_id);	       		
	       		if ($empleado['telefonoParticular'] == "") {
					$phone= "No tiene";
				}
				else{

			    	 $phone=explode(",", $empleado['telefonoParticular'])[0];		
			    	} 

	            $entry = array(
			    'streetAddress' =>html_entity_decode(trim(ucwords(strtolower(  $empleado['direccion'])))),
			    'givenname' => html_entity_decode(trim(ucwords(strtolower($empleado['nombre'])))),
			    'sn' => html_entity_decode(trim(ucwords(strtolower($empleado['apellido1']).' '.strtolower($empleado['apellido2'])))),
			    //'employeenumber'=> $empleado['idExpediente'],
			    'telephoneNumber'=>$phone,
			    'mail' => $nek.'@upr.edu.cu',
			    'employeeid'=> trim($empleado['noCi']),	
			    'physicaldeliveryofficename' => html_entity_decode(trim(ucwords(strtolower($departamento)))),
			    'description'=>html_entity_decode(ucwords(strtolower($cargo))),			    
			    );
			    //dd($entry);
			    
			    if (!@ldap_mod_replace($ldap,$user_dn,$entry)){
				    $error = @ldap_error($ldap);
				    $errno = @ldap_errno($ldap);
				    $message[] = "E201 - Your user cannot be change, please contact the administrator.";
				    $message[] = "$errno - $error";
			  	}
			  	else {
				    $message_css = "yes";	    
				    $message[] = "The change for $user_id has been used $entry[givenname].";
			  	}

			  	//dd($message);
			  	return true;
		  	}
		  	catch(\Exception $e)
        	{
            	Log::critical("No se puede acceder al empleado del Assets:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
		  		return false;
		  	}
		  	
	}

	function normaliza ($cadena){
	    $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
	    $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
	    $cadena = utf8_decode($cadena);
	    $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
	    $cadena = strtolower($cadena);
	    return utf8_encode($cadena);
	}

	function ActualizarCamposStudent($empleado, $departamento, $cargo, $username){

		  try{
			  global $message;
			  global $message_css;
			    
			  error_reporting(0);
				$ldap = ldap_connect($this->ldap_host,389);
			  if (!$ldap)
		            throw new Exception("Cant connect ldap server", 1);
		            
	          ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
	          ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);  
			  
			  $ldapBind= @ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass)or die("<br>Error: Couldn't bind to server using supplied credentials!"); 
			  
			  // bind anon and find user by uid
			    $attrib = array('unicodepwd','cn','thumbnailphoto','telephonenumber','streetaddress','sn','physicaldeliveryofficename','name','mail','jpegphoto','employeenumber','employeeid','distinguishedname','displayname','description','department','cn','samaccountname', 'givenname'); 
	       
			    
		        $results = @ldap_search($ldap,$this->ldap_dn,'(|(employeenumber='.trim($empleado['id_student']).')(employeeid='.trim($empleado['identification']).')(samaccountname='.$username.'))',$attrib); 		        

		        

			    $user_data = ldap_get_entries($ldap, $results);
			  	$user_entry = @ldap_first_entry($ldap, $results);
			  	$user_dn = @ldap_get_dn($ldap, $user_entry);
	 			$user_id = $user_data[0]["samaccountname"][0];
	       		
	       		if ($empleado['phone'] == "") {
					$phone= "No tiene";
				}
				else{
			    	 $phone=$empleado['phone'];		
			    	} 

	            $entry = array(
			    'streetAddress' =>html_entity_decode(trim(ucwords(strtolower(  $empleado['address'])))),
			    'givenname' => html_entity_decode(trim(ucwords(strtolower($empleado['name'])))),
			    'sn' => html_entity_decode(trim(ucwords(strtolower($empleado['middle_name']).' '.strtolower($empleado['last_name'])))),
			    //'employeenumber'=> $empleado['id_student'],
			    //'telephoneNumber'=>$phone,			    	
			    'employeeid'=> $empleado['identification'],	
			    'physicaldeliveryofficename' => html_entity_decode(trim(ucwords(strtolower($departamento)))),
			    'description'=>html_entity_decode(ucwords($cargo)),			    
			    );
			    
			    
			    if (!@ldap_mod_replace($ldap,$user_dn,$entry)){
				    $error = @ldap_error($ldap);
				    $errno = @ldap_errno($ldap);
				    $message[] = "E201 - Your user cannot be change, please contact the administrator.";
				    $message[] = "$errno - $error";
			  	}
			  	else {
				    $message_css = "yes";	    
				    $message[] = "The change for $user_id has been used $entry[givenname].";
			  	}

			  	
			  	return true;
		  	}
		  	catch(\Exception $e)
        	{
            	Log::critical("No se puede acceder al usuario del Assets:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
		  		return false;
		  	}
		  	
	}

	function thumbnailphoto($samaccountname)
	{
		 try{
			  global $message;
			  global $message_css;
			  
			  $exist = true;
			  $thumbnailphoto="";
			  error_reporting(0);
				$ldap = ldap_connect($this->ldap_host,389);
			  if (!$ldap)
		            throw new Exception("Cant connect ldap server", 1);
		            
	          ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
	          ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);  
			  
			  $ldapBind= @ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass)or die("<br>Error: Couldn't bind to server using supplied credentials!"); 
			  
			  // bind anon and find user by uid
			    $attrib = array('unicodepwd','cn','thumbnailphoto','telephonenumber','streetaddress','sn','physicaldeliveryofficename','name','mail','jpegphoto','employeenumber','employeeid','distinguishedname','displayname','description','department','cn','samaccountname', 'givenname'); 
	       

		        $results = @ldap_search($ldap,$this->ldap_dn,'(samaccountname='.trim($samaccountname).')',$attrib); 

			    $user_data = @ldap_get_entries($ldap, $results);
			  	$user_entry = @ldap_first_entry($ldap, $results);
			  	$user_dn = @ldap_get_dn($ldap, $user_entry);

			  	if($user_data[0]['distinguishedname'][0] == "")$exist = false;  
	        	if(strstr($user_data[0]['distinguishedname'][0], '_Bajas')) $exist = false;

	 			if (!$exist) {

	 				// Nombre de la imagen					
					$path = public_path() . "\images\intranetclassic.png";
					 
					// Extensión de la imagen
					$type = pathinfo($path, PATHINFO_EXTENSION);
					 
					// Cargando la imagen
					$data = file_get_contents($path);
					 
					// Decodificando la imagen en base64
					//$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

	 				return $data;
	 			}
	 			
	 			$thumbnailphoto = $user_data[0]["thumbnailphoto"][0];
	 			
			  	return $thumbnailphoto;
		  	}
		  	catch(\Exception $e)
        	{
            	Log::critical("No se puede acceder al empleado del Assets:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
		  		return false;
		  	}
		  	//dd($message);
	}
 
 	function mover($distinguishedName, $newDistinguishedName)
 	{

 		$ldap = ldap_connect($this->ldap_host,389);
		  if (!$ldap)
	            throw new Exception("Cant connect ldap server", 1);
	            
	    ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
	    ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);  

	    list($newRdn, $null) = explode(',', $distinguishedName, 2);

	    $ldapBind= @ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass)or die("<br>Error: Couldn't bind to server using supplied credentials!"); 
			  
			  // bind anon and find user by uid
	    $attrib = array('unicodepwd','cn','thumbnailphoto','telephonenumber','streetaddress','sn','physicaldeliveryofficename','name','mail','jpegphoto','employeenumber','employeeid','distinguishedname','displayname','description','department','cn','samaccountname', 'givenname'); 
   

        $results = @ldap_search($ldap,$this->ldap_dn,'(samaccountname='.trim($samaccountname).')',$attrib); 

	    $user_data = @ldap_get_entries($ldap, $results);
	  	$user_entry = @ldap_first_entry($ldap, $results);
	  	$user_dn = @ldap_get_dn($ldap, $user_entry);

        $res = @ldap_rename($ldap, $distinguishedName, $newRdn, $newDistinguishedName, true);        
        if (!$res)  {        	
    		print '|ERROR: '.ldap_error($ldap);
		}

 	}

 	function addtogroup($distinguishedname, $groupname) { 

 		$ldap = ldap_connect($this->ldap_host);
        if (!$ldap)
            throw new Exception("Cant connect ldap server", 1);
        
      

        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
        ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);     
          


        $ldapBind= @ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass);
        
        $attrib = array('cn','distinguishedName'); 
        
	  foreach ($groupname as $item) {
	  	
	  	$results = @ldap_search($ldap,$this->ldap_dn,'(cn='.trim($item).')',$attrib);
        $user_data = @ldap_get_entries($ldap, $results);

	  	$dn = $user_data[0]['dn'];

	  	$addme["member"] = $distinguishedname;
	  	$res = @ldap_mod_add($ldap, $dn, $addme);
	  	$errstr = '';
	  	if (!$res) {
	    	$errstr .= ldap_error($ldap);		    
	 	}
	  }	  
		  	
	}

	//funci'on que elimina los grupos asociados a los usuarios 
	//revive direccion del usuario dentro del ldap
	//recive grupos que se desean no eliminar del usuario
	function deltogroup($distinguishedname, $groupname = null) { 
		set_time_limit(0);
		//conexi'on con el ldap de la UPR
 		$ldap = ldap_connect($this->ldap_host);
        if (!$ldap)
            throw new Exception("Cant connect ldap server", 1);

        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
        ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);   

        //Construcci'on del objeto de la conexi'on del ldap de la UPR
        $ldapBind= @ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass);
        
        //atributos que quiero que me debuelva la consulta
        $attrib = array('cn','distinguishedName','objectcategory'); 

        //resultado de la consulta al ldap pasandole el filtro para los grupos
        $results = @ldap_search($ldap,$this->ldap_dn,'(	objectcategory=CN=Group,CN=Schema,CN=Configuration,DC=upr,DC=edu,DC=cu)',$attrib);

        //listado de todos los grupos
        $user_data = @ldap_get_entries($ldap, $results);
        
      for ($i=0; $i <$user_data['count'] ; $i++) 
      { 

      	$exist = true;
      	$igual = false;
      	$errstr = "";

      	//para descartar grupos de distintas Unidades Organizativas
	    if(strstr($user_data[$i]['distinguishedname'][0], 'Builtin')) $exist = false;
	    if(strstr($user_data[$i]['distinguishedname'][0], 'Users')) $exist = false;

	    if ($exist) 
	    	{
		    	$dn = $user_data[$i]['dn'];			    	 	
		    	//comparaci'on de los grupos de la UPR con los grupos que no se desean eliminar al usuario	    	
		    	if($groupname != null)
		    	{
			    	foreach ($groupname as $value) {
			    		$grourNoDeleter = @ldap_search($ldap,$this->ldap_dn,'(cn='.trim($value).')',$attrib);		    		
				        $group_data = @ldap_get_entries($ldap, $grourNoDeleter);
					  	if($dn == $group_data[0]['dn']){$igual = true;}
			    	}
		    	}	    	
		    	if (!$igual) {
				  	$addme["member"] = $distinguishedname;			  	
				  	$res = @ldap_mod_del($ldap, $dn, $addme);
				  	if (!$res) {
				    	$errstr .= ldap_error($ldap);		    
				 	}	
		    	}
			  	
	    	}

	  	
	  	}  	
	
	}
	//Elimina los grupos por a loa usuarios, todos los grupos que se manden
	function deltogroupEspecifico($distinguishedname, $groupname = null) { 
		set_time_limit(0);
		$ldap = ldap_connect($this->ldap_host);
        if (!$ldap)
            throw new Exception("Cant connect ldap server", 1);
        
      

        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
        ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);     
          


        $ldapBind= @ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass);
        
        $attrib = array('cn','distinguishedName');         	  
	  	$results = @ldap_search($ldap,$this->ldap_dn,'(cn='.trim($groupname).')',$attrib);
        $user_data = @ldap_get_entries($ldap, $results);        
	  	$dn = $user_data[0]['dn'];
	  	$addme["member"] = $distinguishedname;
	  	$res = @ldap_mod_del($ldap, $dn, $addme);
	  	$errstr = '';
	  	if (!$res) {
	    	$errstr .= ldap_error($ldap);		    
	 	}
	 	  
	
	}//end deltogroupEspecifico

	function Disable($samaccountname)
 	{

 		$ldap = ldap_connect($this->ldap_host,389);
		  if (!$ldap)
	            throw new Exception("Cant connect ldap server", 1);
	            
	    ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
	    ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);  
	    

	    $ldapBind= @ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass)or die("<br>Error: Couldn't bind to server using supplied credentials!"); 
			  
			  // bind anon and find user by uid
	    $attrib = array('unicodepwd','cn','thumbnailphoto','telephonenumber','streetaddress','sn','physicaldeliveryofficename','name','mail','jpegphoto','employeenumber','employeeid','distinguishedname','displayname','description','department','cn','samaccountname', 'givenname','useraccountcontrol'); 
   

        $results = @ldap_search($ldap,$this->ldap_dn,'(samaccountname='.trim($samaccountname).')',$attrib); 

		
	    $user_data = @ldap_get_entries($ldap, $results);
	  	$user_entry = @ldap_first_entry($ldap, $results);
	  	$user_dn = @ldap_get_dn($ldap, $user_entry);
	  	
	  	
        $entry = array(
			    'useraccountcontrol' =>"514",
			    );
			    
	    if (!@ldap_mod_replace($ldap,$user_dn,$entry)){
		    $error = @ldap_error($ldap);
		    $errno = @ldap_errno($ldap);
		    $message[] = "E201 - Your user cannot be change, please contact the administrator.";
		    $message[] = "$errno - $error";
	  	}
	  	else {
		    $message_css = "yes";	    
		    $message[] = "The change for has been used.";
	  	}
 	}

	function Enable($samaccountname)
 	{

 		$ldap = ldap_connect($this->ldap_host,389);
		  if (!$ldap)
	            throw new Exception("Cant connect ldap server", 1);
	            
	    ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
	    ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);  	   

	    $ldapBind= @ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass)or die("<br>Error: Couldn't bind to server using supplied credentials!"); 
			  
			  // bind anon and find user by uid
	    $attrib = array('unicodepwd','cn','thumbnailphoto','telephonenumber','streetaddress','sn','physicaldeliveryofficename','name','mail','jpegphoto','employeenumber','employeeid','distinguishedname','displayname','description','department','cn','samaccountname', 'givenname', 'useraccountcontrol'); 
   

        $results = @ldap_search($ldap,$this->ldap_dn,'(samaccountname='.trim($samaccountname).')',$attrib); 

	    $user_data = @ldap_get_entries($ldap, $results);
	  	$user_entry = @ldap_first_entry($ldap, $results);
	  	$user_dn = @ldap_get_dn($ldap, $user_entry);

        $entry = array('useraccountcontrol' =>"512",);
		

	    if (!@ldap_mod_replace($ldap,$user_dn,$entry)){
		    $error = @ldap_error($ldap);
		    $errno = @ldap_errno($ldap);
		    $message[] = "E201 - Your user cannot be change, please contact the administrator.";
		    $message[] = "$errno - $error";
	  	}
	  	else {
		    $message_css = "yes";	    
		    $message[] = "The change for has been used.";
	  	}

 	}

 	function InternetEstudiante()
 	{
 		try{			 
			 $ldap = ldap_connect($this->ldap_host,389);
		  	 
		  	 if (!$ldap)
	            throw new Exception("Cant connect ldap server", 1);
	            
	          ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
	          ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);  

	         $ldapBind= @ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass)or die("<br>Error: Couldn't bind to server using supplied credentials!"); 
		 
	         $attrib = array('thumbnailphoto','telephonenumber','physicaldeliveryofficename','description','cn', 'distinguishedname','samaccountname');

	         $filter="(&(objectClass=user)(memberOf=CN=UPR-Internet-Est,OU=_Gestion,DC=upr,DC=edu,DC=cu))";
	        
	        $results = @ldap_search($ldap,$this->ldap_dn,$filter,$attrib);  
		    $user_data = @ldap_get_entries($ldap, $results);


		    return $user_data;

	    }
       catch(\Exception $e)
        {
            Log::critical("No se puede acceder a los usuarios:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return response("Alguna cosa esta mal", 500);
        }
 	}

 	function InternetProfesores()
 	{
 		try{			 
			 $ldap = ldap_connect($this->ldap_host,389);
		  	 
		  	 if (!$ldap)
	            throw new Exception("Cant connect ldap server", 1);
	            
	          ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
	          ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);  

	         $ldapBind= @ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass)or die("<br>Error: Couldn't bind to server using supplied credentials!"); 
		 
	         $attrib = array('thumbnailphoto','telephonenumber','physicaldeliveryofficename','description','cn', 'distinguishedname','samaccountname');

	         $filter="(&(objectClass=user)(memberOf=CN=UPR-Internet-Profes,OU=_Gestion,DC=upr,DC=edu,DC=cu))";
	        
	        $results = @ldap_search($ldap,$this->ldap_dn,$filter,$attrib);  
		    $user_data = @ldap_get_entries($ldap, $results);

		    return $user_data;

	    }
       catch(\Exception $e)
        {
            Log::critical("No se puede acceder a los usuarios:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return response("Alguna cosa esta mal", 500);
        }
 	}

 	function findUPRedes()
 	{
 		try{			 
			 $ldap = ldap_connect($this->ldap_host,389);
		  	 
		  	 if (!$ldap)
	            throw new Exception("Cant connect ldap server", 1);
	            
	          ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
	          ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);  

	         $ldapBind= @ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass)or die("<br>Error: Couldn't bind to server using supplied credentials!"); 
		 
	         $attrib = array('thumbnailphoto','telephonenumber','physicaldeliveryofficename','description','cn', 'distinguishedname','samaccountname','displayname','employeenumber','employeeid');

	         $filter="(&(objectClass=user)(memberOf=CN=UPRedes,OU=Listas,OU=_Gestion,DC=upr,DC=edu,DC=cu))";
	        
	        $results = @ldap_search($ldap,$this->ldap_dn,$filter,$attrib);  
		    $user_data = @ldap_get_entries($ldap, $results);

		    return $user_data;

	    }
       catch(\Exception $e)
        {
            Log::critical("No se puede acceder a los usuarios:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
             return response("Alguna cosa esta mal", 500);
        }
 	}

 	function Docentes()
 	{
 		try{			 
			 $ldap = ldap_connect($this->ldap_host,389);
		  	 
		  	 if (!$ldap)
	            throw new Exception("Cant connect ldap server", 1);
	            
	          ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
	          ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);  

	         $ldapBind= @ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass)or die("<br>Error: Couldn't bind to server using supplied credentials!"); 
		 
	         $attrib = array('thumbnailphoto','telephonenumber','physicaldeliveryofficename','description','cn', 'distinguishedname','samaccountname');

	         $filter="(&(objectClass=user)(memberOf=CN=UPR-Docentes,OU=_Gestion,DC=upr,DC=edu,DC=cu))";
	        
	        $results = @ldap_search($ldap,$this->ldap_dn,$filter,$attrib);  
		    $user_data = @ldap_get_entries($ldap, $results);

		    

		    return $user_data;

	    }
       catch(\Exception $e)
        {
            Log::critical("No se puede acceder a los usuarios:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return response("Alguna cosa esta mal", 500);
        }
 	}

 	function Estudiante()
 	{
 		try{			 
			 $ldap = ldap_connect($this->ldap_host,389);
		  	 
		  	 if (!$ldap)
	            throw new Exception("Cant connect ldap server", 1);
	            
	          ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
	          ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);  

	         $ldapBind= @ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass)or die("<br>Error: Couldn't bind to server using supplied credentials!"); 
		 
	         $attrib = array('thumbnailphoto','telephonenumber','physicaldeliveryofficename','description','cn', 'distinguishedname','samaccountname');

	         $filter="(&(objectClass=user)(memberOf=CN=UPR-Estudiantes,OU=_Gestion,DC=upr,DC=edu,DC=cu))";
	        
	        $results = @ldap_search($ldap,$this->ldap_dn,$filter,$attrib);  
		    $user_data = @ldap_get_entries($ldap, $results);

		    

		    return $user_data;

	    }
       catch(\Exception $e)
        {
            Log::critical("No se puede acceder a los usuarios:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return response("Alguna cosa esta mal", 500);
        }
 	}

 	function KuotaDoctor()
 	{
 		try{			 
			 $ldap = ldap_connect($this->ldap_host,389);
		  	 
		  	 if (!$ldap)
	            throw new Exception("Cant connect ldap server", 1);
	            
	          ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
	          ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);  

	         $ldapBind= @ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass)or die("<br>Error: Couldn't bind to server using supplied credentials!"); 
		 
	         $attrib = array('thumbnailphoto','telephonenumber','physicaldeliveryofficename','description','cn', 'distinguishedname','samaccountname');

	         $filter="(&(objectClass=user)(memberOf=CN=UPR-Internet-Doctores,OU=_Gestion,DC=upr,DC=edu,DC=cu))";
	        
	        $results = @ldap_search($ldap,$this->ldap_dn,$filter,$attrib);  
		    $user_data = @ldap_get_entries($ldap, $results);

		    
		    
		    return $user_data;

	    }
       catch(\Exception $e)
        {
            Log::critical("No se puede acceder a los usuarios:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return response("Alguna cosa esta mal", 500);
        }
 	}

	function KuotaMater()
 	{
 		try{			 
			 $ldap = ldap_connect($this->ldap_host,389);
		  	 
		  	 if (!$ldap)
	            throw new Exception("Cant connect ldap server", 1);
	            
	          ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
	          ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);  

	         $ldapBind= @ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass)or die("<br>Error: Couldn't bind to server using supplied credentials!"); 
		 
	         $attrib = array('thumbnailphoto','telephonenumber','physicaldeliveryofficename','description','cn', 'distinguishedname','samaccountname');

	         $filter="(&(objectClass=user)(memberOf=CN=UPR-Internet-Master,OU=_Gestion,DC=upr,DC=edu,DC=cu))";
	        
	        $results = @ldap_search($ldap,$this->ldap_dn,$filter,$attrib);  
		    $user_data = @ldap_get_entries($ldap, $results);

		    
		    
		    return $user_data;

	    }
       catch(\Exception $e)
        {
            Log::critical("No se puede acceder a los usuarios:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return response("Alguna cosa esta mal", 500);
        }
 	}

 	function KuotaCuadro()
 	{
 		try{			 
			 $ldap = ldap_connect($this->ldap_host,389);
		  	 
		  	 if (!$ldap)
	            throw new Exception("Cant connect ldap server", 1);
	            
	          ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
	          ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);  

	         $ldapBind= @ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass)or die("<br>Error: Couldn't bind to server using supplied credentials!"); 
		 
	         $attrib = array('thumbnailphoto','telephonenumber','physicaldeliveryofficename','description','cn', 'distinguishedname','samaccountname');

	         $filter="(&(objectClass=user)(memberOf=CN=UPR-Internet-Cuadros,OU=_Gestion,DC=upr,DC=edu,DC=cu))";
	        
	        $results = @ldap_search($ldap,$this->ldap_dn,$filter,$attrib);  
		    $user_data = @ldap_get_entries($ldap, $results);

		    
		    
		    return $user_data;

	    }
       catch(\Exception $e)
        {
            Log::critical("No se puede acceder a los usuarios:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return response("Alguna cosa esta mal", 500);
        }
 	}

 	function KuotaRector()
 	{
 		try{			 
			 $ldap = ldap_connect($this->ldap_host,389);
		  	 
		  	 if (!$ldap)
	            throw new Exception("Cant connect ldap server", 1);
	            
	          ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
	          ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);  

	         $ldapBind= @ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass)or die("<br>Error: Couldn't bind to server using supplied credentials!"); 
		 
	         $attrib = array('thumbnailphoto','telephonenumber','physicaldeliveryofficename','description','cn', 'distinguishedname','samaccountname');

	         $filter="(&(objectClass=user)(memberOf=CN=UPR-Internet-Rector,OU=_Gestion,DC=upr,DC=edu,DC=cu))";
	        
	        $results = @ldap_search($ldap,$this->ldap_dn,$filter,$attrib);  
		    $user_data = @ldap_get_entries($ldap, $results);

		    
		    
		    return $user_data;

	    }
       catch(\Exception $e)
        {
            Log::critical("No se puede acceder a los usuarios:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return response("Alguna cosa esta mal", 500);
        }
 	}
 	function InternetNoDocentes()
 	{
 		try{			 
			 $ldap = ldap_connect($this->ldap_host,389);
		  	 
		  	 if (!$ldap)
	            throw new Exception("Cant connect ldap server", 1);
	            
	          ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
	          ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);  

	         $ldapBind= @ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass)or die("<br>Error: Couldn't bind to server using supplied credentials!"); 
		 
	         $attrib = array('thumbnailphoto','telephonenumber','physicaldeliveryofficename','description','cn', 'distinguishedname','samaccountname');

	         $filter="(&(objectClass=user)(memberOf=CN=UPR-Internet-NoDocente,OU=_Gestion,DC=upr,DC=edu,DC=cu))";
	        
	        $results = @ldap_search($ldap,$this->ldap_dn,$filter,$attrib);  
		    $user_data = @ldap_get_entries($ldap, $results);

		    return $user_data;

	    }
       catch(\Exception $e)
        {
            Log::critical("No se puede acceder a los usuarios:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return response("Alguna cosa esta mal", 500);
        }
 	}

 	function NoDocentes()
 	{
 		try{			 
			 $ldap = ldap_connect($this->ldap_host,389);
		  	 
		  	 if (!$ldap)
	            throw new Exception("Cant connect ldap server", 1);
	            
	          ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
	          ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);  

	         $ldapBind= @ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass)or die("<br>Error: Couldn't bind to server using supplied credentials!"); 
		 
	         $attrib = array('thumbnailphoto','telephonenumber','physicaldeliveryofficename','description','cn', 'distinguishedname','samaccountname');

	         $filter="(&(objectClass=user)(memberOf=CN=UPR-NoDocentes,OU=_Gestion,DC=upr,DC=edu,DC=cu))";
	        
	        $results = @ldap_search($ldap,$this->ldap_dn,$filter,$attrib);  
		    $user_data = @ldap_get_entries($ldap, $results);

		    

		    return $user_data;

	    }
       catch(\Exception $e)
        {
            Log::critical("No se puede acceder a los usuarios:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return response("Alguna cosa esta mal", 500);
        }
 	}

 	function UsuariosRas()
 	{
 		try{			 
			 $ldap = ldap_connect($this->ldap_host,389);
		  	 
		  	 if (!$ldap)
	            throw new Exception("Cant connect ldap server", 1);
	            
	          ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
	          ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);  

	         $ldapBind= @ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass)or die("<br>Error: Couldn't bind to server using supplied credentials!"); 
		 
	         $attrib = array('thumbnailphoto','telephonenumber','physicaldeliveryofficename','description','cn', 'distinguishedname','samaccountname');

	         $filter="(&(objectClass=user)(memberOf=CN=UPR-Ras,OU=_Gestion,DC=upr,DC=edu,DC=cu))";
	        
	        $results = @ldap_search($ldap,$this->ldap_dn,$filter,$attrib);  
		    $user_data = @ldap_get_entries($ldap, $results);


		    return $user_data;

	    }
       catch(\Exception $e)
        {
            Log::critical("No se puede acceder a los usuarios:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return response("Alguna cosa esta mal", 500);
        }
 	}
	
	function DocentesRas()
 	{
 		try{			 
			 $ldap = ldap_connect($this->ldap_host,389);
		  	 
		  	 if (!$ldap)
	            throw new Exception("Cant connect ldap server", 1);
	            
	          ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
	          ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);  

	         $ldapBind= @ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass)or die("<br>Error: Couldn't bind to server using supplied credentials!"); 
		 
	         $attrib = array('thumbnailphoto','telephonenumber','physicaldeliveryofficename','description','cn', 'distinguishedname','samaccountname');

	         $filter="(&(objectClass=user)(memberOf=CN=UPR-Docentes,OU=_Gestion,DC=upr,DC=edu,DC=cu)(memberOf=CN=UPR-Ras,OU=_Gestion,DC=upr,DC=edu,DC=cu))";
	        
	        $results = @ldap_search($ldap,$this->ldap_dn,$filter,$attrib);  
		    $user_data = @ldap_get_entries($ldap, $results);


		    return $user_data;

	    }
       catch(\Exception $e)
        {
            Log::critical("No se puede acceder a los usuarios:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return response("Alguna cosa esta mal", 500);
        }
 	}
	function NoDocentesRas()
 	{
 		try{			 
			 $ldap = ldap_connect($this->ldap_host,389);
		  	 
		  	 if (!$ldap)
	            throw new Exception("Cant connect ldap server", 1);
	            
	          ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
	          ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);  

	         $ldapBind= @ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass)or die("<br>Error: Couldn't bind to server using supplied credentials!"); 
		 
	         $attrib = array('thumbnailphoto','telephonenumber','physicaldeliveryofficename','description','cn', 'distinguishedname','samaccountname');

	         $filter="(&(objectClass=user)(memberOf=CN=UPR-NoDocentes,OU=_Gestion,DC=upr,DC=edu,DC=cu)(memberOf=CN=UPR-Ras,OU=_Gestion,DC=upr,DC=edu,DC=cu))";
	        
	        $results = @ldap_search($ldap,$this->ldap_dn,$filter,$attrib);  
		    $user_data = @ldap_get_entries($ldap, $results);


		    return $user_data;

	    }
       catch(\Exception $e)
        {
            Log::critical("No se puede acceder a los usuarios:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return response("Alguna cosa esta mal", 500);
        }
 	}
	function Adiestrados()
 	{
 		try{			 
			 $ldap = ldap_connect($this->ldap_host,389);
		  	 
		  	 if (!$ldap)
	            throw new Exception("Cant connect ldap server", 1);
	            
	          ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
	          ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);  

	         $ldapBind= @ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass)or die("<br>Error: Couldn't bind to server using supplied credentials!"); 
		 
	         $attrib = array('thumbnailphoto','telephonenumber','physicaldeliveryofficename','description','cn', 'distinguishedname','samaccountname');

	         $filter="(&(objectClass=user)(description=Recien Graduado En Adiestramiento))";
	        
	        $results = @ldap_search($ldap,$this->ldap_dn,$filter,$attrib);  
		    $user_data = @ldap_get_entries($ldap, $results);

		    
		    return $user_data;

	    }
       catch(\Exception $e)
        {
            Log::critical("No se puede acceder a los usuarios:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return response("Alguna cosa esta mal", 500);
        }
 	}

 	function Busqueda($search)
 	{
 		
 		try{			 
			 $ldap = ldap_connect($this->ldap_host,389);
		  	 
		  	 if (!$ldap)
	            throw new Exception("Cant connect ldap server", 1);
	            
	          ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
	          ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);  

	         $ldapBind= @ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass)or die("<br>Error: Couldn't bind to server using supplied credentials!"); 
		 
	         $attrib = array('thumbnailphoto','telephonenumber','physicaldeliveryofficename','description','cn', 'distinguishedname','samaccountname','name','employeenumber','displayname');

	         $filter='(&(|(samaccountname='.$search.')(cn='.$search.')(displayname='.$search.')(givenname='.$search.')(name='.$search.')(physicaldeliveryofficename='.$search.')(employeenumber='.$search.')(sn='.$search.'))(objectclass=user))';

	        $results = @ldap_search($ldap,$this->ldap_dn,$filter,$attrib);  
		    $user_data = @ldap_get_entries($ldap, $results);

		    
		    return $user_data;

	    }
       catch(\Exception $e)
        {
            Log::critical("No se puede acceder a los usuarios:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return response("Alguna cosa esta mal", 500);
        }
 	}
 	
 	function CrearUsuario($empleado){
		  try{
			  global $message;
			  global $message_css;
			    
			  error_reporting(0);
				$ldap = ldap_connect($this->ldap_host,389);
			  if (!$ldap)
		            throw new Exception("Cant connect ldap server", 1);
		            
	          ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
	          ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);  
			  
			  $ldapBind= @ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass)or die("<br>Error: Couldn't bind to server using supplied credentials!"); 
			  
			  	
		  		$cn = $this->user_unic($empleado['nombre'], $empleado['apellido1'], $empleado['apellido2'], 0);
		  		if ($this->Exist($cn)) {
		  			$cn = $this->user_unic($empleado['nombre'], $empleado['apellido1'], $empleado['apellido2'], 1);
		  		}
		  		
				$user_dn = 'CN='.$cn.',OU=newStudent,OU=_Usuarios,DC=upr,DC=edu,DC=cu';  
	 			
				$newPassw = $this->pwd_encryption("P@ssword");

				if(html_entity_decode(trim(ucwords(strtolower($departamento)))) == "") {
			    		$physical = "No tiene";
			    	}	
			    else{
			    	   $physical = html_entity_decode(trim(ucwords(strtolower($departamento))));
					}
				if (html_entity_decode(ucwords(strtolower($cargo))) == "") {
						$descrip= "No tiene";
					}
				else{
			    	 $descrip=html_entity_decode(ucwords(strtolower($cargo)));		
			    	} 
		    	if ($empleado['telefonoParticular'] == "") {
					$phone= "No tiene";
				}
				else{
			    	 $phone=$empleado['telefonoParticular'];		
			    	} 
			     $nek = $this->normaliza($cn);
	            $entry = array(
			    'streetAddress' =>html_entity_decode(trim(ucwords(strtolower(  $empleado['direccion'])))),
			    'givenname' => html_entity_decode(trim(ucwords(strtolower($empleado['nombre'])))),
			    'sn' => html_entity_decode(trim(ucwords(strtolower($empleado['apellido1']).' '.strtolower($empleado['apellido2'])))),
			    'employeenumber'=> trim($empleado['idExpediente']),	
			    'employeeid'=> trim($empleado['noCi']),			    
		    	'physicaldeliveryofficename' => trim($physical),
		    	'description'=>trim($descrip),					    	
			    'objectclass' => [0=>"top",1=>"person",2=>"organizationalPerson",3=>"user"],
			    'mail'			 => $nek.'@upr.edu.cu',
			    'userPrincipalName' => $nek.'@upr.edu.cu',
			    'telephoneNumber'=>$phone,
			    'displayName' => html_entity_decode(trim(ucwords(strtolower($empleado['nombre'])))).' '. html_entity_decode(trim(ucwords(strtolower($empleado['apellido1']).' '.strtolower($empleado['apellido2'])))),
			    'sAMAccountName' =>$nek,
			    'useraccountcontrol'=>'514',
			    //'unicodePwd'=> $newPassw
			    );

			    
			    
			    if (!@ldap_add($ldap,$user_dn, $entry)){
				    $error = @ldap_error($ldap);
				    $errno = @ldap_errno($ldap);
				    $message[] = "E201 - Your user cannot be change, please contact the administrator.";
				    $message[] = "$errno - $error";
				    
			  	}
			  	else {
				    $message_css = "yes";	    
				    $message[] = "The change for $user_id has been used $entry[givenname].";
			  	}

			  	
			  	
			  	return true;
		  	}
		  	catch(\Exception $e)
        	{
            	Log::critical("No se puede acceder al empleado del Assets:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
		  		return false;
		  	}
		  	
	}

	function CrearStudent($empleado, $facultad, $officces){		
	  try{
		  global $message;
		  global $message_css;
		    
		  error_reporting(0);
			$ldap = ldap_connect($this->ldap_host,389);
		  if (!$ldap)
	            throw new Exception("Cant connect ldap server", 1);
	            
          ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
          ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);  
		  
		  $ldapBind= @ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass)or die("<br>Error: Couldn't bind to server using supplied credentials!"); 
		  
		  	if (!$this->ExistCI($empleado['identification'])) {

		  		$cn = $this->user_unic($empleado['name'], $empleado['first_name'], $empleado['second_name'], 0);
		  		if ($this->Exist($cn)) {
		  			$cn = $this->user_unic($empleado['name'], $empleado['first_name'], $empleado['second_name'], 1);
		  		}
		  		
				$user_dn = 'CN='.$cn.',OU=newStudent,OU=_Usuarios,DC=upr,DC=edu,DC=cu';  
	 			
				$nek= $this->normaliza($cn);
				//dd($empleado);
	            $entry = array(
			    'streetAddress' =>html_entity_decode(trim(ucwords(strtolower(  $empleado['address'])))),
			    'givenname' => html_entity_decode(trim(ucwords(strtolower($empleado['name'])))),
			    'sn' => html_entity_decode(trim(ucwords(strtolower($empleado['first_name']).' '.strtolower($empleado['second_name'])))),
			    'employeenumber'=> $empleado['id_matriculated_student'],	
			    'employeeid'=> $empleado['identification'],			    
		    	'physicaldeliveryofficename' => $officces,
		    	'description'=>$facultad,					    	
			    'objectclass' => [0=>"top",1=>"person",2=>"organizationalPerson",3=>"user"],
			    'mail'			 => $nek.'@estudiantes.upr.edu.cu',
			    'userPrincipalName' => $nek.'@estudiantes.upr.edu.cu',
			    //'telephoneNumber'=>$empleado[''],
			    'displayName' => html_entity_decode(trim(ucwords(strtolower($empleado['name'])))).' '. html_entity_decode(trim(ucwords(strtolower($empleado['first_name']).' '.strtolower($empleado['second_name'])))),
			    'sAMAccountName' =>$nek,
			    'useraccountcontrol'=>'514',
			    'name' => html_entity_decode(trim(ucwords(strtolower($empleado['name'])))).' '. html_entity_decode(trim(ucwords(strtolower($empleado['first_name']).' '.strtolower($empleado['second_name'])))),		    
			    );
			    
			    //dd($entry);

			    if (!@ldap_add($ldap,$user_dn, $entry)){
				    $error = @ldap_error($ldap);
				    $errno = @ldap_errno($ldap);
				    $message[] = "E201 - Your user cannot be change, please contact the administrator.";
				    $message[] = "$errno - $error";
				    
			  	}
			  	else {
				    $message_css = "yes";	    
				    $message[] = "The change for $user_id has been used $entry[givenname].";

				    $group= [
			    		'Domain Users',
			    		'UPR-Wifi',
			    		'UPR-Jabber',
			    		'UPR-Correo-Internacional',
			        	'UPR-Estudiantes',
			        	'UPR-Internet-Est'
			    	];   	
			    	$this->addtogroup($user_dn, $group);

			    	/*$info['unicodepwd'] = "{MD5}".base64_encode(pack("H*",md5("P@ssword")));
			    	
			    	if (!ldap_mod_replace($ldap, $user_dn, $info)){
			    		dd(@ldap_error($ldap));
			    	}*/

			  		}//end else	
			  		return true;
		  	}//end if ($this-<ExistCI)
	  		
	  		return false;
		  	
		  	
	  	}
	  	catch(\Exception $e)
    	{
        	Log::critical("No se puede acceder al empleado del Assets:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
	  		return false;
	  	}
		  	
	}

	function CrearStudentWeb($empleado){		
	  try{
		  global $message;
		  global $message_css;
		    
		  error_reporting(0);
			$ldap = ldap_connect($this->ldap_host,389);
		  if (!$ldap)
	            throw new Exception("Cant connect ldap server", 1);
	            
          ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
          ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);  
		  
		  $ldapBind= @ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass)or die("<br>Error: Couldn't bind to server using supplied credentials!"); 
		  
		  	
	  		$cn = $this->user_unic($empleado['name'], $empleado['middle_name'], $empleado['last_name'], 0);
	  		if ($this->Exist($cn)) {
	  			$cn = $this->user_unic($empleado['name'], $empleado['middle_name'], $empleado['last_name'], 1);
	  		}
	  		
			$user_dn = 'CN='.$cn.',OU=newStudent,OU=_Usuarios,DC=upr,DC=edu,DC=cu';  
 			
			$nek= $this->normaliza($cn);
			//dd($empleado);


			$officces="No Tiene";
			$facultad="No Tiene";

            $entry = array(
		    'streetAddress' =>html_entity_decode(trim(ucwords(strtolower(  $empleado['address'])))),
		    'givenname' => html_entity_decode(trim(ucwords(strtolower($empleado['name'])))),
		    'sn' => html_entity_decode(trim(ucwords(strtolower($empleado['middle_name']).' '.strtolower($empleado['last_name'])))),
		    'employeenumber'=> $empleado['id_student'],	
		    'employeeid'=> $empleado['identification'],			    
	    	'physicaldeliveryofficename' => $officces,
	    	'description'=>$facultad,					    	
		    'objectclass' => [0=>"top",1=>"person",2=>"organizationalPerson",3=>"user"],
		    'mail'			 => $nek.'@estudiantes.upr.edu.cu',
		    'userPrincipalName' => $nek.'@estudiantes.upr.edu.cu',
		    //'telephoneNumber'=>$empleado[''],
		    'displayName' => html_entity_decode(trim(ucwords(strtolower($empleado['name'])))).' '. html_entity_decode(trim(ucwords(strtolower($empleado['middle_name']).' '.strtolower($empleado['last_name'])))),
		    'sAMAccountName' =>$nek,
		    'useraccountcontrol'=>'514',
		    'name' => html_entity_decode(trim(ucwords(strtolower($empleado['name'])))).' '. html_entity_decode(trim(ucwords(strtolower($empleado['middle_name']).' '.strtolower($empleado['last_name'])))),		    
		    );
		    
		    //dd($user_dn);

		    if (!@ldap_add($ldap,$user_dn, $entry)){
			    $error = @ldap_error($ldap);
			    $errno = @ldap_errno($ldap);
			    $message[] = "E201 - Your user cannot be change, please contact the administrator.";
			    $message[] = "$errno - $error";
			    
		  	}
		  	else {
			    $message_css = "yes";	    
			    $message[] = "The change for $user_id has been used $entry[givenname].";

			    $group= [
		    		'Domain Users',
		    		'UPR-Wifi',
		    		'UPR-Jabber',
		    		'UPR-Correo-Internacional',
		        	'UPR-Estudiantes',
		        	'UPR-Internet-Est'
		    	];   	
		    	$this->addtogroup($user_dn, $group);

		    	/*$info['unicodepwd'] = "{MD5}".base64_encode(pack("H*",md5("P@ssword")));
		    	
		    	if (!ldap_mod_replace($ldap, $user_dn, $info)){
		    		dd(@ldap_error($ldap));
		    	}*/

		  	}
		  	//dd($message);
		  	return true;
	  	}
	  	catch(\Exception $e)
    	{
        	Log::critical("No se puede acceder al empleado del Assets:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
	  		return false;
	  	}
		  	
	}

	public function user_unic($nombre, $apellido1, $apellido2, $veces=0){
		if ($veces == 0) {
			$a = explode(" ", $nombre);
		  	$b = explode(" ", $apellido1);
		  	$c=$apellido2[0];
		  	return $cn =strtolower($a[0].'.'.$b[0].$c);
	  	}
	  	else
	  	{
	  		$a = explode(" ", $nombre);
		  	$b = $apellido1[0];
		  	$c=explode(" ", $apellido2);
		  	return $cn =strtolower($a[0].'.'.$b.$c[0]);	
	  	}
	}
	public function pwd_encryption($password){

		$password = '"' . $password . '"';
        if (function_exists('mb_convert_encoding')) {
            $password = mb_convert_encoding($password, 'UTF-16LE', 'UTF-8');
        } elseif (function_exists('iconv')) {
            $password = iconv('UTF-8', 'UTF-16LE', $password);
        } else {
            $len = strlen($password);
            $new = '';
            for ($i = 0; $i < $len; $i++) {
                $new .= $password[$i] . "\x00";
            }
            $password = $new;
        }
        return base64_encode($password);
	}

	function SaberUltimasUserCreador(){
		try{		

			$date = Carbon::now();				 
			 $ldap = ldap_connect($this->ldap_host,389);
		  	 
		  	 if (!$ldap)
	            throw new Exception("Cant connect ldap server", 1);
	            
	          ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
	          ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);  

	         $ldapBind= @ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass)or die("<br>Error: Couldn't bind to server using supplied credentials!"); 
		 
	         $attrib = array('thumbnailphoto','telephonenumber','physicaldeliveryofficename','description','cn', 'distinguishedname','samaccountname','name','employeenumber');

	         $filter='(&(objectclass=user)(whencreated='.$date->toDateString().'))';
	         dd($filter);

	        $results = @ldap_search($ldap,$this->ldap_dn,$filter,$attrib);  
		    $user_data = @ldap_get_entries($ldap, $results);

		    
		    return $user_data;

	    }
       catch(\Exception $e)
        {
            Log::critical("No se puede acceder a los usuarios:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return response("Alguna cosa esta mal", 500);
        }
	}

	function isMemberUPRedes($username)
	{
		try{		

			
			$ldap = ldap_connect($this->ldap_host,389);
		  	 
		  	 if (!$ldap)
	            throw new Exception("Cant connect ldap server", 1);
	            
	          ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
	          ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);  

	         $ldapBind= @ldap_bind($ldap, $this->ldapuser. $this->ldap_usr_dom, $this->ldappass)or die("<br>Error: Couldn't bind to server using supplied credentials!"); 
		 
	         $attrib = array('thumbnailphoto','telephonenumber','physicaldeliveryofficename','description','cn', 'distinguishedname','samaccountname','name','employeenumber');

	         $filter="(&(objectClass=user)(samaccountname=".$username.")(memberOf=CN=UPRedes,OU=Listas,OU=_Gestion,DC=upr,DC=edu,DC=cu))";	         

	        $results = @ldap_search($ldap,$this->ldap_dn,$filter,$attrib);  
		    $user_data = @ldap_get_entries($ldap, $results);
		    if($user_data['count']>0)
		    {
		    	return true;
		    }		    
		    return false;

	    }
       catch(\Exception $e)
        {
            Log::critical("No se puede acceder a los usuarios:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return false;
        }
	}

}
