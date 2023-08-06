<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Main {
	
	/* 	di init_main :	ada 1 query sql yang berjalan, 
		ketika menampilkan domain ... 
	*/

	var $backend_themes_path 	= '../../../template/back/';
	var $frontend_themes_path 	= '../../../template/front/';
	var $themes 				= 'newdefault';
	var $isadmin				= 0;
	var $domain_active 			= NULL;
	var $host 					= NULL;			
	var $allSetAtr 				= NULL;
	var $allAtr 				= NULL;
	var $htmlfile 				= FALSE;
	var $layout 				= NULL; 
	var $domainatr 				= NULL;
	var $isHome 				= FALSE;
	var $isCategory 			= FALSE;
	var $isCategoryProduct 		= FALSE;
	var $isPage 				= FALSE;
	var $isSingle 				= FALSE;
	var $isSingleProduct 		= FALSE;
	var $isSearch 				= FALSE;
	var $forTitle 				= NULL;
	var $salt_length			= 10;
	var $store_salt				= FALSE;
	var $tempforeach			= NULL;
	
	/* for admin */
	var $curBlogID				= 0;
	
	function init_main(){
		/* get active domain */
		$this->host = explode('.', $_SERVER['HTTP_HOST'], 2);
		
		
		/* get all setting */

		if($this->host[0] != 'www'){
			$this->domain_active = 'www.'.$this->host[0].'.'.$this->host[1];
		}
		else{
			$this->domain_active = $this->host[0].'.'.$this->host[1];	
		}

		$atr = $this->getDomAtr($this->domain_active);
		$this->allAtr = $atr;
		
		/* jika settingan tidak kosong */
		if(!empty($atr)){
			/* ini digunakan atribut web di luar member, web utama kaffah */
			$this->allSetAtr = @unserialize(base64_decode($atr->domain_atribute));	
		}
		/* sebaliknya */
		else{
			$this->allSetAtr = NULL;
		}			
	}
	
	/* 	function ini digunakan untuk pengecekan domain apa yang 
		sedang di cek oleh admin akun */
	function curDomAtr(){
		$_this =& get_instance();
		
		/* ambil domain yang sekarang sedang di edit */
		$array_need = array(
								'blogid' => $_this->uri->segment(3)
							);
		
		$_this->session->set_userdata($array_need);
		
	}

	function view_themes($pages, $data=NULL, $front=NULL){
		global $SConfig;
		$_this =& get_instance();
		$allSetAtr = $this->allSetAtr;


		
		/* kalo diakses di bagian depan */
		if($front==TRUE){			
			/* jika settingan template itu ada */
			if((@$this->allAtr->domain_status == 1) && (!empty($this->allAtr->domain_html) || !empty($this->allAtr->domain_atribute ) ) ){
				
				/* jika yang di akses itu bukan market ataupun kaffahbiz depan */
				if(($this->domain_active != $SConfig->domain) && ($this->domain_active != $SConfig->market)){
					/* visitor logging -> untuk statistika */
					$this->visitorLog();
					
					/* ambil custom HTML dari database */
					/* lakukan parsing template position */
					$custom_template = $_this->template->posParsing($this->allAtr->domain_html);
					
					/* jika di database untuk custom template tidak kosong */
					if(!empty($custom_template)){
						// masukkan custom template ke dalam tampilan html */
						$data = array('template' => $custom_template);
						
						/* load melalui view method, lalu bypass data ke bootstrap */
						$_this->load->view('bootstrap', $data); 
					}
					
					/* yang ini template berbasis file */
					else{						
						/* jika web yang di akses ternyata tidak menggunakan template */

						$this->themes = 'none';
						$this->htmlfile = TRUE;
					}
				}
				
				/* jika yang di akses ada market.kaffah / www.kaffah maka */
				else{

					/* jika yang di akses itu halaman, kategori dan artikel */

					if($_this->uri->segment(1) == 'halaman' || $_this->uri->segment(1) == 'artikel'){
						$this->visitorLog();
						
						/* ambil custom HTML dari database */
						/* lakukan parsing template position */
						$custom_template = $_this->template->posParsing($this->allAtr->domain_html);
						
						/* jika di database untuk custom template tidak kosong */
						if(!empty($custom_template)){
							// masukkan custom template ke dalam tampilan html */
							$data = array('template' => $custom_template);
							
							/* load melalui view method, lalu bypass data ke bootstrap */
							$_this->load->view('bootstrap', $data); 
						}
						
						/* yang ini template berbasis file */
						else{						
							/* jika web yang di akses ternyata tidak menggunakan template */

							$this->themes = 'none';
							$this->htmlfile = TRUE;
						}						
					}
					else{
						$template = $allSetAtr['template']['front_template'];	
						$this->themes = $template;	
						$this->htmlfile = TRUE;	
					}
					
				}
					
			}
			
			/* jika url website yang di akses itu ternyata tidak ada */
			else{								
				if($this->allAtr->domain_status == 1){
					$this->themes = 'none-act';
					$this->htmlfile = TRUE;
				}
				else if($this->allAtr->domain_id > 0){
					$this->themes = 'none-act2';
					$this->htmlfile = TRUE;
				}
				else{
					$this->themes = 'none';
					$this->htmlfile = TRUE;	
				}
				
			}
		}
		
		/* kalo di akses di bagian belakang maka secara otomatis kedudukan adalah admin */
		else{
			$this->isadmin();	
			$this->htmlfile = TRUE;
		}
		
		/* jika fix menggunakan file html */
		if($this->htmlfile == TRUE){
			/* sesuaikan tampilan dengan pengaksesan backend atau front end */
			$this->isadmin ? $this->layout = $this->backend_themes_path : $this->layout = $this->frontend_themes_path ;
			$_this->load->view($this->layout.$this->themes.'/'.$pages.EXT, $data);	
		}
	}
	
	function getDomAtr($blogname=NULL,$blogid=NULL){
		$_this =& get_instance();
		
		/* dapatkan settingan template dari blog yang sedang di buka saat ini */
		if(!empty($blogname)){
			$_this->db->from('kp_domain')->where('domain_name', $blogname)->join('kp_users', 'kp_users.id = kp_domain.user_id');
			$query = $_this->db->get();
			$result = $query->row();
			$result->domain_html = base64_decode(open_template($result->domain_id));
			return $result;
		}

		else if(!empty($blogid)){
			$_this->db->from('kp_domain')->where('domain_id', $blogid)->join('kp_users', 'kp_users.id = kp_domain.user_id');
			$query = $_this->db->get();
			$result = $query->row();
			$result->domain_html = base64_decode(open_template($result->domain_id));
			return $result;
		}
		
		else{
			return FALSE;
		}
	}
	
	function isadmin(){
		$_this =& get_instance();
		$this->isadmin = 1;		
	}
	
	function hash_passwd($password, $passworddb){
		// $password = 'demokaffah';
		// $passworddb2 = '9114508ddfda0fee5f8325687a06e87a97ee9e8d';
		// $salt = substr(md5(uniqid(rand(), true)), 0, 10);
		
		$salt = substr($passworddb, 0, 10);
		return $salt . substr(sha1($salt . $password), 0, -10);		
	}


	function to_password($password, $salt=false){
	    if (empty($password)){
	    	return FALSE;
	    }

	    if ($this->store_salt && $salt){
		    return  sha1($password . $salt);
	    }

	    else{
			$salt = $this->salt();
			return  $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
	    }
	}

	function salt(){
	    return substr(md5(uniqid(rand(), true)), 0, $this->salt_length);
	}


	
	function form_valid_conf(){
		$_this =& get_instance();
		$_this->form_validation->set_message('required', '<b>%s</b> belum diisi ...');
		$_this->form_validation->set_error_delimiters('<div class="error">', '</div>');
	}
	
	function is_logged_in(){
		$_this =& get_instance();
		
		/* 	ini digunakan untuk mengecek apakah user sudah login
			jika belum login atau setengah login maka user akan di 
			tendang keluar dari halaman menuju halaman login
		*/
		if($_this->session->userdata('logged_in') == FALSE){
			$_this->session->sess_destroy();
			redirect(base_url().'login/');
		}
		else{
		}
		
	}

	function is_reseller(){
		$_this =& get_instance();
		$_this->load->model('Site_model');				
		$domainatr = $_this->Site_model->getDomainAtr($_this->main->getUser('dom_id'),$_this->main->getUser('user_id'));		
		if(empty($domainatr['domain_reseller'])){
			redirect(base_url().'site/member');
		}		
	}

	function is_free_packet(){
		$_this =& get_instance();
		$_this->load->model('Site_model');				
		$domainatr = $_this->Site_model->getDomainAtr($_this->main->getUser('dom_id'),$_this->main->getUser('user_id'));		
		if($domainatr['packet_id'] < 2){
			redirect(base_url().'site/member');
		}
	}

	function is_superadmin_logged_in(){
		$_this =& get_instance();
		if($_this->session->userdata('group') == 'superadmin'){

		}
		else if($_this->session->userdata('logged_in') == TRUE){			
			redirect(base_url().'site/member/'.$_this->session->userdata('dom_id').'/#dashboard');						
		}	
		else{
			$_this->session->sess_destroy();
			redirect(base_url().'login/');
		}	
	}
	
	function getUser($whatto){
		$_this =& get_instance();
		return $_this->session->userdata($whatto);
	}
	
	function getDomainAtr(){
		$_this =& get_instance();
		/* ketika sudah di akses dalaman domain website */
		if($_this->uri->segment(3)){
			$_this->load->model('Site_model');
			
			/* 	ambil semua atribut domain */
			/* 	jadikan domainatr sebagai variable penampung 
				yang nantinya bisa di akses dari mana saja */
			$this->domainatr = $_this->Site_model->getDomainAtr($_this->uri->segment(3),$this->getUser('user_id'));
			
			/* jika domainnya tidak sesuai dengan kepemilikan maka logout */
			if(!$this->domainatr){
				redirect(base_url().'logout');
			}			
		}

	}
	
	function visitorLog($onadm=FALSE){
		$_this =& get_instance();	
		$_this->load->library('user_agent');

		if($onadm == TRUE){
			$blog = $_this->session->userdata('dom_id');
		}
		else{
			$blog = $this->allAtr->domain_id;	
		}
		
		if($_this->agent->is_robot()){

		}
		
		else if((!$_this->session->userdata('user_online')) || ($onadm == TRUE)){
			$sessId = $_this->session->userdata('session_id');
			
			$ip = $_SERVER['REMOTE_ADDR'];
			//$ip = '36.69.74.146';
			$date = date('Y-m-d H:i:s');
			$agent = $_this->agent->agent_string();
			(!empty($_SERVER['HTTP_REFERER'])) ? $reff = $_SERVER['HTTP_REFERER'] : $reff = '';
			
			@$var = file_get_contents("http://ip-api.com/json/$ip");
			$var = json_decode($var);

			$visitorLogs = array(
					'VisBlog_id' => $blog, 
					'VisIP' => $ip, 
					'VisRef' => $reff, 
					'VisDate' => $date, 
					'VisAgent' => $agent, 
					'VisSess' => $sessId,
					'VisCity' => isVal(@$var->city), 	
					'VisState' => isVal(@$var->countryCode), 	
					'VisCountry' => isVal(@$var->country), 	
					'VisLon' => isVal(@$var->lon),
					'VisLat' => isVal(@$var->lat),
					'VisOrg' => isVal(@$var->org),
					'VisSearchKeyword' => ''	
			);
								
			$_this->db->insert('kp_visitor_logs', $visitorLogs);
			$_this->session->set_userdata(array('user_online' => $_this->session->userdata('session_id') ));			
		}

		
		return TRUE;		
	}

	/* for uploading */
	function create_tmb_img($source, $size){
		$_this =& get_instance();
		
		/* thumbnail creation */
		$config['image_library'] = 'gd2';
		$config['source_image'] = $source;
		$config['create_thumb'] = TRUE;
		$config['maintain_ratio'] = TRUE;
		
		if($size == "big"){
			$config['width'] = 240;	
			$config['height'] = 200;
		}
		
		else{
			$config['width'] = 80;	
			$config['height'] = 80;			
		}
		
		$_this->image_lib->clear();
		$_this->image_lib->initialize($config);
		$_this->image_lib->resize();
		$_this->image_lib->clear();
		return;
	}
	
	function create_dir(){
		global $SConfig;
		$_this =& get_instance();
		
		$datepath = date('d-M-Y');
		$yeardir = date('Y');
		$monthdir = date('M');
		$datedir = date('d');
		// $path --> "d:/xampp/htdocs/kaffahv3";
		$path = $SConfig->docroot.'/uploads';
		
		
		/* jika folder user ada */
		if(is_dir($path.'/'.$yeardir.'/'.$monthdir.'/'.$datedir.'/'.$this->getUser('user_id'))){
			if(!is_dir($path.'/'.$yeardir.'/'.$monthdir.'/'.$datedir.'/'.$this->getUser('user_id').'/')){
				mkdir($path.'/'.$yeardir.'/'.$monthdir.'/'.$datedir.'/'.$this->getUser('user_id'), 0755);
				touch($path.'/'.$yeardir.'/'.$monthdir.'/'.$datedir.'/'.$this->getUser('user_id').'/'.'index.php');
			}
		}
		
		else{
			mkdir($path.'/'.$yeardir, 755);
			touch($path.'/'.$yeardir.'/'.'index.php');
			mkdir($path.'/'.$yeardir.'/'.$monthdir, 755);
			touch($path.'/'.$yeardir.'/'.$monthdir.'/'.'index.php');
			mkdir($path.'/'.$yeardir.'/'.$monthdir.'/'.$datedir, 755);
			touch($path.'/'.$yeardir.'/'.$monthdir.'/'.$datedir.'/'.'index.php');
			mkdir($path.'/'.$yeardir.'/'.$monthdir.'/'.$datedir.'/'.$this->getUser('user_id'), 0755);
			touch($path.'/'.$yeardir.'/'.$monthdir.'/'.$datedir.'/'.$this->getUser('user_id').'/index.php');
		}
		
	}
	
	function media_upload_config(){
		global $SConfig;
		$_this =& get_instance();		
		$path = $SConfig->docroot.'/uploads/';
		$datepath = date('d-M-Y');
		$yeardir = date('Y');
		$monthdir = date('M');
		$datedir = date('d');
		$realpath = $path.'/'.$yeardir.'/'.$monthdir.'/'.$datedir.'/'.$_this->main->getUser('user_id');
		
		$config['upload_path'] = $realpath;
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['max_size']	= '1000';
		$config['max_width']  = '3000';
		$config['max_height']  = '3000';
		
		return $config;		
	}
	
	function resize_img($image=NULL, $width=NULL, $height=NULL, $type=NULL){
		global $SConfig;
		$_this =& get_instance();
		$_this->load->library('image_lib'); 
		
		/* definite globalvar */
		$docroot = $SConfig->docroot;
		$siteurl = $SConfig->siteurl;
		
		/* jika kosong maka jadikan nilai default */
		(!empty($width)) ? $width_image = $width : $width_image = 75;
		(!empty($height)) ? $height_image = $height : $height_image = 50;
		
		/* directory replace */
		$http_replace = str_replace('http://', '', $image);
		
		/* get domain name */
		$domain = substr($http_replace, 0, strpos($http_replace, '/'));

		/* search '/' */
		$slash_search = strpos($http_replace, '/');
		
		/* change path to directory */
		$directory = $docroot.substr($http_replace, $slash_search);
		
		/* change files name to new name */
		$get_latest_slash = strrpos($directory, '/');
		$file_name = substr($directory,	$get_latest_slash+1 );
		$extension = substr($file_name, strrpos($file_name, '.'));
		$file_name_without_ext = substr($directory,	$get_latest_slash+1, strrpos($file_name, '.') );
		$new_name = $file_name_without_ext.'_'.$width_image.'x'.$height_image.$extension;
		
		/* path baru */
		$new_path = str_replace($file_name, $new_name, $directory);
		
		/* new url */
		$new_url = str_replace($docroot,'http://'.$domain, $new_path);
		
		@$file_is_exist = @read_file($new_path);
		
		if($file_is_exist == TRUE){
			
			return $new_url;
		}
		
		/* else if(empty($file_is_exist)){
			if($type=='people'){
				return $SConfig->df_people;
			}
			else{
				return $SConfig->df_product;
			}
			
		}*/
		
		else{
			/* configuration */
			$config['image_library'] = 'gd2';
			$config['source_image']	= $directory;
			$config['create_thumb'] = TRUE;
			$config['thumb_marker'] = '';
			$config['maintain_ratio'] = TRUE;
			
			if(read_file($config['source_image'])){
				$img_size = getimagesize($config['source_image']);
				$t_ratio = $width/$height;
		      	$o_width = $img_size[0];
		      	$o_height = $img_size[1];
			
				if ((!empty($img_size)) && ($t_ratio > $o_width/$o_height)){
					$config['width'] = $width;
					$config['height'] = round( $width * ($o_height / $o_width));
					$y_axis = round(($config['height']/2) - ($height/2));
					$x_axis = 0;
				}
				else{
					$config['width'] = round( $height * ($o_width / $o_height));
					$config['height'] = $height;
					$y_axis = 0;
					$x_axis = round(($config['width']/2) - ($width/2));
				}				
			}
			
			else{
					$config['width'] = $width;
					$config['height'] = $height;
					$y_axis = 0;
					$x_axis = round(($config['width']/2) - ($width/2));				
			}

	  		
			$config['new_image'] = $new_path;
			
			/* load library image */
			$_this->image_lib->clear();
			$_this->image_lib->initialize($config);
			
			/* jika tidak ada masalah maka lakukan resize */
			$_this->image_lib->resize();
			
			$source_img01 = $config['new_image'];
			$config['image_library'] = 'gd2';
			$config['source_image'] = $source_img01;
			$config['create_thumb'] = false;
			$config['maintain_ratio'] = false;
			$config['width'] = $width;
			$config['height'] = $height;
			$config['y_axis'] = $y_axis ;
			$config['x_axis'] = $x_axis ;
			
			$_this->image_lib->clear();
			$_this->image_lib->initialize($config);
			$_this->image_lib->crop();
			
	
			/* return value */			
		}
		return $new_url;
	}
		
}


/* End of file site.php */
