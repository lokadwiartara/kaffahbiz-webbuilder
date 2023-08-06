<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->main->init_main();
		$this->main->is_logged_in();
		$this->main->getDomainAtr();
	}
	/*
	public function act(){
		$post = $this->input->post();
		switch($post['page']){
			case "adminnotif": $this->adminnotif(); break;
			default: break;
		}
	}
	*/

	public function tes_password(){		
		$this->load->library('Passwd');
		
		/* 2599a2dc6f669b90cef8da84cfbc8c5ff6a9cc7b */
		/* pembuatan password 
		$password = 'padasuatuhari';
		$topassword = $this->passwd->to_password($password);
		echo $topassword;
		*/
		
		// $password = 'padasuatuhari';
		// $topassword = $this->passwd->to_password($password);
		// echo $topassword;
		
		/* pengecekan password */
		/* password yang ada dalam database di ambil */
		$passworddb = 'e2693a5409cee38614dd2f6bb9600472fd8d6faa' ;
		
		/* password yang dikirimkan di form */
		$password = 'padasuatuhari'; /* input post */
		$passwordhash = $this->passwd->hash_passwd($password, $passworddb);
				
		/* strcasecmp */
		 if(strcasecmp($passwordhash, $passworddb) == 0){
		 	echo 'BENAR';
		 }
		 else{
		 	echo 'SALAH';
		 }
	}	
	
	public function passtes(){
		$password = $this->main->to_password('demokaffah');
		echo $password;
	}

	public function tes(){
		// $html = '<kaffah_post_meta attr="time" s="d" />';
		$html = '<kaffah_widget s="TES"><h1>TESTES</h1></kaffah_widget>';	
		
		$doc = new DOMDocument();
		@$doc->loadHTML($html);

		$tags = $doc->getElementsByTagName('kaffah_widget');

		foreach ($tags as $tag) {
		       echo $tag->getAttribute('s');
		}
	}
	
	public function member(){
		
		global $SConfig;		

		/* 	ketika yang di akses itu adalah domain panel www.kaffah.co 
			maka masuk ke dalam halaman admin panel
		*/
		if(@$this->main->allAtr->domain_name == $SConfig->panel){
			
			if(($this->uri->total_segments() == 2) && ($this->main->getUser('group') == 'superadmin')){
				$data = array();
				$this->main->view_themes('superadmin', $data);				
			}

			else if($this->uri->segment(3)){
				
				/* 	ini digunakan untuk mengetahui blog 
					yang saat ini sedang di akses */
				$this->main->curDomAtr();
				
				/*  ketika url fragment full,
					maka tampilkan template full */
				if($this->uri->segment(4) == 'full'){										
					$data = array();
					$this->main->view_themes('full', $data);						
				}
				
				/* selain daripada itu maka tampilkan seperti biasa */
				else{
					$data = array();
					$this->main->view_themes('admin', $data);	

				}
						
			}
			
			/* 	tampilkan halaman landing page dengan 
				tampilan daftar blog yang sudah di buat */
			else{
				redirect(base_url().'site/member/'.$this->main->getUser('dom_id').'/#dashboard');
			}			
		
		}
		
		/* jika admin di akses di domain lain, maka arahkan ke domain panel */
		else{
			redirect(base_url().'site/member/'.$this->main->getUser('dom_id').'/#dashboard');
		}

		
	}

	public function member_registered(){
		$this->main->view_themes('member_registered');
	}

	public function web(){
		$this->main->view_themes('web');
	}

	public function productlist(){
		$this->main->view_themes('productlist');
	}

	public function articlelist(){
		$this->main->view_themes('articlelist');
	}

	public function transactionlist(){
		$this->main->view_themes('transactionlist');
	}	

	public function memberlist(){
		$this->main->view_themes('memberlist');
	}			
	
	public function adminnotif(){
		$this->main->view_themes('adminnotif');
	}

	/* bagian dashboard panel */
	public function dashboard(){
		$this->main->view_themes('dashboard');
	}

	/* bagian dashboard panel */
	public function setting_verify(){
		$this->main->view_themes('setting_verify');
	}	
	
	/* bagian artikel */
	public function article(){
		$data = array();
		$this->main->view_themes('article', $data);
	}	
	
	public function statistic(){
		$data = array();
		$this->main->view_themes('statistic', $data);
	}
	
	public function article_draft(){
		$data = array();
		$this->main->view_themes('articledraft', $data);
	}
	
	public function article_publish(){
		$data = array();
		$this->main->view_themes('articlepublish', $data);
	}
	
	public function editarticle(){
		$data = array();
		$this->main->view_themes('editarticle', $data);		
	}
	
	public function newarticle(){
		$data = array();
		$this->main->view_themes('newarticle', $data);		
	}	
	
	public function cat_article(){
		$data = array();
		$this->main->view_themes('cat_article', $data);		
	}
	
	/* bagian halaman */
	public function page(){
		$data = array();
		$this->main->view_themes('page', $data);		
	}
	
	public function editpage(){
		$data = array();
		$this->main->view_themes('editpage', $data);		
	}
	
	public function newpage(){
		$data = array();
		$this->main->view_themes('newpage', $data);		
	}	
	
	/* bagian produk */
	public function cat_product(){
		$data = array();
		$this->main->view_themes('cat_product', $data);		
	}
	
	public function product(){
		$data = array();
		$this->main->view_themes('product', $data);		
	}
	
	public function newproduct(){
		$data = array();
		$this->main->view_themes('newproduct', $data);		
	}
	
	public function editproduct(){
		$data = array();
		$this->main->view_themes('editproduct', $data);		
	}

	public function order(){
		$data = array();
		$this->main->view_themes('order', $data);		
	}	
	
	public function confirmation(){
		$data = array();
		$this->main->view_themes('confirmation', $data);
	}
	
	public function product_publish(){
		$data = array();
		$this->main->view_themes('productpublish', $data);
	}

	public function product_draft(){
		$data = array();
		$this->main->view_themes('productdraft', $data);
	}
	
	
	public function media(){
		$data = array();
		$this->main->view_themes('media', $data);		
	}
	
	public function user(){
		$data = array();
		$this->main->view_themes('user', $data);		
	}
	
	public function comment(){
		$data = array();
		$this->main->view_themes('comment', $data);		
	}
	
	public function comment_publish(){
		$data = array();
		$this->main->view_themes('commentpublish', $data);		
	}
	
	public function comment_pending(){
		$data = array();
		$this->main->view_themes('commentpending', $data);		
	}			

	public function template(){
		$data = array();
		$this->main->view_themes('template', $data);		
	}

	public function edittemplate(){		
		$data = array();
		$this->main->view_themes('edittemplate', $data);		
	}
	public function module(){
		$data = array();
		$this->main->view_themes('module', $data);		
	}
	
	public function article_setting(){
		$data = array();
		$this->main->view_themes('articlesetting', $data);		
	}
	
	public function template_setting(){
		$data = array();
		$this->main->view_themes('templatesetting', $data);		
	}
		
	public function config(){
		$this->main->view_themes('config');
	}
		
	public function config_seo(){
		$this->main->view_themes('config_seo');
	}
	
	public function config_estore(){
		$this->load->helper('provkotkab_helper');
		$this->load->helper('form');
		$this->main->view_themes('config_estore');
	}
	
	public function config_comment(){
		$this->main->view_themes('config_comment');
	}
	
	public function config_content(){
		$this->main->view_themes('config_content');
	}
	
	public function configg(){
		$this->main->view_themes('configg');
	}

	public function reseller_product(){
		$this->load->model('Site_model');
		$domainatr = $this->Site_model->getDomainAtr($this->main->getUser('dom_id'),$this->main->getUser('user_id'));							
		if(!empty($domainatr['domain_reseller'])){
			$this->main->view_themes('reseller_product');
		}
		else{
			$this->main->view_themes('redirect');
		}
	}

	public function order_reseller(){
		$this->load->model('Site_model');
		$domainatr = $this->Site_model->getDomainAtr($this->main->getUser('dom_id'),$this->main->getUser('user_id'));			
		if(!empty($domainatr['domain_reseller'])){		
			$this->main->view_themes('order_reseller');
		}
		else{
			$this->main->view_themes('redirect');
		}
	}

	public function admin_order_reseller(){
		global $SConfig;
		if($SConfig->storeID == $this->main->getUser('dom_id')){
			$this->main->view_themes('admin_order_reseller');
		}		
	}

	public function admin_commision(){
		global $SConfig;
		if($SConfig->storeID == $this->main->getUser('dom_id')){
			$this->main->view_themes('admin_commision');
		}
	}	

	public function commision(){
		$this->load->model('Site_model');
		$domainatr = $this->Site_model->getDomainAtr($this->main->getUser('dom_id'),$this->main->getUser('user_id'));			
		if(!empty($domainatr['domain_reseller'])){		
			$this->main->view_themes('commision');
		}		
		else{
			$this->main->view_themes('redirect');
		}
	}			
	
	public function temp(){
		$template = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title><kaffah:blog.title/></title>
<meta name="" content="">
<script type="text/javascript">
</script>
</head>
<body style="background:#fff;color:#000; font-family:arial;font-size:12px;margin:20px;">
	<h1 style="margin:0;padding:0;">DEMO.KAFFAH.BIZ</h1>
	<p style="margin:0;padding:0;">is underconstruction with new algorithm ...</p>
	
	<kaffah:loop>
		<h1><kaffah:blog.title/></h1>	
	</kaffah:loop>
	
</body>
</html>';

		$array_template_setting = array(
							'template' => array('front_template' => 'default', 'custom_template' => $template),
							'modul' => array()
							);
							
		$template_setting = base64_encode(serialize($array_template_setting));
		
		$array_update = array('domain_atribute' => $template_setting);
		$this->db->where('domain_id', 2);
		$this->db->update('kp_domain',$array_update);
	}

	
}

/* End of file site.php */
/* Location: ./application/controllers/site.php */