<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Coredb {

	function count_table_row($where=NULL,$table){
		$_this =& get_instance();

		(!empty($where)) ? $_this->db->where($where) : $where=NULL;
		return $_this->db->count_all_results($table);
	}	
	
}