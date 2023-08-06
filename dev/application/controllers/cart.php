<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cart extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->main->init_main();

	}

	public function keranjang_belanja(){
		global $SConfig;
		
		if(@$this->main->allAtr->domain_name ){
			$this->main->isPage = TRUE;

			$data = array();
			$this->main->view_themes('index',$data, TRUE);

		}
		else{

		}
	}

	public function pembelian_selesai(){
		global $SConfig;
		
		if(@$this->main->allAtr->domain_name ){
			$this->main->isPage = TRUE;

			$data = array();
			$this->main->view_themes('index',$data, TRUE);

		}
		else{

		}
	}


	public function pemeriksaan_pembelian($cek=null){
		global $SConfig;
		
		if(@$this->main->allAtr->domain_name ){
			$this->load->library('custompage');			
			$this->main->isPage = TRUE;		

			$data = array();
			$this->main->view_themes('index',$data, TRUE);

		}
		else{

		}
	}	
	
	public function beli(){
		global $SConfig;
		
		if(@$this->main->allAtr->domain_name ){
			$this->load->library('custompage');
			$this->main->isPage = TRUE;						
			$this->custompage->beli($_POST);			

			$data = array();
			$this->main->view_themes('index',$data, TRUE);
		}
		
		else{
		}

		// print_r($_POST);

		// redirect($_SERVER['HTTP_REFERER']);
	}

	public function kategori_produk(){
		global $SConfig;
				

		if(@$this->main->allAtr->domain_name){
			$this->main->isCategoryProduct = TRUE;									

			/* tentukan variable yang akan digunakan nantinya di halaman tersebut */
			$data = array();
			$this->main->view_themes('category',$data, TRUE);
		}
		
		else{
			$data = array();
			$this->main->view_themes('category',$data, TRUE);
		}		
	}
	
	public function detail_produk($years,$month,$article){		
		global $SConfig;

		//$session = $this->session->userdata;
		//print_r($session);
		
		if(@$this->main->allAtr->domain_name ){
			$this->main->isSingleProduct = TRUE;
			
			/* tentukan variable yang akan digunakan nantinya di halaman tersebut */
			$data = array();
			$this->main->view_themes('index',$data, TRUE);
		}
		
		else{
			$data = array();
			$this->main->view_themes('index',$data, TRUE);
		}
		
	}				
}