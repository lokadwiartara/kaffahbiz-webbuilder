<?php

	function get_domain_atr($whatto=NULL){
		$_this =& get_instance();
		if($whatto != NULL){
			$domAtr = $_this->main->domainatr;
			if($whatto == 'domain_name'){
 				$dom = explode('.', $domAtr[$whatto], 2);
 				if($dom[0] == 'www'){
 					return $dom[1];
 				}
 				else{
 					return $dom[0].'.'.$$dom[1]; 
 				}
			}
			else{				
				return @$domAtr[$whatto];	
			}
			
		}
	}	

?>