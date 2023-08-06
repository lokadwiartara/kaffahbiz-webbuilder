<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Req extends CI_Controller {
	
	/* kelas ini di buat dengan fungsi yang tidak berhubungan 
	langsung dengan site admin atau front */
	function __construct(){
		parent::__construct();
		$this->main->init_main();
	}
	
	function status_login(){
		$json_array = array(
							'user_id' => $this->main->getUser('user_id'),
							'email' => $this->main->getUser('email'),
							'name' => $this->main->getUser('name'),
							'logged_in' => $this->main->getUser('logged_in')
						);
		
		echo json_encode($json_array);
	}
	
	function forgot_pass(){
		global $SConfig;
		$this->load->model('Site_model');
		$this->load->library('form_validation');
		
		if(@$this->main->allAtr->domain_name == $SConfig->panel){
			/* 	masukkan array post ke dalam variable post */
			$post = $this->input->post();
			$this->main->form_valid_conf();
			
			if($this->uri->segment(2) == 'success'){
				$data = array(); 
				$this->main->view_themes('forgot_pass_succ',$data);
			}
			else if($this->uri->segment(2) == 'failed'){
				$data = array(); 
				$this->main->view_themes('forgot_pass_fail',$data);
			}
			else{
				/* jika validasi tidak sesuai dengan kriteria */
				if($this->form_validation->run('forgot_pass') == FALSE){	
					$data = array(); 
					$this->main->view_themes('forgot_pass',$data);  	
				}
				else{				
					$this->load->model('Global_model');	
					$this->load->library('passwd');
					$randcode = getRandomWord(6);
					$email = $post['email'];

					$date = date('Y-m-d H:i:s');
					$domainatr = $this->main->allAtr ;				
					

					if($this->Global_model->update(array('email' => $post['email']), array('password' => $this->passwd->to_password($randcode) ), 'kp_users')){
						/* 3. kirim email di sini tempatnya */

						$website = $domainatr->domain_name;
						$email = $post['email'];
						$activation_code = $randcode ;
						$emailreply = 'noreply@kaffah.biz'; 
						$password = $randcode;

						// $regdisplay .= "register1.pl $website $email $password $activation_code $emailreply";
						/* kirim email di sini tempatnya OpJRlN */  
						$output=shell_exec("perl /var/www/kaffahbiz/temp/lupa_password.pl $website $email $password $emailreply");	

						// $regdisplay = '<p>Silahkan cek email Anda, password baru sudah dikirimkan...</p>';	
						// $regdisplay .= "lupa_password.pl $website $email $password $emailreply";
						// echo $regdisplay;
						redirect('/forgot_pass/success');
						
					}
					else{
						redirect('/forgot_pass/failed');
					}	
				}				
			}		
		}
		else{
			redirect('http://'.$SConfig->panel.'/login/');
		}		
	}

	function check_email($str){		

		$this->load->model('site_model');				

		/* pengecekan jika email sudah terdaftar di website yang ditempati */
		$detil = $this->site_model->checkuser(array('kp_users.email' => $str));

		if(count($detil) > 0){
			
			return TRUE;			
		}

		/* jika email belum terdaftar maka langkah selanjutnya adalah mengecek hubungannya ... */
		else{

			$this->form_validation->set_message('check_email', 'mohon maaf, %s tidak terdaftar');
			return FALSE;
		}

	}




	/* login request */
	function login(){
		global $SConfig;
		$this->load->model('Site_model');
		$this->load->model('Global_model');

		$this->load->library('form_validation');
		
		if(@$this->main->allAtr->domain_name == $SConfig->panel || @$this->main->allAtr->domain_name == $SConfig->store){
			/* jika sudah login maka di arahkan ke dashboard admin */
			if($this->main->getUser('logged_in') == TRUE) redirect('/site/member') ;
		
			/* 	masukkan array post ke dalam variable post */
			$post = $this->input->post();
			$this->main->form_valid_conf();
			

			print_r($post);

			/* jika validasi tidak sesuai dengan kriteria */
			if($this->form_validation->run('login') == FALSE){	
				$data = array(); 
				$this->main->view_themes('login',$data); 	
			}
			else{
				/* 	username dan password */
				$email = $post['email'];
				$password = $post['password'];
				
				/* 	tampilkan password yang di dapatkan dari database lewat site_model */
				$userlogin = $this->Site_model->login($email);
				
				/* 	save password from db to variable */
				$passworddb = $userlogin->password;
				
				/* 	hash password */
				$passwordhash = $this->main->hash_passwd($password, $passworddb);
				
				/* 	jika tidak ada perbedaan antara yang di masukkan dalam database 
					dan di masukkan dalam form */
				if(strcasecmp($passwordhash, $passworddb) == 0){
				
					/* yang di perlukan untuk tiap halaman user */
					$array_logged_in = array(
												'user_id' => $userlogin->id,
												'email' => $userlogin->email,
												'name' => $userlogin->name,
												'dom_id' => $userlogin->domain_id,
												'dom_not' => $userlogin->domain_notify,
												'dom_packet' => $userlogin->packet_id,
												'logged_in' => TRUE,
												'group' => $userlogin->group,
												'dom_reseller' => $userlogin->domain_reseller
											);
											
					/* keperluan akses tiap halaman */
					$this->session->set_userdata($array_logged_in);								

					$this->main->visitorLog(TRUE);
					
					if($userlogin->domain_id == NULL){
						$this->session->sess_destroy();
						redirect('http://'.$SConfig->panel.'/login/');
					}

					else if($this->main->getUser('group') == 'superadmin'){
						redirect(base_url().'site/member/#web');
					}

					else{
						/* verify http://www.kaffah.biz/site/member/1/#setting_verify */
						$user_detail = 	$this->Global_model->select_single(array('kp_user_detail.user_id' => $this->main->getUser('user_id')),'kp_user_detail');	

						if(!empty($user_detail['noktp']) && !empty($user_detail['scanktp']) && !empty($user_detail['scanid']) && ($user_detail['user_verify'] != 0)){
							redirect(base_url().'site/member/'.$array_logged_in['dom_id'].'/#dashboard');	
						}
						else{
							redirect(base_url().'site/member/'.$array_logged_in['dom_id'].'/#setting_verify');		
						}

						
					}
					

					
				}
				else{
					/* keperluan untuk menampilkan error yang terjadi */
					set_form_error(array('error_msg'=>'Mohon maaf, Anda salah menginputkan <strong>email atau password</strong> , Silahkan coba kembali!'));
					redirect(base_url().'login/');
				}			
			}
		}
		else{
			redirect('http://'.$SConfig->panel.'/login/');
		}
	}
	
	function logout(){
		$this->session->sess_destroy();
		redirect(base_url().'login/');		
	}

	function getcity($blogid=NULL){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		
			$provinsi = $this->input->post('provinsi');

			$this->load->helper('provkotkab_helper');
			
			if(!empty($provinsi)){
				echo json_encode(provinsi_kota($provinsi)) ;
			}
			
		
		}		
	}

	function getpage($blogid=NULL){
	}

	function getallsession(){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$this->load->library('cart');	
			$userdata = $this->session->userdata;
			unset($userdata['session_id']);
			unset($userdata['user_id']);
			unset($userdata['user_online']);
			unset($userdata['ip_address']);
			unset($userdata['user_agent']);
			unset($userdata['user_data']);
			echo json_encode(array('userdata' => $userdata));
		}		
	}

	function getimage(){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$data = $this->input->post('data');
			$width = $this->input->post('width');
			$height = $this->input->post('height');
			$this->load->model('Global_model');
			$image = $this->Global_model->selectwherein($data,'kp_posts','post_image, ID');



			foreach($image as $key => $val){
				foreach($val as $k => $v){
					if($k == 'post_image'){
						$image[$key][$k] = $this->main->resize_img($v,$width,$height,1);	
					}
					else{
						$image[$key][$k] = $v;	
					}
					

				}
			}


			echo json_encode($image);
		}
	}

	/******************************************************************************/
	/******************************************************************************/
	/********************** SUPPPPPPPPPPERRR ADMIN SECTION ************************/
	/******************************************************************************/
	/******************************************************************************/

	function getsuperadminsetting(){
		/* check if superadmin domain is logged in */
		$this->main->is_superadmin_logged_in();

		/* hanya bisa di akses via ajax request */
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {			
			$this->load->model('Site_model');
			$this->load->model('Global_model');	
			$this->load->helper('string');

			$superadmin_setting = $this->Global_model->select_single(array('blog_id' => $this->main->getUser('dom_id'), 'option_name' => 'superadmin_setting'),'kp_options');
			$optval = unserialize(base64_decode($superadmin_setting['option_value']));			

			if(!empty($optval)){
				$status = array('success' => TRUE);
				$all = array_merge_recursive($status,$optval['admin_notif']);
				echo json_encode($all);	
			}
			else{
				echo json_encode(array('success' => FALSE));		
			}
			
		}
	}	

	function superadminsetting(){
		/* check if superadmin domain is logged in */
		$this->main->is_superadmin_logged_in();

		/* hanya bisa di akses via ajax request */
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {			
			$this->load->model('Site_model');
			$this->load->model('Global_model');	
			$this->load->helper('string');	
			$post = $this->input->post();

			/* mulai insert database */
			foreach($post as $key => $val){
				$post[$key] = $val;
			}
			
			$numsetting = $this->Global_model->numrows(array('blog_id' => $this->main->getUser('dom_id'), 'option_name' => 'superadmin_setting'),'kp_options');
			
			if($numsetting > 0){
				$exist_setting = $this->Global_model->select_single(array('blog_id' => $this->main->getUser('dom_id'), 'option_name' => 'superadmin_setting'),'kp_options');
				if(!empty($exist_setting)){
					$optval = unserialize(base64_decode($exist_setting['option_value']));					
					$optval['admin_notif'] = $post;

					$datatoupdate = array(
						'option_value' => base64_encode(serialize($optval)),
					);												

					if($this->Global_model->update(array('blog_id' => $this->main->getUser('dom_id'), 'option_name' => 'superadmin_setting'), $datatoupdate, 'kp_options')){
						echo json_encode(array('success'=>TRUE));
					}
					else{
						echo json_encode(array('success'=>FALSE));
					}										

				}

			}
			else{
				$datatoinsert = array(
						'option_value' => base64_encode( serialize( array('admin_notif' => $post) ) ),
						'option_name' => 'superadmin_setting',
						'blog_id' => $this->main->getUser('dom_id')
					);				

				if($this->Global_model->addNew($datatoinsert, 'kp_options')){
					echo json_encode(array('success'=>TRUE));
				}
				else{
					echo json_encode(array('success'=>FALSE));
				}

			}
		
		}	
	}

	function popupinit(){
		$this->main->is_logged_in(); 
		/* hanya bisa di akses via ajax request */
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {			
			$this->load->model('Site_model');
			$this->load->model('Global_model');	

			/* verify */
			$user_detail = 	$this->Global_model->select_single(array('kp_user_detail.user_id' => $this->main->getUser('user_id')),'kp_user_detail');	

			if(!empty($user_detail['noktp']) && !empty($user_detail['scanktp']) && !empty($user_detail['scanid']) && ($user_detail['user_verify'] != 0)){
				/* notif admin */
				$superadminsetting = $this->Global_model->select_single(array('blog_id' => $this->main->allAtr->domain_id, 'option_name' => 'superadmin_setting'),'kp_options');			
				$setting = unserialize(base64_decode($superadminsetting['option_value']));
				$popupnotif = $setting['admin_notif'];
				$domain_notify = $this->main->getUser('dom_not');
				$packet_id = $this->main->getUser('dom_packet');			



				if($packet_id < 2 && isset($popupnotif['status_notif_for_free'])){					
					echo json_encode(
								array(
									'popup' => 'admin_notif', 
									'judul_notif' => $popupnotif['judul_notif_for_free'],
									'isi_notif' => $popupnotif['isi_html_for_free'],
									'type_notif' => $popupnotif['type_notif_for_free'],
									'sidebar' => $popupnotif['sidebar_for_free'],
									'dashboard' => $popupnotif['dashboard_for_free'],
									'type' => 'free',
									));							
				}

				else if($packet_id < 2 ){					
					echo json_encode(
								array(
									'popup' => 'admin_notif_not_active', 									
									'sidebar' => $popupnotif['sidebar_for_free'],
									'dashboard' => $popupnotif['dashboard_for_free'],
									'type' => 'free',
									));							
				}

				else if($packet_id > 1 && isset($popupnotif['status_notif_for_paid'])){
					echo json_encode(
								array(
									'popup' => 'admin_notif', 
									'judul_notif' => $popupnotif['judul_notif_for_paid'],
									'isi_notif' => $popupnotif['isi_html_for_paid'],
									'type_notif' => $popupnotif['type_notif_for_paid'],
									'sidebar' => $popupnotif['sidebar_for_paid'],
									'dashboard' => $popupnotif['dashboard_for_paid'],
									'type' => 'paid',
									));							
				}

				else if($packet_id > 1 ){					
					echo json_encode(
								array(
									'popup' => 'admin_notif_not_active', 									
									'sidebar' => $popupnotif['sidebar_for_paid'],
									'dashboard' => $popupnotif['dashboard_for_paid'],
									'type' => 'paid',
									));							
				}


			}

			else{
				/* notif admin */
				$superadminsetting = $this->Global_model->select_single(array('blog_id' => $this->main->allAtr->domain_id, 'option_name' => 'superadmin_setting'),'kp_options');			
				$setting = unserialize(base64_decode($superadminsetting['option_value']));
				$popupnotif = $setting['admin_notif'];
				$domain_notify = $this->main->getUser('dom_not');
				$packet_id = $this->main->getUser('dom_packet');			
				
				if($packet_id < 2 && isset($popupnotif['status_notif_for_free'])){	
					echo json_encode(array('popup' => 'verify', 'sidebar' => $popupnotif['sidebar_for_free']));
				}
				else if($packet_id > 1 && isset($popupnotif['status_notif_for_paid'])){
					echo json_encode(array('popup' => 'verify', 'sidebar' => $popupnotif['sidebar_for_paid']));
				}
				else if($packet_id < 2){
					echo json_encode(array('popup' => 'verify', 'sidebar' => $popupnotif['sidebar_for_free'], 'dashboard' => $popupnotif['dashboard_for_free']));
				}

				else if($packet_id > 1 ){
					echo json_encode(array('popup' => 'verify', 'sidebar' => $popupnotif['sidebar_for_paid'], 'dashboard' => $popupnotif['dashboard_for_paid']));
				}
			}
		}		
	}

	function popupsave(){
		$this->main->is_logged_in(); 
		/* hanya bisa di akses via ajax request */
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {	
			$post = $this->input->post(NULL, TRUE);

			if(!empty($post['nama_lengkap']) && !empty($post['facebook_id']) && !empty($post['jenis_kelamin']) && !empty($post['tempat_lahir']) &&
			!empty($post['day']) && !empty($post['month']) && !empty($post['year']) && !empty($post['no_handphone']) && 
			!empty($post['no_telepon']) && !empty($post['provinsi']) && !empty($post['provinsi_kota']) && !empty($post['alamat']) && 
			!empty($post['no_ktp']) && !empty($post['img_ktp']) && !empty($post['img_id']) ){

				$this->load->model('Global_model');
				$nama_lengkap = explode(' ', $post['nama_lengkap'], 2);
				if(count($nama_lengkap) > 1){
					$nama_depan = $nama_lengkap[0];
					@$nama_belakang = $nama_lengkap[1];
				}
				else{
					$nama_depan = $post['nama_lengkap'];
					$nama_belakang = '';
				}

				$array_update = array(
						'nama_depan' => $nama_depan,
						'nama_belakang' => $nama_belakang,
						'jenis_kelamin' => $post['jenis_kelamin'],
						'tempat_lahir' => $post['tempat_lahir'],
						'tanggal_lahir' => $post['year'].'/'.$post['month'].'/'.$post['day'],
						'handphone' => $post['no_handphone'],
						'telephone' => $post['no_telepon'],
						'provinsi' => $post['provinsi'],
						'kota' => $post['provinsi_kota'],
						'alamat' => $post['alamat'],
						'facebook' => $post['facebook_id'],
						'noktp' => $post['no_ktp'],
						'scanktp' => $post['img_ktp'],
						'scanid' => $post['img_id'],
						'user_verify' => 2,
					);

				$dom_reseller = $this->main->getUser('dom_reseller');
				if(!empty($dom_reseller)){
					$array_update['bank'] = @$post['bank'];
					$array_update['no_rek'] = @$post['no_rek'];
					$array_update['atas_nama'] = @$post['atas_nama'];
				}

				$array_where = array('user_id' => $this->main->getUser('user_id'));

				if($this->Global_model->update($array_where, $array_update, 'kp_user_detail')){		

					if(!empty($post['password']) && ($post['password'] == $post['cfmpassword'])){
						$this->load->library('passwd');
						$password = $this->passwd->to_password($post['password']);
						$this->Global_model->update(array('id' => $this->session->userdata('user_id') ),array('password' => $password ),'kp_users');
					}	

					echo json_encode(array('success'=>TRUE));
				}
				else{
					echo json_encode(array('success'=>FALSE,'update'=>'yes'));
				}

			}
			else{
				echo json_encode(array('success' => FALSE,'update'=>'no'));
			}			
		}
	}

	/* THIS SECTION IS ABOUT BLOG LIST */

	function domaincheck(){
		/* check if superadmin domain is logged in */
		$this->main->is_superadmin_logged_in();

		/* hanya bisa di akses via ajax request */
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$this->load->model('Site_model');
			$dom = $this->input->post('domain');
			
			/* jika domain tidak kosong */
			if(!empty($dom)){
				$domain = url_title($this->input->post('domain'), '.', TRUE).$this->input->post('domaintld') ;
				$domain = preg_replace("/\.[\.]+/",'.', $domain);
				$domainsplit = explode('.', $domain, 2);
				
				/* domain dalam database selalu di awali dengan www. */
				if($domainsplit[0] == 'www'){
					$fixdomain = $domainsplit[0].'.'.$domainsplit[1];
					$fixdisplay = $domainsplit[0].'.'.$domainsplit[1];
				}
				else{
					$fixdomain = 'www.'.$domainsplit[0].'.'.$domainsplit[1];
					$fixdisplay = $domainsplit[0].'.'.$domainsplit[1];
				}
				
				$domainexist = $this->Site_model->checkdomain($fixdomain);
				
				$json = json_encode(array('domainexist'=>$domainexist, 'fixdomain' => $fixdisplay));
				
				echo $json;			
			}	
		}
	}

	function adddomain(){
		/* check if superadmin domain is logged in */
		$this->main->is_superadmin_logged_in();

		/* hanya bisa di akses via ajax request templatechoose */
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			global $Sconfig;
			$this->load->model('Global_model');
			$this->load->model('Site_model');
			$this->load->library('form_validation');
			
			
			if($this->form_validation->run('add_domain') == FALSE ){	
				echo FALSE;
			}
			
			else{
				$post = $this->input->post();
				
				/* template yang digunakan */
				$template = $this->Site_model->getTemplate($post['templatechoose']);
				
				/* siap-siap masukkan ke dalam database */
				$array_domain = array(
									'domain_name' => 'www.'.$post['domain'].$post['domaintld'],	
									'domain_title' => $post['title'],	
									'domain_desc' => $post['desc'],
									'domain_atribute' => '',	
									'domain_template' => $template['template_name'],	
									'domain_html' => base64_encode($template['template_html']),	
									'domain_modul' => '',
									'domain_activated' => date('Y-m-d'),	
									'domain_verify' => 0,
									'user_id' => $this->main->getUser('user_id'),
								);
				
				/* ketika query berhasil di jalankan */		
				if($this->Global_model->addNew($array_domain, 'kp_domain')) echo TRUE;				
			}
			

			
		}		
	}

	function updatedomain(){
		/* check if superadmin domain is logged in */
		$this->main->is_superadmin_logged_in();

		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			global $SConfig;
		
			$post = $this->input->post();
			$this->load->model('Site_model');
			$this->load->model('Global_model');

			$domain = $post['domain'] ;
			$domain = preg_replace("/\.[\.]+/",'.', $domain);
			$domainsplit = explode('.', $domain, 2);						

			/* domain dalam database selalu di awali dengan www. */
			if($domainsplit[0] == 'www'){
				$fixdomain = $domainsplit[0].'.'.$domainsplit[1];
				$fixdisplay = $domainsplit[0].'.'.$domainsplit[1];
			}
			else{
				$fixdomain = 'www.'.$domainsplit[0].'.'.$domainsplit[1];
				$fixdisplay = $domainsplit[0].'.'.$domainsplit[1];
			}							

			list($rowdomain,$domainexist) = $this->Site_model->checkdomain($fixdomain);				
			
			$arraydomainupdate = array(
					'domain_name' => $domain,
					'packet_id' => $post['packet'],
					'domain_activated' => $post['year_start'].'/'.$post['month_start'].'/'.$post['day_start'] ,
					'domain_expired' => $post['year_expire'].'/'.$post['month_expire'].'/'.$post['day_expire'] ,
					'domain_status' => $post['domain_status'],
					'domain_verify' => $post['domain_verify']
				);

			if(($domainexist > 0) && ($rowdomain->domain_id == $post['id'])){
				// update untuk domain yang sama

				if (strposa($domain, $SConfig->subdomain, 1)) {
                                }
                                /* ketika domainnya dot com */
                                else {
                                    addDomain($domainsplit[1]);
                                }

				
				if($this->Global_model->update(array('domain_id' => $post['id']),$arraydomainupdate,'kp_domain')){
					$this->Global_model->update(array('user_id' => $post['user_id']),array('user_verify' => $post['user_verify']),'kp_user_detail');
					echo json_encode(array('status' => TRUE));
				}

			}	

			else if($domainexist > 0){
				/* ketika domain yang dipost itu subdomain */
				if (strposa($domain, $SConfig->subdomain, 1)) {					    
				} 
				/* ketika domainnya dot com */
				else {						
				    addDomain($domainsplit[1]);
				}
				
					
	
				// update untuk domain yang sama
				if($this->Global_model->update(array('domain_id' => $post['id']),$arraydomainupdate,'kp_domain')){
					
					$this->Global_model->update(array('user_id' => $post['user_id']),array('user_verify' => $post['user_verify']),'kp_user_detail');
					echo json_encode(array('status' => TRUE));
				}	
				else{
					echo json_encode(array('status' => FALSE));
				}				
			}
			
			else if($domainexist > 0){
				echo json_encode(array('status' => FALSE));
			}
			
			
			else{
				// update untuk domain yang sama

				if (strposa($domain, $SConfig->subdomain, 1)) {
                                }
                                /* ketika domainnya dot com */
                                else {
                                    addDomain($domainsplit[1]);
                                }

				
				if($this->Global_model->update(array('domain_id' => $post['id']),$arraydomainupdate,'kp_domain')){
					$this->Global_model->update(array('user_id' => $post['user_id']),array('user_verify' => $post['user_verify']),'kp_user_detail');
					echo json_encode(array('status' => TRUE));
				}	
				else{
					echo json_encode(array('status' => FALSE));
				}

			}			
			
		}				
	}

	function getdomaindetail(){	
		/* check if superadmin domain is logged in */
		$this->main->is_superadmin_logged_in();

		/* if accessing from ajax call */
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {			
			$this->load->model('Site_model');
			$post = $this->input->post();

			$domain_detil = $this->Site_model->getdomaindetail($post['domain_id']);

			$arr = array('status' => TRUE, $domain_detil);
			echo json_encode($arr);			
		}
	}

	function deletedomain(){
		/* check if superadmin domain is logged in */
		$this->main->is_superadmin_logged_in();

		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			global $SConfig;
			
			$this->load->model('Global_model');
			$post = $this->input->post();
			if($this->Global_model->del(array('domain_id' => $post['domain_id']),'kp_domain')){
				$this->Global_model->del(array('blog_id' => $post['domain_id']),'kp_multi_sitelogin');
				$this->Global_model->del(array('comment_blog_id' => $post['domain_id']),'kp_comments');
				$this->Global_model->del(array('blog_id' => $post['domain_id']),'kp_confirmation');
				$this->Global_model->del(array('blog_id' => $post['domain_id']),'kp_options');
				$this->Global_model->del(array('blog_id' => $post['domain_id']),'kp_posts');
				$this->Global_model->del(array('blog_id' => $post['domain_id']),'kp_shipping');
				$this->Global_model->del(array('term_blog_id' => $post['domain_id']),'kp_terms');
				$this->Global_model->del(array('blog_id' => $post['domain_id']),'kp_transaction');
				$this->Global_model->del(array('blog_id' => $post['domain_id']),'kp_trans_detail');
				$this->Global_model->del(array('VisBlog_id' => $post['domain_id']),'kp_visitor_logs');

				echo json_encode(array('status'=>TRUE));
			}
			else{
				echo json_encode(array('status'=>FALSE));
			}
			
		}		
	}

	function activationdomain(){
		/* check if superadmin domain is logged in */
		$this->main->is_superadmin_logged_in();

		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			global $SConfig;				
			$post = $this->input->post();
			$this->load->model('Global_model');
			if($post['action'] == 'pending'){
				if($this->Global_model->update(array('domain_id' => $post['domain_id']),array('domain_status' => 0),'kp_domain')){
					echo json_encode(array('status' => TRUE));
				}
				else{
					echo json_encode(array('status' => FALSE));
				}
			}
			else if($post['action'] == 'publish'){
				if($this->Global_model->update(array('domain_id' => $post['domain_id']),array('domain_status' => 1),'kp_domain')){
					echo json_encode(array('status' => TRUE));
				}		
				else{
					echo json_encode(array('status' => FALSE));
				}			
			}

			
		}				
	}		

	function getalldomain($limit=NULL,$offset=NULL,$mediafilter=NULL,$contentfilter=NULL){
		/* check if superadmin domain is logged in */
		$this->main->is_superadmin_logged_in();

		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			global $SConfig;
			
			$this->load->model('Site_model');
			$domain = $this->Site_model->getalldomain($limit,$offset,$mediafilter,$contentfilter);
			//echo $this->main->getUser('user_id');
			/* jika domainnya ada */

			if(!empty($domain)){
				echo json_encode($domain);	
			}
			else{
				echo json_encode(array('status'=>FALSE));
			}
			
		}		
	}	

	function pagingbloglist($page){
		/* check if superadmin domain is logged in */
		$this->main->is_superadmin_logged_in();

		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			/* jika session belum di isi */
			$pagesession = $this->session->userdata('pagebloglistcurrent');
			
			if(!empty($pagesession)){
				$array_page = array('pagebloglistcurrent' => $page);
				$this->session->set_userdata($array_page);			
			}
			else{
				$array_page = array('pagebloglistcurrent' => $page);
				$this->session->set_userdata($array_page);						
			}
			
			echo json_encode($array_page);
		}
	}

	function bloglistpaginginit($mediafilter=NULL,$contentfilter=NULL){
		/* check if superadmin domain is logged in */
		$this->main->is_superadmin_logged_in();

		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
		
			/* 	algoritma paging berapa banyak */
			$this->load->model('Global_model');

			$where_array = array( 'kp_domain.domain_id >' => 0);

			/* konfigurasi untuk paging */
			$total_rows = $this->Global_model->numrows($where_array,'kp_domain',$mediafilter,$contentfilter);			
			
			$per_page = getconfig('limit');
			$total_page = ceil($total_rows / getconfig('limit')) ;			
			$array_json = array(	
								'banyak_baris' => $total_rows ,
								'per_page'	=>	$per_page,
								'total_page' => $total_page
								);
													
			// echo $total_rows;
			echo json_encode($array_json);			
		}
	}		
	
	function gettransactionalldomain(){
		/* check if superadmin domain is logged in */
		$this->main->is_superadmin_logged_in();

		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			global $SConfig;
			if($this->main->getUser('logged_in') == TRUE && $this->main->getUser('group') == 'superadmin'){			
				$this->load->model('Site_model');
				$post = $this->input->post();		

				$transaction = $this->Site_model->getalltransactioncount($post['domain_id']);

				if(!empty($transaction)){
					echo json_encode($transaction);	
				}	
			}
		}				
	}	

	/* THIS SECTION IS ABOUT PRODUCT LIST */

	function editarticlesa($type,$id){
		/* check if superadmin domain is logged in */
		$this->main->is_superadmin_logged_in();		
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			
			$this->load->model('Post_model');
			$array_json = $this->Post_model->get_article_sa($type,$id);
			
			echo json_encode($array_json);
		}		
	}

	function updatearticlesa(){
		/* check if superadmin domain is logged in */
		$this->main->is_superadmin_logged_in();		
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){	
				$post = $this->input->post();
				$this->load->model('Post_model');
				$this->load->model('Global_model');

				if(!empty($post['kategori_market'])){
					$kategori_market = $post['kategori_market'];
					$market = $this->Post_model->get_market_category_id($kategori_market);	
					$id_kategori_market = $market['market_id'];

					/* ketika market sub di pilih */
					if(!empty($post['kategori_market_sub'])){
						$kategori_market_sub = $post['kategori_market_sub'];
					}

					/* ketika market sub tidak di pilih */
					else{

						if(!empty($post['kategori_market_sub_other'])){
							if($this->Global_model->numrows(array(
																'market_name' => url_title($post['kategori_market_sub_other'],'_', TRUE), 
																'market_parent' => $id_kategori_market
																), 'kp_market') == 0){

								$this->Global_model->addNew(
															array(
																	'market_name' => url_title($post['kategori_market_sub_other'],'_', TRUE),
																	'market_title' => $post['kategori_market_sub_other'],
																	'market_parent' => $id_kategori_market
																), 
															'kp_market');	
							
							}

							$kategori_market_sub = url_title($post['kategori_market_sub_other'],'_', TRUE);							
						}
						else{
							$kategori_market_sub = '';
						}

					}
				}
				else{
					$kategori_market = '';
					$kategori_market_sub = '';
				}

				if(isset($post['pilihan']) && isset($post['hot'])){
					$attribut = 'choice,hot';
				}

				else if(isset($post['pilihan'])){
					$attribut = 'choice';
				}

				else if(isset($post['hot'])){
					$attribut = 'hot';
				}

				else{
					$attribut = '';
				}

				if(isset($post['modeyes'])){
					$modeyes = 1;
				}
				else{
					$modeyes = 0;
				}

				$array_update_article = array(
						'post_name' => url_title($post['produk'],'_', TRUE),
						'post_title' => $post['produk'],
						'post_price' => toMoney($post['harga']),
						'post_stock' => $post['stok'],
						'post_status' => $post['status'],
						'post_market_category' => $kategori_market,
						'post_market_sub_cat' => $kategori_market_sub,
						'post_moderation' => $modeyes,
						'post_market_attribute' => $attribut
					);

				if($this->Global_model->update(array('ID'=>$post['id']),$array_update_article,'kp_posts')){
					echo json_encode(array('success'=>TRUE));	
				}
				else{
					echo json_encode(array('success'=>FALSE));
				}

				

		}
	}	

	function getallarticlesa($type,$status,$limit,$offset,$mediafilter=NULL,$contentfilter=NULL){
		/* check if superadmin domain is logged in */
		$this->main->is_superadmin_logged_in();		
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			$this->load->model('Post_model');
			echo json_encode($this->Post_model->get_all_article_sa($type,$status,$limit,$offset,$mediafilter,$contentfilter));	
		}
	}	

	function mass_updatearticlesa($status){
		/* check if superadmin domain is logged in */
		$this->main->is_superadmin_logged_in();		
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){			
			$this->load->model('Global_model');
			$this->load->model('Post_model');
			$this->load->helper('url');
			$post = $this->input->post();					
			$postid = $post['postid'];	
			$array_where = array('kp_posts.ID >' => 1);

			if($status == 'undir'){
				$array_update = array(
					'kp_posts.post_status' =>  'draft', 
					'post_market_category' => '',
					'post_market_sub_cat' => '',
					'post_moderation' => 1,
					'post_market_attribute' => ''
				);				
			}

			else if($status == 'delete'){
				if($this->Post_model->mass_del_articlesa($postid)){
					echo json_encode(array('status'=>TRUE));
				}
			}

			else{
				$array_update = array('kp_posts.post_status' =>  $status);	
			}
			
			if(!empty($array_update)){
				if($this->Global_model->update($array_where,$array_update,'kp_posts',TRUE,$postid,'kp_posts.ID')){
					echo json_encode(array('status'=>TRUE));
				}	
			}
			
			
		}	
	}

	function getmarketcategory($parent=NULL){
		/* check if superadmin domain is logged in */
		$this->main->is_superadmin_logged_in();		
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			
			$this->load->model('Post_model');
			if(!empty($parent)){
				$market = $this->Post_model->get_market_category_id($parent);	
				$market = $market['market_id'];
			}
			else{
				$market = NULL;
			}
			
			$array_json = $this->Post_model->get_market_category($market);
			
			echo json_encode($array_json);
		}			
	}

	function paginginitsa($type,$status,$mediafilter=NULL,$contentfilter=NULL){
		/* check if superadmin domain is logged in */
		$this->main->is_superadmin_logged_in();		
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
					&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
		
			/* 	algoritma paging berapa banyak */
			$this->load->model('Global_model');
			
			$where_array = array(
									'post_type' => $type,									
								);
			
			/* ketika status tidak ada */
			if($status != '-' && $status != 'null'){
				$where_array['post_status'] = $status;
			}
				
			/* konfigurasi untuk paging */
			$total_rows = $this->Global_model->numrows($where_array,'kp_posts',$mediafilter,$contentfilter);
			$per_page = getconfig('limit');
			$total_page = ceil($total_rows / getconfig('limit')) ;
			
			$array_json = array(	
								'banyak_baris' => $total_rows ,
								'per_page'	=>	$per_page,
								'total_page' => $total_page
								);
			
			/* tampilkan dalam json */							
			echo json_encode($array_json);
		}
	}	

	/* *************************************** */
	/* ************ STATISTIC LIST ************ */
	/* *************************************** */	

	function getStatistic($blogid=NULL,$unique=NULL){
		$this->main->is_logged_in();		
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$this->load->model('Site_model');			
			$statistic = $this->Site_model->getStatistic($this->main->getUser('dom_id'),$unique);
			$display = NULL;
			$db = NULL;
			
			if(!empty($statistic)){
				$display = '[[';
				foreach($statistic as $row){
					$db .= '["'.$row['date'].'",'.$row['total_blog'].'],';
				}
				
				$displayunfix = rtrim($db,',');
				
				$display .= $displayunfix.']]';
			}
			
			echo $display;
		}
	}

	function getallstatistic($blogid){
		$this->main->is_logged_in(); 
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
					&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){

			$this->load->model('Site_model');
			$this->load->helper('inc/parse_user_agent');
			$getbydate = $this->Site_model->get_by_date($this->main->getUser('dom_id'));
			$totalgetbyhour = $this->Site_model->get_by_hour_total($this->main->getUser('dom_id'));
			$totalgetbydate = $this->Site_model->get_by_date_total($this->main->getUser('dom_id'));
			$getbyhour = $this->Site_model->get_by_hour($this->main->getUser('dom_id'));			
			$getgraph = $this->Site_model->getStatistic($this->main->getUser('dom_id'),'unique');

			$display = NULL;
			$db = NULL;
			
			if(!empty($getgraph)){
				$display = '[[';
				foreach($getgraph as $row){
					$db .= '["'.$row['date'].'",'.$row['total_blog'].'],';
				}
				
				$displayunfix = rtrim($db,',');
				
				$display .= $displayunfix.']]';
			}

						

			echo json_encode(array(
					'getbydate' => $getbydate,
					'totalgetbyhour' => $totalgetbyhour,
					'totalgetbydate' => $totalgetbydate,
					'getbyhour' => $getbyhour,
					'getgraph' => $display
				));			

		}				
	}

	function getrecent30($blogid){		
		$this->main->is_logged_in(); 
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
					&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){

			$this->load->model('Site_model');
			$this->load->helper('inc/parse_user_agent');
			$row = $this->Site_model->get_recent_30($this->main->getUser('dom_id'));
			
			$data = array();
			$browser = NULL;
			$platform = NULL;

			foreach($row as $key => $val){
				foreach($val as $valkey => $valval){
					// echo $key.' '.$valkey .' '. $valval .'<br />';
					if($valkey == 'VisAgent'){
						$ua_info = parse_user_agent($valval);

						/* browser switch */
						switch($ua_info['browser']){
							case "Chrome" : $browser = 'chrome'; break;
							case "Safari" : $browser = 'safari'; break;
							case "Firefox" : $browser = 'firefox'; break;
							case "MSIE" : $browser = 'ie'; break;
							case "Opera" : $browser = 'opera'; break;
							default: ;

						}


						/* platform switch */
						switch($ua_info['platform']){
							case "Windows" : $platform = 'windows'; break;
							case "Linux" : $platform = 'linux'; break;
							case "Macintosh" : $platform = 'apple'; break;
							case "Android" : $platform = 'android'; break;
							default: ;
						}

						$data[$key][$valkey] = '<img src="http://www.kaffah.biz/assets/images/flag/'.$platform.'.png" title="'.$platform.'" /><img src="http://www.kaffah.biz/assets/images/flag/'.$browser.'.png" title="'.$browser.'"" />';
					}
					else{
						$data[$key][$valkey] = $valval;	
					}

					
				}				
			}
			
			echo json_encode($data);
		}		
	}



	/* *************************************** */
	/* ************ TEMPLATE LIST ************ */
	/* *************************************** */	

	function choosetemplate(){
		global $SConfig;
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {						
			$this->load->model('Site_model');
			$this->load->model('Global_model');
			// $alltemplate = $this->Site_model->getalltemplate();
			// echo json_encode($alltemplate); 
			
			$post = $this->input->post();
			
			/* get template html */
			$templatedetail = $this->Site_model->getTemplateName($post['templatename']);
			/* update kp_domain atribut */
			
			$array_update = array(
									'domain_template' => $post['templatename'],			
								);
			
			/* save as to template */
			write_template($this->main->getUser('dom_id'), base64_encode($templatedetail['template_html']));
			
			if($templatedetail['template_price'] > 0){
				echo json_encode(array('success' => FALSE, 'status' => 'paid'));
			}
			else{					
				if($this->Global_model->update(array('domain_id' => $this->main->getUser('dom_id')),$array_update,'kp_domain')){
					echo json_encode(array('success' => TRUE));
				}
				else{
					echo json_encode(array('success' => FALSE));
				}
			}									
			
		}
	}

	function getdomain($blogid){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {			
			$this->load->model('Site_model');
			/* ini digunakan untuk menampilkan detil atribut dari kp_domain */
			$domain_atr = $this->Site_model->get_domain_detail($this->main->getUser('dom_id'));
			echo json_encode($domain_atr);			
		}
	}

	function getdompacket(){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			
			$this->load->model('Site_model');
			/* ini digunakan untuk menampilkan detil atribut dari kp_domain */
			$domain_atr = $this->Site_model->get_domain_detail($this->main->getUser('dom_id'));
			if($domain_atr['packet_id'] < 2){
				echo json_encode(array('status' => FALSE));
			}	
			else{
				echo json_encode(array('status' => TRUE, 'url' => base_url().'site/member/'.$this->main->getUser('dom_id').'/full/#edittemplate' ));
			}						
		}		
	}	

	function getalltemplate(){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {					
			$this->load->model('Site_model');
			$alltemplate = $this->Site_model->getalltemplate();
			echo json_encode($alltemplate); 			
		}
	}	

	function savetemplate(){	
		/* jika paket gratisan */		
		$this->main->is_logged_in();
		$this->main->is_free_packet();	
		$this->load->library('template');
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {				

			$this->load->model('Global_model');
			$post = $this->input->post();
			$warn = '';
			$stat = TRUE;

			$checkTemplate = $this->template->checkTemplate($post['template_html']);						

			/* home check */
			if($checkTemplate['home_warning'] != 1){				
				$warn = $checkTemplate['home_warning'];
				$stat = FALSE;
			}	

			/* page check */
			if($checkTemplate['page_warning'] != 1){
				$warn .= $checkTemplate['page_warning'];
				$stat = FALSE;
			}

			/* category check */
			if($checkTemplate['category_warning'] != 1){
				$warn .= $checkTemplate['category_warning'];
				$stat = FALSE;
			}	

			/* category product check */
			if($checkTemplate['category_product_warning'] != 1){
				$warn .= $checkTemplate['category_product_warning'];
				$stat = FALSE;
			}		

			/* single check */
			if($checkTemplate['single_warning'] != 1){
				$warn .= $checkTemplate['single_warning'];
				$stat = FALSE;
			}		

			/* single product check */
			if($checkTemplate['single_product_warning'] != 1){
				$warn .= $checkTemplate['single_product_warning'];
				$stat = FALSE;
			}		

			/* search check */
			if($checkTemplate['search_warning'] != 1){
				$warn .= $checkTemplate['search_warning'];
				$stat = FALSE;
			}							


			if(write_template($this->main->getUser('dom_id'), base64_encode($post['template_html']))  && $stat == TRUE){
				echo json_encode(array('success' => TRUE, 'warn' => $warn));
			}
			else{
				echo json_encode(array('success' => FALSE, 'warn' => $warn));
			}
		}						
	}

	/* *************************************** */
	/* ************ MODULE LIST ************ */
	/* *************************************** */
	function sortirmodule($blogid){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			if($this->main->getUser('logged_in') == TRUE){	
				$post = $this->input->post();
				$this->load->model('Site_model');
				$this->load->model('Global_model');
				
				/* sortir module */
				$sortir = str_replace('Edit | Hapus', '||', $post['param']);
				$fix_sortir = substr($sortir,0,-2);
				$sortir_array = explode('||',$fix_sortir);
				
				/* get active module */
				$activemod = $this->Site_model->getActiveModule($this->main->getUser('dom_id'));
				$allmodule = unserialize(base64_decode($activemod['option_value']));
				$mod4edit = $allmodule;
				
				for($x=0;$x<count($sortir_array);$x++){
					$modulesort[url_title($sortir_array[$x], '_', TRUE)] = array('sort' => $x);
				}
				
				/* modedit dulu baru yang di klik down */
				/* jika module tidak kosong */
				if(isset($mod4edit)){
					foreach($mod4edit as $key => $val){				
						foreach($val as $key2 => $val2){
							foreach($val2 as $key3 => $val3){
								if($key3 == 'sort'){
									$mod4edit[$key][$key2][$key3] = @$modulesort[$key2][$key3];	
								}	
							}
						}			
					}
					
					/* ini digunakan sebagai pemicu untuk update database */
					$where_update_module = 	array(
												'option_name' => 'module_setting',
												'blog_id' => $this->main->getUser('dom_id')
											);						
					
					/* lakukan update module ke dalam database */												
					$this->Global_model->update($where_update_module, 
											array('option_value' => base64_encode(serialize($mod4edit))),
											'kp_options');	
				}				
			}
		}
	}

	function getmodule($blogid){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			if($this->main->getUser('logged_in') == TRUE){		
				/* get domain atribut */
				$this->load->model('Site_model');
				$domain_atr = $this->Site_model->get_domain_detail($this->main->getUser('dom_id'));
				$kaffah_modulepattern = '#<\s*?kaffah_module\b[^>]*>(.*?)</kaffah_module\b[^>]*>#s';
				$kaffah_module = preg_match_all($kaffah_modulepattern, $domain_atr['domain_html'],$kaffahresult);
				
				/* array for sidebar */
				$modulearray = array();
				$moduletemp = array();

				
				if(count($kaffahresult[0]) > 0){
					$doc = new DOMDocument();
					$module = $kaffahresult[0];
					for($x=0;$x<count($module);$x++){
						libxml_use_internal_errors(true);
						$doc->loadHTML($module[$x]);
						libxml_use_internal_errors(false);

						$kaffahmoduleelement = $doc->getElementsByTagName('kaffah_module');
						foreach ($kaffahmoduleelement as $tag) {	
							//if(!array_key_exists($tag->getAttribute('name'), $moduletemp)){
								$modulearray[$x]['name'] = $tag->getAttribute('name');
								$modulearray[$x]['id'] = $tag->getAttribute('id');
							//}						
							
							$moduletemp[$tag->getAttribute('name')] = $tag->getAttribute('name');
						}
					}
							
					$modulearray = super_unique($modulearray,'name');			

					echo json_encode($modulearray);
				
				}
				else{
					echo json_encode(array('warning'=>0));
				}
				
			}
		}		
	}

	function getactivemodule($blogid){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			if($this->main->getUser('logged_in') == TRUE){		
				$this->load->model('Site_model');
				$activemod = $this->Site_model->getActiveModule($this->main->getUser('dom_id'));
				
				//print_r(unserialize($activemod['option_value']));
				/* digunakan untuk melihat module2 yang aktif */
				$allmoduleactive = unserialize(base64_decode($activemod['option_value']));
				
				$keeprow = array();
				foreach($allmoduleactive as $row){
					 uasort($row,"cmp");
					 $keeprow[] = $row;
				}
				
				/* merge array */
				$x = 0;
				foreach($allmoduleactive as $key => $val){
					$allmoduleactive[$key] = $keeprow[$x];
					$x++;
				}
				
				/* tampilkan ke dalam array */				
				echo json_encode($allmoduleactive);
			}
		}		
	}	

	/* module widget space */
	function modulelist($blogid){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			if($this->main->getUser('logged_in') == TRUE){
				$modulename = $this->input->post('modulename');
				echo get_list_function($this->main->getUser('dom_id'),$modulename);
			}	
		}		
	}

	function moduledetail($blogid){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			if($this->main->getUser('logged_in') == TRUE){
				echo get_function($this->main->getUser('dom_id'),$this->input->post('moduladd'));
			}	
		}			
	}

	function addmodule($blogid){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			if($this->main->getUser('logged_in') == TRUE){		
				/* di sini di eksekusi dari setiap module */
				$modulename = $this->input->post('modulename').'_backend' ;
				echo insert_function($this->main->getUser('dom_id'),$modulename,$this->input->post(),$this->input->post('position'));
			}
		}
	}	
	
	function delmodule($blogid){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			if($this->main->getUser('logged_in') == TRUE){	
				$this->load->model('Global_model');
				$this->load->model('Site_model');
			
				$post = $this->input->post();
				$this->load->model('Site_model');
				$activemod = $this->Site_model->getActiveModule($this->main->getUser('dom_id'));
				$activemod_val = unserialize(base64_decode($activemod['option_value']));


				/* hapus module yang di delete tadi via array */
				unset($activemod_val[$post['modulepost']][$post['modulekey']]);								

				/* array module update */
				$where_update_module = 	array(
											'option_name' => 'module_setting',
											'blog_id' => $this->main->getUser('dom_id')
										);						
				
				/* query start update module */							
				$this->Global_model->update($where_update_module, 
										array('option_value' => base64_encode(serialize($activemod_val))),
										'kp_options');						
				
			}
		}
		
		// echo json_encode($post);
	}

	function editmodule($blogid){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			if($this->main->getUser('logged_in') == TRUE){	
				/* initiate */
				$post = $this->input->post();	
				$this->load->model('Site_model');
				$this->load->helper('inflector');
				$activemod = $this->Site_model->getActiveModule($this->main->getUser('dom_id'));
				
				/* tampilkan ke dalam array */
				$allmodule = unserialize(base64_decode($activemod['option_value']));
				
				/* masukkan module yang akan diedit ke dalam array */
				$mod4edit = $allmodule[$post['modulepost']][$post['modulekey']];
				
				
				
				$array_edit = array(
									'modposition' => $post['modulepost'],
									'modpositiontitle' => humanize($post['modulepost']),
									'modname' => $post['modulekey'],
									'html' => edit_function($this->main->getUser('dom_id'),$mod4edit['module'],$post['modulekey'],$mod4edit,$post['modulepost'])
								);
				
				echo json_encode($array_edit);
				
			}
		}		
	}
	
	function updatemodule($blogid){
		$this->main->is_logged_in();
		/* ini digunakan untuk melakukan update module */
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			if($this->main->getUser('logged_in') == TRUE){	
				$modulename = $this->input->post('modulename').'_backend' ;				
				$modulekey = $this->input->post('modulekey');
				echo update_function($this->main->getUser('dom_id'),$modulename,$this->input->post(),$modulekey,$this->input->post('position'));
			}
		}					
	}	

	function savesetting($blogid){
		/* ini digunakan untuk melakukan update module */
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			if($this->main->getUser('logged_in') == TRUE){
				$this->load->model('Site_model');
				$this->load->model('Global_model');	
				$this->load->helper('string');	
				$post = $this->input->post();

				foreach($post as $key => $val){
					$post[$key] = quotes_to_entities(addslashes($val));
				}
				
				/* template setting get */
				$templatesetting = $this->Site_model->getTemplateSetting($this->main->getUser('dom_id'));
				
				if(count($templatesetting) > 0){
					$getoldtemplatesetting = unserialize(base64_decode($templatesetting['option_value']));
					$getoldtemplatesetting['display_setting'] = $post;
					
					$datatoupdate = array(	
											'option_value' => base64_encode(serialize($getoldtemplatesetting))										
										);
					$arraywhere = array(
											'blog_id' => $this->main->getUser('dom_id'),	
											'option_name' => 'template_setting',
										);
					
					if($this->Global_model->update($arraywhere, $datatoupdate, 'kp_options')){
						echo json_encode(array('success'=>TRUE));
					}
					else{
						echo json_encode(array('success'=>FALSE));
					}										
					
				}
				else{
					/* jika kosong tidak ada isinya maka sila di insert */
					$template_setting = array(
											'display_setting' => $post
										);
					
					/* data to insert */
					$datatoinsert = array(
											'blog_id' => $this->main->getUser('dom_id'),	
											'option_name' => 'template_setting',	
											'option_value' => base64_encode(serialize($template_setting))
										);
					
					if($this->Global_model->addNew($datatoinsert, 'kp_options')){
						echo json_encode(array('success'=>TRUE));
					}
					else{
						echo json_encode(array('success'=>FALSE));
					}
					
				}
				
			}
		}		
	}

	function getvaluetag($blogid){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			if($this->main->getUser('logged_in') == TRUE){
				$this->load->model('Site_model');
				$templatesetting = $this->Site_model->getTemplateSetting($this->main->getUser('dom_id'));

				// print_r($templatesetting);

				if($templatesetting){
					$getoldtemplatesetting = unserialize(base64_decode($templatesetting['option_value']));
				}
				else{
					$getoldtemplatesetting['display_setting'] = array();
				}
				
				foreach($getoldtemplatesetting['display_setting'] as $key => $val){
					$getoldtemplatesetting['display_setting'][$key] = stripslashes($val);
				}

				echo json_encode(@$getoldtemplatesetting['display_setting']);
			}
		}		
	}	
	
	function gettemplatetag($blogid){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

			$kaffahe = null;
			if($this->main->getUser('logged_in') == TRUE){	
				$this->load->model('Site_model');

				/* digunakan untuk mengambil semua tag kaffah_e */
				$kaffahepattern = '#<\s*?kaffah_e\b[^>]*/>#s';
				$domain_atr = $this->Site_model->get_domain_detail($this->main->getUser('dom_id'));
				$display = $domain_atr['domain_html'];
				
				/* display domain */
				$doc = new DOMDocument();
				libxml_use_internal_errors(true);
				$doc->loadHTML($display);
				libxml_use_internal_errors(false);
				
				/* kaffah e tag */
				$kaffahetag = preg_match_all($kaffahepattern, $display,$kaffahresult);
				$screenwithtag = $kaffahresult[0];
				
				for($x=0;$x<count($screenwithtag);$x++){
					/* ambil fungsi untuk tag name kaffah e */
					libxml_use_internal_errors(true);
					$doc->loadHTML($screenwithtag[$x]);
					libxml_use_internal_errors(false);
					
					/* pengulangan ini didgunakan untuk mendapatkan satu demi kaffah_e */
					$foreachkaffahe = $doc->getElementsByTagName('kaffah_e');
					
					/* disini digunakan untuk menampilkan hasil dari kaffahe */
					foreach($foreachkaffahe as $atr){	

						if($atr->getAttribute('type') == 'text'){
							$value = str_replace(array('[[',']]'),array('<','>'), $atr->getAttribute('value'));
							$value = strip_tags($value);
						}
						else{
							$value = str_replace(array('[[',']]'),array('<','>'), $atr->getAttribute('value'));
						} 

						$kaffahe[] = array(
											'type' => $atr->getAttribute('type'), 
											'value' => $value,
											'name' => $atr->getAttribute('name'), 
											'desc' => $atr->getAttribute('desc'), 
										);
					
						
					}
					
					
						
				}
				
				
				echo json_encode($kaffahe);
							
			}

			else{
				echo json_encode(array());
			}
		}		
	}

	/* *************************************** */
	/* ************ CONFIG LIST ************ */
	/* *************************************** */

	function savesettingconfigumum($blogid){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			if($this->main->getUser('logged_in') == TRUE){
				$this->load->model('Site_model');
				$this->load->model('Global_model');
				$templatesetting = $this->Site_model->getTemplateSetting($this->main->getUser('dom_id'));
				$post = $this->input->post();
				
				/* jika yang diambil dari database itu ada */
				if($templatesetting){
					/* ambil option value dari template setting */
					$getoldtemplatesetting = unserialize(base64_decode($templatesetting['option_value']));
					
					/* setting yang harus di setting */
					$getoldtemplatesetting['general_setting'] = $post;
					
					/* data yang harus di update */
					$datatoupdate = array(	
											'option_value' => base64_encode(serialize($getoldtemplatesetting))										
										);
										
					/* array yang harus di update */
					$arraywhere = array(
											'blog_id' => $this->main->getUser('dom_id'),	
											'option_name' => 'template_setting',
										);
										
					
					
					if($this->Global_model->update($arraywhere, $datatoupdate, 'kp_options')){
						
						$array_domain_update = array(													
													'domain_title' => $post['judul'],	
													'domain_desc' => $post['tagline']
												);
						 
						$array_domain_where = array(
													'domain_id' => $this->main->getUser('dom_id'),		
												);
						
						$this->Global_model->update($array_domain_where, $array_domain_update, 'kp_domain');
						
						echo json_encode(array('success'=>TRUE));
					}
					else{
						echo json_encode(array('success'=>FALSE));
					}
				
					
				}
				
				/* jika tidak ada dalam database */
				else{
					/* jika kosong tidak ada isinya maka sila di insert */
					$template_setting = array(
											'general_setting' => $post
										);
					
					/* data to insert */
					$datatoinsert = array(
											'blog_id' => $this->main->getUser('dom_id'),	
											'option_name' => 'template_setting',	
											'option_value' => base64_encode(serialize($template_setting))
										);
					
					if($this->Global_model->addNew($datatoinsert, 'kp_options')){
						echo json_encode(array('success'=>TRUE));
					}
					else{
						echo json_encode(array('success'=>FALSE));
					}					
				}
				
			}
		}		
	}

	function getsettingconfigumum($blogid){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			if($this->main->getUser('logged_in') == TRUE){
				$this->load->model('Site_model');
				$templatesetting = $this->Site_model->getTemplateSetting($this->main->getUser('dom_id'));
				
				if($templatesetting){
					$getoldtemplatesetting = @unserialize(base64_decode($templatesetting['option_value']));
				}
				else{
					$getoldtemplatesetting['general_setting'] = array();
				}
				
				echo json_encode(@$getoldtemplatesetting['general_setting']);			
			}
		}		
	}

	function savesettingconfigkonten($blogid){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			if($this->main->getUser('logged_in') == TRUE){
				$this->load->model('Site_model');
				$this->load->model('Global_model');
				$templatesetting = $this->Site_model->getTemplateSetting($this->main->getUser('dom_id'));
				$post = $this->input->post();
				
				/* jika yang diambil dari database itu ada */
				if($templatesetting){
					/* ambil option value dari template setting */
					$getoldtemplatesetting = unserialize(base64_decode($templatesetting['option_value']));
					
					/* setting yang harus di setting */
					$getoldtemplatesetting['content_setting'] = $post;
					
					/* data yang harus di update */
					$datatoupdate = array(	
											'option_value' => base64_encode(serialize($getoldtemplatesetting))										
										);
										
					/* array yang harus di update */
					$arraywhere = array(
											'blog_id' => $this->main->getUser('dom_id'),	
											'option_name' => 'template_setting',
										);
					
					if($this->Global_model->update($arraywhere, $datatoupdate, 'kp_options')){
						echo json_encode(array('success'=>TRUE));
					}
					else{
						echo json_encode(array('success'=>FALSE));
					}
				
					
				}
				
				/* jika tidak ada dalam database */
				else{
					/* jika kosong tidak ada isinya maka sila di insert */
					$template_setting = array(
											'content_setting' => $post
										);
					
					/* data to insert */
					$datatoinsert = array(
											'blog_id' => $this->main->getUser('dom_id'),	
											'option_name' => 'template_setting',	
											'option_value' => base64_encode(serialize($template_setting))
										);
					
					if($this->Global_model->addNew($datatoinsert, 'kp_options')){
						echo json_encode(array('success'=>TRUE));
					}
					else{
						echo json_encode(array('success'=>FALSE));
					}					
				}
				
			}
		}		
	}

	function getsettingconfigkonten($blogid){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			if($this->main->getUser('logged_in') == TRUE){
				$this->load->model('Site_model');
				$templatesetting = $this->Site_model->getTemplateSetting($this->main->getUser('dom_id'));
				if($templatesetting){
					$getoldtemplatesetting = @unserialize(base64_decode($templatesetting['option_value']));
				}
				else{
					$getoldtemplatesetting['content_setting'] = array();
				}
				
				echo json_encode(@$getoldtemplatesetting['content_setting']);			
			}
		}		
	}	

	/* save setting config koment */
	function savesettingconfigkomentar($blogid){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			if($this->main->getUser('logged_in') == TRUE){
				$this->load->model('Site_model');
				$this->load->model('Global_model');
				$templatesetting = $this->Site_model->getTemplateSetting($this->main->getUser('dom_id'));
				$post = $this->input->post();
				
				/* jika yang diambil dari database itu ada */
				if($templatesetting){
					/* ambil option value dari template setting */
					$getoldtemplatesetting = unserialize(base64_decode($templatesetting['option_value']));
					
					/* setting yang harus di setting */
					$getoldtemplatesetting['comment_setting'] = $post;
					
					/* data yang harus di update */
					$datatoupdate = array(	
											'option_value' => base64_encode(serialize($getoldtemplatesetting))										
										);
										
					/* array yang harus di update */
					$arraywhere = array(
											'blog_id' => $this->main->getUser('dom_id'),	
											'option_name' => 'template_setting',
										);
					
					if($this->Global_model->update($arraywhere, $datatoupdate, 'kp_options')){
						echo json_encode(array('success'=>TRUE));
					}
					else{
						echo json_encode(array('success'=>FALSE));
					}
				
					
				}
				
				/* jika tidak ada dalam database */
				else{
					/* jika kosong tidak ada isinya maka sila di insert */
					$template_setting = array(
											'comment_setting' => $post
										);
					
					/* data to insert */
					$datatoinsert = array(
											'blog_id' => $this->main->getUser('dom_id'),	
											'option_name' => 'template_setting',	
											'option_value' => base64_encode(serialize($template_setting))
										);
					
					if($this->Global_model->addNew($datatoinsert, 'kp_options')){
						echo json_encode(array('success'=>TRUE));
					}
					else{
						echo json_encode(array('success'=>FALSE));
					}					
				}
				
			}
		}		
	}		

	function getsettingconfigkomentar($blogid){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			if($this->main->getUser('logged_in') == TRUE){
				$this->load->model('Site_model');
				$templatesetting = $this->Site_model->getTemplateSetting($this->main->getUser('dom_id'));
				
				/* jika template setting ada */
				if($templatesetting){
					$getoldtemplatesetting = @unserialize(base64_decode($templatesetting['option_value']));
				}
				
				/* template setting tidak ada maka persiapkan array yang kosong */
				else{
					$getoldtemplatesetting['comment_setting'] = array();
				}
				
				/* komentar setting */
				echo json_encode(@$getoldtemplatesetting['comment_setting']);			
			}
		}		
	}

	function savesettingconfigseo($blogid){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			if($this->main->getUser('logged_in') == TRUE){
				$this->load->model('Site_model');
				$this->load->model('Global_model');
				$templatesetting = $this->Site_model->getTemplateSetting($this->main->getUser('dom_id'));
				$post = $this->input->post();
				
				/* jika yang diambil dari database itu ada */
				if($templatesetting){
					/* ambil option value dari template setting */
					$getoldtemplatesetting = unserialize(base64_decode($templatesetting['option_value']));
					
					/* setting yang harus di setting */
					$getoldtemplatesetting['seo_setting'] = $post;
					
					/* data yang harus di update */
					$datatoupdate = array(	
											'option_value' => base64_encode(serialize($getoldtemplatesetting))										
										);
										
					/* array yang harus di update */
					$arraywhere = array(
											'blog_id' => $this->main->getUser('dom_id'),	
											'option_name' => 'template_setting',
										);
					
					if($this->Global_model->update($arraywhere, $datatoupdate, 'kp_options')){
						echo json_encode(array('success'=>TRUE));
					}
					else{
						echo json_encode(array('success'=>FALSE));
					}
				
					
				}
				
				/* jika tidak ada dalam database */
				else{
					/* jika kosong tidak ada isinya maka sila di insert */
					$template_setting = array(
											'seo_setting' => $post
										);
					
					/* data to insert */
					$datatoinsert = array(
											'blog_id' => $this->main->getUser('dom_id'),	
											'option_name' => 'template_setting',	
											'option_value' => base64_encode(serialize($template_setting))
										);
					
					if($this->Global_model->addNew($datatoinsert, 'kp_options')){
						echo json_encode(array('success'=>TRUE));
					}
					else{
						echo json_encode(array('success'=>FALSE));
					}					
				}
				
			}
		}			
	}	

	function getsettingconfigseo($blogid){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			if($this->main->getUser('logged_in') == TRUE){
				$this->load->model('Site_model');
				$templatesetting = $this->Site_model->getTemplateSetting($this->main->getUser('dom_id'));
				
				/* jika template setting ada */
				if($templatesetting){
					$getoldtemplatesetting = @unserialize(base64_decode($templatesetting['option_value']));
				}
				
				/* template setting tidak ada maka persiapkan array yang kosong */
				else{
					$getoldtemplatesetting['seo_setting'] = array();
				}
				
				/* komentar setting */
				echo json_encode(@$getoldtemplatesetting['seo_setting']);			
			}
		}		
	}	

	function savesettingconfigstore($blogid){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			if($this->main->getUser('logged_in') == TRUE){
				$this->load->model('Site_model');
				$this->load->model('Global_model');
				$this->load->model('Post_model');
				$templatesetting = $this->Site_model->getTemplateSetting($this->main->getUser('dom_id'));
				$post = $this->input->post();
				
				/* jika yang diambil dari database itu ada */
				if($templatesetting){
					/* ambil option value dari template setting */
					$getoldtemplatesetting = unserialize(base64_decode($templatesetting['option_value']));										
										
					/* array yang harus di update */
					$arraywhere = array(
											'blog_id' => $this->main->getUser('dom_id'),	
											'option_name' => 'template_setting',
										);


					if($post['hal_pil_konfirmasi'] == 0){
						/* array for new article */
						$addnew_array = array(	
							'post_author' => $this->main->getUser('user_id'), 		
							'post_date' => date('Y-m-d'),	
							'post_name' => url_title($post['title_halaman'],'_', TRUE),								
							'post_title' => $post['title_halaman'],
							'post_type' => 'page',		
							'blog_id' => $this->main->getUser('dom_id')
						);	

						if($this->Post_model->articletitle_is_exist($post['title_halaman'], $this->main->getUser('dom_id')) == 0 && $post['title_halaman'] != ''){
							if(@$this->Global_model->addNew($addnew_array,'kp_posts')){
								$post['hal_pil_konfirmasi'] = url_title($post['title_halaman'],'_', TRUE);								
							}
						}

							
					}
					
					$post['title_halaman'] = '';

					/* setting yang harus di setting */
					$getoldtemplatesetting['store_setting'] = $post;					

					/* data yang harus di update */
					$datatoupdate = array(	
											'option_value' => base64_encode(serialize($getoldtemplatesetting))										
										);


					if($this->Global_model->update($arraywhere, $datatoupdate, 'kp_options')){
						$wheretrigger = array('blog_id' => $this->main->getUser('dom_id'), 'post_name' => 'page', 'post_name' => $post['hal_pil_konfirmasi'] , 'post_author' => $this->main->getUser('user_id') );
						$update_array = array('post_content' => nl2br($_POST['hal_konfirmasi'] ));							
						@$this->Global_model->update($wheretrigger,$update_array,'kp_posts');				
						echo json_encode(array('success'=>TRUE));
					}
					else{
						echo json_encode(array('success'=>FALSE));
					}
				
					
				}
				
				/* jika tidak ada dalam database */
				else{
					/* jika kosong tidak ada isinya maka sila di insert */
					$template_setting = array(
											'store_setting' => $post
										);
					
					/* data to insert */
					$datatoinsert = array(
											'blog_id' => $this->main->getUser('dom_id'),	
											'option_name' => 'template_setting',	
											'option_value' => base64_encode(serialize($template_setting))
										);
					
					if($this->Global_model->addNew($datatoinsert, 'kp_options')){
						echo json_encode(array('success'=>TRUE));
					}
					else{
						echo json_encode(array('success'=>FALSE));
					}					
				}
				
			}
		}			
	}

	function getsettingconfigstore($blogid){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {			
			$this->load->model('Site_model');
			$templatesetting = $this->Site_model->getTemplateSetting($this->main->getUser('dom_id'));
			
			/* jika template setting ada */
			if($templatesetting){
				$getoldtemplatesetting = @unserialize(base64_decode($templatesetting['option_value']));
			}
			
			/* template setting tidak ada maka persiapkan array yang kosong */
			else{
				$getoldtemplatesetting['store_setting'] = array();
			}
			
			/* komentar setting */
			echo json_encode(@$getoldtemplatesetting['store_setting']);				
		}		
	}			

	/* USER VERIFY */
	function getaccount($blogid=NULL){

		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {	
			$this->load->model('Site_model');
			$this->load->model('Global_model');	
			$this->load->helper('string');
			$account = $this->Global_model->select_single(array('blog_id' => $this->main->getUser('dom_id'), 'user_id' => $this->main->getUser('user_id')),'kp_user_detail');			
			echo json_encode($account);
		}
	}


	/* DASHBOARD */
	function getsortinfo($blogid=NULL){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {	
			$this->load->model('Global_model');	
			$pendingcomment = $this->Global_model->numrows(array('comment_blog_id' => $this->main->getUser('dom_id'), 'comment_approved' => 'tidak_terpasang'),'kp_comments');
			$pendingorder = $this->Global_model->numrows(array('blog_id' => $this->main->getUser('dom_id'), 'transaction_status' => 'pending'),'kp_transaction');
			$pendingconfirmation = $this->Global_model->numrows(array('blog_id' => $this->main->getUser('dom_id'), 'confirm_status' => 'pending'),'kp_confirmation');

			echo json_encode(array('blogid' => $this->main->getUser('dom_id') , 'comment' => $pendingcomment, 'order' => $pendingorder , 'confirmation' => $pendingconfirmation ));
		}	
	}


	/* getsetting */
	function getsetting($detail=NULL){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {	
			$this->load->model('Site_model');
			$templatesetting = $this->Site_model->getTemplateSetting($this->main->allAtr->domain_id);
			
			/* jika template setting ada */
			if($templatesetting){
				$getoldtemplatesetting = @unserialize(base64_decode($templatesetting['option_value']));
			}
			
			/* template setting tidak ada maka persiapkan array yang kosong */
			else{
				$getoldtemplatesetting['store_setting'] = array();
			}
			
			/* komentar setting */
			echo json_encode(array('detail' => @$getoldtemplatesetting['store_setting'][$detail]));		
		}

	}
}



/* End of file req.php */
/* Location: ./application/controllers/req.php */
