<?php

namespace Sync;

use Illuminate\Database\Eloquent\Model;
use Log;
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
            global $ldap_dn,$ldap_usr_dom;
            
            //$ldap = ldap_connect($this->ldap_host,389);    

            ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
            ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);
     
            $ldapBind= ldap_bind($ldap, $username. $this->ldap_usr_dom, $password);            
            //ldap_unbind($ldap);   
                

            return   $ldapBind; 
     }
    
    function Auth($username, $password, $adGroupName = false){
        global $ldap_host,$ldap_dn;
            
        $ldap = ldap_connect($this->ldap_host);
        if (!$ldap)
            throw new Exception("Cant connect ldap server", 1);
        
          return isLdapUser($username, $password, $ldap);     
        
    }

    function Info($username, $password, $adGroupName = false,$attrib){
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
	       		
	            $entry = array(
			    'streetAddress' =>html_entity_decode(trim(ucwords(strtolower(  $empleado['direccion'])))),
			    'givenname' => html_entity_decode(trim(ucwords(strtolower($empleado['nombre'])))),
			    'sn' => html_entity_decode(trim(ucwords(strtolower($empleado['apellido1']).' '.strtolower($empleado['apellido2'])))),
			    //'employeenumber'=> $empleado['idExpediente'],	
			    'employeeid'=> $empleado['noCi'],	
			    'physicaldeliveryofficename' => html_entity_decode(trim(ucwords(strtolower($departamento)))),
			    'description'=>html_entity_decode(ucwords(strtolower($cargo))),
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
            	Log::critical("No se puede acceder al empleado del Assets:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
		  		return false;
		  	}
		  	//dd($message);
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

         $entry = array(
			    'useraccountcontrol' =>"512",
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
		 
	         $attrib = array('thumbnailphoto','telephonenumber','physicaldeliveryofficename','description','cn', 'distinguishedname','samaccountname','name');

	         $filter='(&(|(samaccountname='.$search.')(cn='.$search.')(displayname='.$search.')(givenname='.$search.')(name='.$search.')(physicaldeliveryofficename='.$search.')(sn='.$search.'))(objectclass=user))';

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
 	//$filter='(&(|(samaccountname='.$search.')(cn='.$search.')(displayname='.$search.')(givenname='.$search.')(name='.$search.')(physicaldeliveryofficename='.$search.')(sn='.$search.'))(objectclass=computer))';

}
