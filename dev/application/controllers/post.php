<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Post extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->main->init_main();

	}

	public function kategori(){
		global $SConfig;

		if(@$this->main->allAtr->domain_name){
			$this->main->isCategory = TRUE;			
			
			/* tentukan variable yang akan digunakan nantinya di halaman tersebut */
			$data = array();
			$this->main->view_themes('category',$data, TRUE);
		}
		
		else{
			$data = array();
			$this->main->view_themes('category',$data, TRUE);
		}		
	}
	
	public function detail($years,$month,$article){		
		global $SConfig;

		//$session = $this->session->userdata;
		//print_r($session);
		
		if(@$this->main->allAtr->domain_name ){
			$this->main->isSingle = TRUE;
			
			/* tentukan variable yang akan digunakan nantinya di halaman tersebut */
			$data = array();
			$this->main->view_themes('index',$data, TRUE);
		}
		
		else{
			$data = array();
			$this->main->view_themes('index',$data, TRUE);
		}
		
	}




	
	public function komentar(){
		global $SConfig;
		
		if(@$this->main->allAtr->domain_name ){
		}
		
		else{
		}
		
	}						  	
}

/* End of file post.php */
/* Location: ./application/controllers/front.php */