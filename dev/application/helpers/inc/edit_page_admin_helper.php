<?php
	function get_page_name(){
		$_this =& get_instance();
		if($_this->uri->segment(5)){
			return $_this->uri->segment(5);
		}
	}
?>