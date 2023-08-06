<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dir extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->main->init_main();		
	}


	public function index(){
		/* 	ketika yang di akses itu adalah domain utama 
			maka tampilkan view index
		*/
		
		global $SConfig;
		
		if(@$this->main->allAtr->domain_name == $SConfig->domain){			
			$data = array();
			$this->main->isHome = TRUE;
			$this->main->forTitle = $SConfig->dirname;
			$this->main->visitorLog();
			$this->main->view_themes('dir/index',$data, TRUE);
		}
		
		
		else{
			$data = array();
			
			$this->main->view_themes('index',$data, TRUE);
		}
	}

	public function directory($type,$what=null,$to=null){

		global $SConfig;
		
		if(@$this->main->allAtr->domain_name == $SConfig->domain){			
			$data = array();
			$this->main->isCategory = TRUE;			
			$this->main->visitorLog();				

			if($this->uri->total_segments() > 2){				
				if($type=='artikel'){				
					
					if($what=='id'){
						$this->template->dir = get_detail_post();						
						$this->template->title =  $this->template->dir->post_title.' | '.$SConfig->dirname . ' Artikel';
						$type='single_article';
					}
					else if($what=='h'){
						$this->template->title =  $SConfig->dirname . ' Artikel';
						$type='article';	
					}
					
				}
				else if($type=='produk'){
					
					if($what=='id'){
						$this->template->dir = get_detail_post();						
						$this->template->title =  $this->template->dir->post_title.' | '.$SConfig->dirname . ' Produk';						
						$type='single_product';
					}
					else if($what=='h'){
						$this->template->title =  $SConfig->dirname . ' Produk';
						$type='product';
					}
					
				}	
			}
			else{
				if($type=='artikel'){				
					$this->template->title =  $SConfig->dirname . ' Artikel';
					$type='article';
				}
				else if($type=='produk'){
					$this->template->title =  $SConfig->dirname . ' Produk';
					$type='product';
				}				
			}




			$this->main->view_themes('dir/'.$type,$data, TRUE);
		}
		
		
		else{
			$data = array();
			
			$this->main->view_themes('index',$data, TRUE);
		}
	}	






}

/* End of file front.php */
/* Location: ./application/controllers/front.php */
