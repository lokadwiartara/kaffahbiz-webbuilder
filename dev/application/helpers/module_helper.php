<?php
	
	function update_function($blogid,$modulename=NULL,$input=NULL,$modulekey=NULL,$position=NULL){
		$_this =& get_instance();	
		$_this->load->helper('form');
		$display = NULL;
		/* keluarkan semua isi modulnya untuk nanti di echo */
		if(!empty($modulename)){
			if(strpos($modulename, 'backend') > 0){
				/* jika yang di akses itu adalah blog kepunyaan si user 
				maka dia bisa memasukkannya ke dalam database */
				
				$display = call_user_func_array($modulename, array('updatedb',$input,$position,$modulekey,$blogid));	
			}				
		}
	    return $display;	
	}
	
	function super_unique($array,$key){

	   $temp_array = array();

	   foreach ($array as &$v) {

	       if (!isset($temp_array[$v[$key]]))

	       $temp_array[$v[$key]] =& $v;

	   }

	   $array = array_values($temp_array);

	   return $array;

	}


	function insert_function($blogid,$modulename=NULL,$input=NULL,$position=NULL){
		global $SConfig;
		$_this =& get_instance();	
		$_this->load->helper('form');
		$display = NULL;
		
		/* keluarkan semua isi modulnya untuk nanti di echo */
		if(!empty($modulename)){
			if(strpos($modulename, 'backend') > 0){
				/* jika yang di akses itu adalah blog kepunyaan si user 
				maka dia bisa memasukkannya ke dalam database */
				
				$display = call_user_func_array($modulename, array('inserttodb',$input,$position,NULL,$blogid));	
			}				
		}
	    return $display;	
	}
	
	function get_function($blogid,$modulename=NULL){
		global $SConfig;
		$_this =& get_instance();	
		$_this->load->helper('form');
		$display = NULL;
		
		/* keluarkan semua isi modulnya untuk nanti di echo */
		if(!empty($modulename)){
			if(strpos($modulename, 'backend') > 0){
				$display .= call_user_func_array($modulename, array('insert'));	
				
				
			}				
		   	
		}
		
		
	    return $display;	
			
	}
	
	function edit_function($blogid,$modulename=NULL,$modulekey=NULL,$input=NULL,$position=NULL){
		global $SConfig;
		$_this =& get_instance();	
		$_this->load->helper('form');
		$display = NULL;
		
		/* keluarkan semua isi modulnya untuk nanti di echo */		

		if(!empty($modulename)){
			$display .= call_user_func_array($modulename.'_backend', array('edit',$input,$position,$modulekey,$blogid));	 	
		}
			   
	    return $display;			
	}
	
	function get_list_function($blogid,$modulename=NULL){
		global $SConfig;
		$_this =& get_instance();
		
		$functionlist = NULL;
		$display = NULL;
	    $content = NULL;
		
		$directory = $SConfig->approot.'/dev/application/helpers/module/'.$modulename;

		if ($handle = opendir($directory)) {
		    while (false !== ($entry = readdir($handle))) {
		        if ($entry != "." && $entry != "..") {
					$content .= file_get_contents($directory.'/'.$entry);
		        }
		    }
		    closedir($handle);
		}

		
		
		/* ambil setiap function dalam modul-modul tersebut */
	    preg_match_all("/(function )(\S*\(\S*\))/", $content, $matches);
	
		
		/* 	jika semua modul sudah di ambil maka langkah terakhir adalah 
			menganggap itu adalah bagian dari modul */	
		foreach($matches[2] as $match) {
	        $function[] = preg_replace("/\([^\)]+\)/","",trim($match));
	    }
		
		/* pengurutan modul-modul berdasarkan huruf abjad dan angka */
		@natcasesort($function);
		 
		/* keluarkan semua isi modulnya untuk nanti di echo */
		if(!empty($function)){
			foreach($function as $row){
				if(strpos($row, 'backend') > 0){
					$display .= call_user_func_array($row, array('desc'));	
				}				
		   	}
		}
			   
	    return $display;				
	}
	
?>