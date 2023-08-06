<?php
	function get_article_id(){
		$_this =& get_instance();
		if($_this->uri->segment(5)){
			return $_this->uri->segment(5);
		}
	}
?>