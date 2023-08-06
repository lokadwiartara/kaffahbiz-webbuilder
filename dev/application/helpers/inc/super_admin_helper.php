<?php
	function addDomain($newdomain, $newip=NULL){
		global $SConfig;
		$_this =& get_instance();
		$os = $_SERVER['SERVER_SOFTWARE'];
		preg_match('#\([^)]+\)#', $os, $match);
		
		if($match[0] == '(Win32)'){
			return TRUE;
		}
		
		else{
			$domain = $newdomain;
			($newip == NULL) ?  $ip = $SConfig->ip : $ip = $newip;
			$domain_is_exist = exec('cat /etc/named.conf | grep '.$domain);
			
			if ($domain_is_exist == NULL){
				$output = exec('sudo /etc/init.d/named stop');
				$output = shell_exec("sudo /home/domaintool/adddomain $domain $ip");				
				return  TRUE;
			}
			
			else{
				return FALSE;
			}			
		}
		
	}

	function tesip(){
		global $SConfig;
		return $SConfig->ip;
	}

	function strposa($haystack, $needles=array(), $offset=0) {
        $chr = array();
        foreach($needles as $needle) {
                $res = strpos($haystack, $needle, $offset);
                if ($res !== false) $chr[$needle] = $res;
        }
        if(empty($chr)) return false;
        return min($chr);
	}	
?>