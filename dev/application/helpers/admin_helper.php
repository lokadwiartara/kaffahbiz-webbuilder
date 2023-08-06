<?php
	/*  helper ini digunakan untuk bantuan admin 
		baik template maupun yang lainnya
	*/		

	/* masukkan kebutuhan file-file */
	require "inc/bloglist_admin_helper.php";
	require "inc/new_article_admin_helper.php";
	require "inc/edit_article_admin_helper.php";
	require "inc/cat_article_admin_helper.php";
	require "inc/page_admin_helper.php";
	require "inc/edit_page_admin_helper.php";
	require "inc/super_admin_helper.php";
	
	/* daftar function */
	
	/* fungsi untuk mendapatkan configuration */
	function getconfig($config){
		global $SConfig;
		return $SConfig->$config;
	}
	
	function cmp($a, $b) {
        if ($a['sort'] == $b['sort']) {
                return 0;
        }
        return ($a['sort'] > $b['sort']) ? -1 : 1;
	}
	
	function getoffset(){
		$_this =& get_instance();
		
		if($_this->uri->segment(4) == 'page'){
						
			return $_this->uri->segment(5);	
		}
		
		else if($_this->uri->segment(2) == 'getallcomment'){
			return  $_this->uri->segment(4);	
		}

		else{
			return '';
		}
	}

	function toMoney($var){
		return preg_replace("/[., ]/", "", $var);
	}
	
	function isVal($var){
		if(!empty($var)){
			return $var;
		}
		
		else{
			return '';
		}
	}

	if( !function_exists('ceiling') )
	{
	    function ceiling($number, $significance = 1)
	    {
	        return ( is_numeric($number) && is_numeric($significance) ) ? (ceil($number/$significance)*$significance) : false;
	    }
	}	



	function write_template($dom,$html){
		global $SConfig;
		chmod($SConfig->docroot.'/template/flat/'.$dom.'.tpl', 0644);
		return file_put_contents($SConfig->docroot.'/template/flat/'.$dom.'.tpl', $html);
	}


	function open_template($dom){
		global $SConfig;
		return file_get_contents($SConfig->docroot.'/template/flat/'.$dom.'.tpl');
	}
 



?>