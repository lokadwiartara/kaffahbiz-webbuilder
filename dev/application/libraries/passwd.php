<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/* libary */

class Passwd {

	var $salt_length			= 10;
	var $store_salt				= FALSE;
		
	function hash_passwd($password, $passworddb){		
		$salt = substr($passworddb, 0, 10);
		return $salt . substr(sha1($salt . $password), 0, -10);		
	}
	
	function to_password($password, $salt=false){
	    if (empty($password)){
	    	return FALSE;
	    }

	    if ($this->store_salt && $salt){
		    return  sha1($password . $salt);
	    }

	    else{
			$salt = $this->salt();
			return  $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
	    }
	}

	function salt(){
	    return substr(md5(uniqid(rand(), true)), 0, $this->salt_length);
	}
		
}


/* End of file passwd.php */