<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->main->init_main();
	}
	
	public function detail($halaman){
		global $SConfig;
		
		if(@$this->main->allAtr->domain_name ){
			$this->main->isPage = TRUE;
			
			/* tentukan variable yang akan digunakan nantinya di halaman tersebut */
			$data = array();
			$this->main->view_themes('index',$data, TRUE);
		}
		
		else{
			$data = array();
			$this->main->view_themes('index',$data, TRUE);
		}	
	}

	public function register(){
		global $SConfig;
		
		if(@$this->main->allAtr->domain_name ){
			$this->main->isPage = TRUE;
			
			/* tentukan variable yang akan digunakan nantinya di halaman tersebut */
			$data = array();
			$this->main->view_themes('index',$data, TRUE);
		}
		
		else{
			$data = array();
			$this->main->view_themes('index',$data, TRUE);
		}		
	}

	public function login(){
		global $SConfig;
		
		if(@$this->main->allAtr->domain_name ){
			$this->main->isPage = TRUE;			
			/* tentukan variable yang akan digunakan nantinya di halaman tersebut */
			$data = array();
			$this->main->view_themes('index',$data, TRUE);
		}
		
		else{
			$data = array();
			$this->main->view_themes('index',$data, TRUE);
		}	
	}	


	public function dashboard(){
		global $SConfig;		
		
		if(@$this->main->allAtr->domain_name ){
			$this->main->isPage = TRUE;			
			/* tentukan variable yang akan digunakan nantinya di halaman tersebut */
			$data = array();
			$this->main->view_themes('index',$data, TRUE);
		}
		
		else{
			$data = array();
			$this->main->view_themes('index',$data, TRUE);
		}	
	}


	public function setting(){
		global $SConfig;
		
		if(@$this->main->allAtr->domain_name ){
			$this->main->isPage = TRUE;			
			/* tentukan variable yang akan digunakan nantinya di halaman tersebut */
			$data = array();
			$this->main->view_themes('index',$data, TRUE);
		}
		
		else{
			$data = array();
			$this->main->view_themes('index',$data, TRUE);
		}	
	}

	public function transaksi(){
		global $SConfig;
		
		if(@$this->main->allAtr->domain_name ){
			$this->main->isPage = TRUE;			
			/* tentukan variable yang akan digunakan nantinya di halaman tersebut */
			$data = array();
			$this->main->view_themes('index',$data, TRUE);
		}
		
		else{
			$data = array();
			$this->main->view_themes('index',$data, TRUE);
		}	
	}

	public function logout(){
		global $SConfig;
		
		if(@$this->main->allAtr->domain_name ){
			$this->main->isPage = TRUE;			
			/* tentukan variable yang akan digunakan nantinya di halaman tersebut */
			$data = array();
			$this->main->view_themes('index',$data, TRUE);
		}
		
		else{
			$data = array();
			$this->main->view_themes('index',$data, TRUE);
		}	
	}		

	public function lupa_password(){
		global $SConfig;
		
		if(@$this->main->allAtr->domain_name ){
			$this->main->isPage = TRUE;			
			/* tentukan variable yang akan digunakan nantinya di halaman tersebut */
			$data = array();
			$this->main->view_themes('index',$data, TRUE);
		}
		
		else{
			$data = array();
			$this->main->view_themes('index',$data, TRUE);
		}	
	}	

	public function aktivasi(){
		global $SConfig;
		
		if(@$this->main->allAtr->domain_name ){
			$this->main->isPage = TRUE;			
			/* tentukan variable yang akan digunakan nantinya di halaman tersebut */
			$data = array();
			$this->main->view_themes('index',$data, TRUE);
		}
		
		else{
			$data = array();
			$this->main->view_themes('index',$data, TRUE);
		}	
	}	

	public function konfirmasi_selesai(){
		global $SConfig;
		
		if(@$this->main->allAtr->domain_name ){
			$this->main->isPage = TRUE;			
			/* tentukan variable yang akan digunakan nantinya di halaman tersebut */
			$data = array();
			$this->main->view_themes('index',$data, TRUE);
		}
		
		else{
			$data = array();
			$this->main->view_themes('index',$data, TRUE);
		}	
	}		
						
	public function check_email($str){		

		$this->load->model('site_model');				

		/* pengecekan jika email sudah terdaftar di website yang ditempati */
		$detil = $this->site_model->checkuser(
										array('kp_users.email' => $str, 
											'kp_domain.domain_id' => $this->main->allAtr->domain_id
											)
										);

		if(count($detil) > 0){
			$this->form_validation->set_message('check_email', 'mohon maaf, %s sudah terdaftar silahkan gunakan email lain');
			return FALSE;			
		}

		/* jika email belum terdaftar maka langkah selanjutnya adalah mengecek hubungannya ... */
		else{

			$detil2 = $this->site_model->checkuserfase2(
								array('kp_users.email' => $str, 
									'kp_multi_sitelogin.blog_id' => $this->main->allAtr->domain_id
									)
								);
			
			if(count($detil2) > 0){
				$this->form_validation->set_message('check_email', 'mohon maaf, %s sudah terdaftar silahkan gunakan email lain');
				return FALSE;				
			}
			else{
				return TRUE;
				
			}
		}

	}

}

/* End of file post.php */
/* Location: ./application/controllers/front.php */