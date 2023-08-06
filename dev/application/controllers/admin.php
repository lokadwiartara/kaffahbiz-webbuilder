<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->main->init_main();

	}


	function index(){
		$this->login();
	}

	function login(){
		if(@$this->main->allAtr->domain_name ){
			$data = array();
			$this->main->view_themes('login_redirect',$data, FALSE);
		}

	}
}