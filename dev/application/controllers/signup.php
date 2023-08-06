<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Signup extends CI_Controller {
	
	/* kelas ini di buat dengan fungsi yang tidak berhubungan 
	langsung dengan site admin atau front */
	function __construct(){
		parent::__construct();
		$this->main->init_main();
	}

	function index(){		
		$this->reg();
	}

	function reg(){
		global $SConfig;		
		if(@$this->main->allAtr->domain_name == $SConfig->domain){
			$this->main->isPage = TRUE;			
			$this->template->title = 'Registrasi';
			$data = array();			
			$this->main->view_themes('reg',$data, TRUE);
		}	
	}

	function fitur(){		
		global $SConfig;		
		if(@$this->main->allAtr->domain_name == $SConfig->domain){
			$this->main->isPage = TRUE;		
			$this->template->title = 'Fitur';	
			$data = array();			
			$this->main->view_themes('fitur',$data, TRUE);
		}	
	}	

	function harga(){
		global $SConfig;		
		if(@$this->main->allAtr->domain_name == $SConfig->domain){
			$this->main->isPage = TRUE;		
			$this->template->title = 'Harga';	
			$data = array();			
			$this->main->view_themes('harga',$data, TRUE);
		}	
	}

	function demo(){
		global $SConfig;		
		if(@$this->main->allAtr->domain_name == $SConfig->domain){
			$this->main->isPage = TRUE;			
			$this->template->title = 'Demo';
			$data = array();			
			$this->main->view_themes('demo',$data, TRUE);
		}	
	}

	function check_email(){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$emailval = $this->input->post('emailval');		
			$this->load->model('site_model');			
			$this->load->helper('email');						
			if(valid_email($emailval)){
				$detil = $this->site_model->checkuser(array('kp_users.email' => $emailval));
				if(count($detil) > 0){			
					echo json_encode(array('status' => FALSE)) ;			
				}
				
				else{
					echo json_encode(array('status' => TRUE)) ;
				}			
			}	
			else{
				echo json_encode(array('status' => 'UNVALID'));
			}
		}
	}

	function check_passwd(){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$passval = $this->input->post('passval');		
			if(strlen($passval) > 7){
				if(validateRegex($passval)){
					echo json_encode(array('status' => 'strong')) ;	
				}
				else{
					echo json_encode(array('status' => 'weak')) ;		
				}
				
			}
			else{
				echo json_encode(array('status' => 'UNVALID')) ;	
			}
		}
	} 	

	function check_hp(){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$hpval = $this->input->post('hpval');
			if(strlen($hpval) > 8){
				if(validatePhone($hpval)){
					$this->load->model('site_model');
					$detil = $this->site_model->checkuserdetail(array('kp_user_detail.handphone' => $hpval));
					if(count($detil) > 0){			
						echo json_encode(array('status' => 'EXIST')) ;			
					}
					
					else{
						echo json_encode(array('status' => TRUE)) ;
					}	
				}
				else{
					echo json_encode(array('status' => FALSE)) ;		
				}		
			}
			else{
				echo json_encode(array('status' => 'INVALID'));
			}		
		}
	}

	function check_dom(){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$alamatval = $this->input->post('alamatval');	
			$tld = $this->input->post('tld');	
			$domval = $alamatval.$tld;

			if(validateAlphaNum($domval)){
				if (str_contains($domval, 'www.')) {
					echo json_encode(array('status' => 'INVALID_DOM')) ;
				}
				else{
					$tld_arr = array('.shopp.id', '.kaffah.biz', '.ol-shop.net', '.onweb.id');
					if(in_array($tld, $tld_arr)){
						$this->load->model('Global_model');
						$domainexist = $this->Global_model->numrows(array('kp_domain.domain_name' => 'www.'.$domval),'kp_domain');
						if($domainexist > 0){
							echo json_encode(array('status' => FALSE));
						}
						else{
							echo json_encode(array('status' => TRUE));	
						}
						

					}
					else{					
						$whois = new Whois();
						$query = $domval;
						$result = $whois->Lookup($query,false);				

						if($result['regrinfo']['registered'] == 'yes'){
							echo json_encode(array('status' => FALSE));
						}
						else{
							echo json_encode(array('status' => TRUE));
						}
					}
				}

			}
			else{
				if (str_contains($domval, 'www.')){
					echo json_encode(array('status' => 'INVALID_DOM')) ;
				}
				else{
					echo json_encode(array('status' => 'INVALID')) ;			
				}
				
			}	
		}					
	}

	function finishing(){
		global $SConfig;
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {		
			$post = $this->input->post();
			$this->load->helper('email');	
			$tld_arr = array('.shopp.id', '.kaffah.biz', '.ol-shop.net', '.onweb.id');
			$community_tld_arr = array('.shopp.id', '.ol-shop.net', '.onweb.id');
			$premium_tld_arr = array('.com', '.net', '.biz', '.org');				

			/*  reseller  need */
			if(!empty($post['jenis_reseller'])) {
				$jenis_reseller = $post['jenis_reseller'];
				$reseller_act = 1;
				$jnetax = 'jabodetabek';
				$jenisreknya = array('BCA', 'Syariah_Mandiri');
				$atasnamanya = array('Loka Dwiartara', 'PT. Kaffah Gemilang');
				$noreknya = array('8410.163.111', '703.511.2787');
			} 
			else{
				$jenis_reseller = '';
				$reseller_act = 0;
				$jnetax = '';
				$jenisreknya = array('BCA');
				$noreknya = array('123456789');
				$atasnamanya = array('Saya Sendiri');
			}

			!empty($post['nama_lengkap']) ? $nama_lengkap = $post['nama_lengkap'] : $nama_lengkap = '';
			!empty($post['provinsi']) ? $provinsi = $post['provinsi'] : $provinsi = '';
			!empty($post['provinsi_kota']) ? $provinsi_kota = $post['provinsi_kota'] : $provinsi_kota = '';
			!empty($post['alamat_lengkap']) ? $alamat_lengkap = $post['alamat_lengkap'] : $alamat_lengkap = '';			

			/* cek email */
			if(valid_email($post['email'])){
				$this->load->model('site_model');
				$detil = $this->site_model->checkuser(array('kp_users.email' => $post['email']));	
				if(count($detil) > 0){			
					$status_email = FALSE;
				}			
				else{
					$status_email = TRUE;
				}			
			}	
			else{
				$status_email = FALSE;
			}

			/* cek password */
			if(strlen($post['password']) > 7){
				if(validateRegex($post['password'])){
					$status_password = TRUE;
				}
				else{
					$status_password = FALSE;
				}			
			}
			else{
				$status_password = FALSE;
			}

			/* cek hp */		
			if(strlen($post['no_hp']) > 8){
				if(validatePhone($post['no_hp'])){
					$this->load->model('site_model');
					$detil = $this->site_model->checkuserdetail(array('kp_user_detail.handphone' => $post['no_hp']));
					if(count($detil) > 0){			
						$status_no_hp = FALSE;		
					}
					
					else{
						$status_no_hp = TRUE;
					}	
				}
				else{
					$status_no_hp = FALSE;
				}		
			}
			else{
				$status_no_hp = FALSE;
			}	

			/* cek domain */
			$alamatval = $this->input->post('alamat');	
			$tld = $this->input->post('tld');	
			$domval = $alamatval.$tld;

			if(validateAlphaNum($domval)){
				if (str_contains($domval, 'www')) {
					$status_domain = FALSE;
				}
				else{				
					if(in_array($tld, $tld_arr)){
						$this->load->model('Global_model');
						$domainexist = $this->Global_model->numrows(array('kp_domain.domain_name' => 'www.'.$domval),'kp_domain');
						if($domainexist > 0){
							$status_domain = FALSE;
						}
						else{
							$status_domain = TRUE;
						}
						

					}
					else{					
						$whois = new Whois();
						$query = $domval;
						$result = $whois->Lookup($query,false);				

						if($result['regrinfo']['registered'] == 'yes'){
							$status_domain = FALSE;
						}
						else{
							$status_domain = TRUE;
						}
					}
				}
			}
			else{
				$status_domain = FALSE;
			}	

			/* INSERT TO DB */
			if($status_email == TRUE && $status_password == TRUE && $status_no_hp == TRUE && $status_domain == TRUE ){
				/* INSERT TO DB */				

				/* INSERT USER */
				$this->load->model('Global_model');
				$this->load->library('passwd');
				$locatename = explode('@',$post['email']);
				$arr_user = array(
						'name' => $locatename[0],
						'password' => $this->passwd->to_password($post['password']),
						'email' => strtolower($post['email']),
						'group' => 'admin',
						'active' => 1 ,
						'reseller' => $reseller_act,
						'reseller_type' => $jenis_reseller
					);

				/* ADD USER */
				$this->Global_model->add($arr_user,'kp_users');
				$userrecent = $this->Global_model->select_single(array('email' => strtolower($post['email'])), 'kp_users');
				$userid = $userrecent['id'];

				/* INSERT DOMAIN */
				$date = date('Y-m-d');

				if(in_array($tld, $community_tld_arr)){
					$packet_id = 3 ;
					$domain_status = 0;
					$expire_year = date('Y')+1;
					$date_expired = date('Y-m-d', strtotime("+180 days"));			
				}
				else if(in_array($tld, $premium_tld_arr)){
					$packet_id = 2 ;
					$domain_status = 0;
					$expire_year = date('Y')+1;
					$date_expired = $expire_year.date('-m-d');
				}
				else{
					$packet_id = 1 ;
					$domain_status = 1;
					$expire_year = date('Y')+1;
					$date_expired = $expire_year.date('-m-d');				
				}

				/* get template detail */
				$template = $this->Global_model->select_single(array('template_id' => 3), 'kp_template');			

				$arr_dom = array(
						'domain_name' => strtolower('www.'.$domval),
						'domain_title' => $domval,
						'domain_activated' => $date,
						'domain_expired' => $date_expired,
						'domain_desc' => 'deskripsi website Anda di sini...',
						'domain_status' => $domain_status,
						'domain_template' => $template['template_name'],
						'domain_verify' => 0,
						'domain_reseller' => $jenis_reseller,
						'user_id' => $userid,
						'packet_id' => $packet_id

					);

				$this->Global_model->add($arr_dom,'kp_domain');
				
				$blogrecent = $this->Global_model->select_single(array('domain_name' => strtolower('www.'.$domval)), 'kp_domain');
				
				write_template($blogrecent['domain_id'], base64_encode($post['template_html']));
				
				/* INSERT USER DETAIl */

				$arr_user_detail = array(
						'user_id' => $userid,
						'blog_id' => $blogrecent['domain_id'],
						'nama_depan' => $locatename[0],
						'nama_belakang' => '',
						'handphone' => $post['no_hp'],
						'alamat' => $alamat_lengkap,
						'kota' => $provinsi_kota,
						'provinsi' => $provinsi

					);

				$this->Global_model->add($arr_user_detail,'kp_user_detail');
				
				/* INSERT POST */
				$date = date('Y-m-d H:i:s');
				/* array for new article */
				$arr_article = array(	
					'post_author' => $userid, 		
					'post_date' => $date,	
					'post_date_modified' => $date,
					'post_date_gmt' => '',	 				
					'post_content' => 'Anda dapat melakukan konfirmasi pembayaran dengan cara mengisi form pembayaran dibawah ini : <br /><br />%%form-confirmation%%',	
					'post_title' => 'Konfirmasi', 	
					'post_status' => 'publish',									
					'ping_status' => '',					
					'post_password' => '',
					'post_name' => 'konfirmasi',	
					'post_modified' => '',					
					'post_modified_gmt' => '',	
					'post_content_filtered' => '', 				
					'guid' => '',							
					'menu_order' => '',	
					'post_type' => 'page',																
					'post_market' => '',					
					'post_moderation' => 0,
					'post_position' => '',					
					'post_counter' => '',	
					'post_mime_type' => '',					
					'comment_count' => '',	
					'blog_id' => $blogrecent['domain_id']
				);				
				
				$this->Global_model->add($arr_article,'kp_posts');

				/* INSERT OPTION */

				$array_opt = array
						(
						    'general_setting' => array
						        (
						            'judul' => $domval,
						            'tagline' => '',
						            'alamatweb' => strtolower('www.'.$domval),
						            'email' => $post['email'],
						        ),

						    'content_setting' => array
						        (
						            'artikel_index' => '5',
						            'artikel_perkategori' => '3',
						            'produk_index' => '5',
						            'produk_perkategori' => '5',
						            'post_persearch' => '5',
						        ),

						    'comment_setting' => array
						        (
						            'all_comment' => 'yes',
						            'moderation' => 'yes',
						            'sent_email' => 'yes',
						        ),

						    'store_setting' => array
						        (
						            'provinsi' => $provinsi,
						            'provinsi_kota' => $provinsi_kota,
						            'telepon' => '',
						            'handphone' => $post['no_hp'],
						            'alamat_lengkap' => $alamat_lengkap,
						            'jenis_rekening' => $jenisreknya,
						            'no_rek' => $noreknya,
						            'atasnama' => $atasnamanya,
						            'jne_modul' => 'yes',
						            'template_email' => '<h3 class="invoice" >INVOICE #%%order_no%%</h3>
						<p ><strong>*Silahkan Save halaman ini.</strong></p>
						<p ><strong>%%nama%%</strong>, Anda telah memesan :</p>
						<p >%%order_list%%</p>
						<p >&nbsp;</p>
						<h3 >Paket akan dikirim ke:</h3>
						<p ><strong>%%nama%%</strong><br />
						Alamat : %%alamat%%<br />
						Kota : %%kota%%<br />
						Provinsi: %%provinsi%%<br />
						Email : %%email%%<br />
						No. HP : %%hp%%<br />
						Telephone : %%telephone%%</p>
						<p >*Mohon di isi selengkap mungkin, kami tidak bertanggung jawab apabila paket tidak sampai yang karena di sebabkan oleh kesalahan input alamat yang anda berikan, jika anda kurang yakin dengan data di atas, anda bisa mengisi form lagi di&nbsp;<strong>%%website%%</strong>&nbsp;(data yg lama akan di Overwrite).</p>
						<p >&nbsp;</p>
						<h3 >Cara Pembayaran :</h3>
						<p >Silahkan melakukan pembayaran<br />
						Transfer sebesar:<br />
						Harga Barang : Rp %%hargabarang%%,-&nbsp;<br />
						Ongkos Kirim :Rp %%ongkoskirim%%,-&nbsp;<br />
						Angka unik : Rp %%random_rp%%,-&nbsp;<br />
						----------------------------------------- +<br />
						Total : Rp %%totaltransfer%% (Sudah termasuk ongkos kirim)</p>
						<p >*angka unik adalah angka yang digunakan untuk memberikan TANDA/STEMPEL bahwa Rp %%totaltransfer%% adalah Uang transferan dari anda, supaya tidak tertukar dengan transferan dari customer lainnya.</p>
						<p >&nbsp;</p>
						<h3 >Ke Rekening (pilih salah satu):</h3>
						<p >%%payment_list%%</p>
						<p ></p>
						<h3 >Konfirmasi:</h3>
						<p ><strong>Setelah transfer silahkan lakukan Konfirmasi via SMS/Telephone ke: %%handphone_admin%% atau<strong>%%telephone_admin%%</strong></strong></p>
						<p >dengan format:<br />
						<strong>nama#no order/no invoice#transfer ke bank#jumlah transfer</strong><br />
						<br />
						contoh:<br />
						<strong>%%nama%%#%%order_no%%#BCA#Rp %%totaltransfer%%</strong><br />
						<br />
						Setelah transfer, anda WAJIB konfirmasi via SMS dengan format seperti di atas</p>
						<p >atau Anda juga dapat melakukan konfirmasi melalui halaman konfirmasi ini<strong>%%confirm_page%%</strong></p>
						<p ><br />
						<br />
						Kami memberikan pelayanan dari hari SENIN sampai SABTU, transfer hari Minggu akan di proses hari SENIN.Setelah pembayaran kami terima, paket akan segera kami proses dan kirim dengan TIKI JNE<br />
						<br />
						Lama pengiriman rata-rata 2-4 hari.</p>
						<h4 style="font-size: 10px; color: rgb(0, 0, 0); font-family: Verdana, Arial, Helvetica, sans-serif; line-height: normal;"><br />
						<strong>PAKET SIAP DI KIRIM KE TEMPAT ANDA</strong></h4>',
									'kota_ongkir' => $jnetax,
						            'hal_pil_konfirmasi' => 'konfirmasi',
						            'hal_konfirmasi' => 'Anda dapat melakukan konfirmasi pembayaran dengan cara mengisi form pembayaran dibawah ini :

						%%form-confirmation%%',
						        ),

						    'display_setting' => array(),

						    'seo_setting' => array
						        (
						            'url_canonical' => 'yes',
						            'home_title' => '',
						            'home_keyword' => '',
						            'home_description' => '',
						            'keyword_description_kategori' => 'yes',
						            'keyword_description_single' => 'yes',
						            'webmaster_meta' => '',
						            'google_analytic' => '',
						            'alexa_meta' => ''
						        )

						);				
			
				$opt = base64_encode(serialize($array_opt)) ;


				$arr_template_setting = array(
						'blog_id' => $blogrecent['domain_id'],
						'option_name' => 'template_setting',
						'option_value' => $opt
					);

				if($this->Global_model->add($arr_template_setting,'kp_options')){
						
					$packetrecent = $this->Global_model->select_single(array('packet_id' => $packet_id), 'kp_packet');

					/* SEBELAH SINI DIGUNAKAN - APABILA NAMA LENGKAP ALAMAT DAN JENIS RESELLER ITU TIDAK KOSONG */

					if(!empty($post['nama_lengkap'])){

						$arraymultisitelogin = array(
								'user_id' => $userid,
								'type' => 'user',
								'blog_id' => $blogrecent['domain_id']
							);

						$this->Global_model->add($arraymultisitelogin,'kp_multi_sitelogin');						

						$random_car = mt_rand(100, 500);
						$random_car = (($random_car % 2) == 0) ? $random_car+=1 : $random_car = $random_car;
						$hargapacket = $packetrecent['packet_price'];
						$total = $hargapacket + $random_car; 
						
						$random_rp = number_format($random_car, 0, ',', '.');
						$hargapacket_rp = number_format($hargapacket, 0, ',', '.');
						$total_rp = number_format($total, 0, ',', '.');
						
						$website = strtolower('www.'.$domval);
						$email = $post['email'];
						$password = $post['password'];
						$quota = $packetrecent['packet_hd_space'];
						$namalengkap = $locatename[0];
						$packet = $packetrecent['packet_name'];
						$product = $packetrecent['packet_product'];
						$visitor = $packetrecent['packet_visitor'];
						$templ = $packetrecent['packet_template'];
						$hargapacket = $hargapacket_rp; 
						$angkaunik = $random_rp;
						$jumlahtransfer = $total_rp;
						$jenisreseller = $post['jenis_reseller'];			
						
						$namalengkap = str_replace(' ', '_', $namalengkap);

						if(!empty($post['jenis_reseller']) && $post['jenis_reseller'] == 'silver') {
							$hargapacket = 1150000;
							$total = $hargapacket + $random_car; 								
							$random_rp = number_format($random_car, 0, ',', '.');
							$hargapacket_rp = number_format($hargapacket, 0, ',', '.');
							$total_rp = number_format($total, 0, ',', '.');
							$hargapacket = $hargapacket_rp; 
							$jumlahtransfer = $total_rp;


							shell_exec("perl /var/www/kaffahbiz/temp/silver.pl $website $email $password $quota $namalengkap $packet $product $visitor $templ $hargapacket $angkaunik $jumlahtransfer $jenisreseller");								

						}

						else if(!empty($post['jenis_reseller']) && $post['jenis_reseller'] == 'basic') {
							$hargapacket = 345000;
							$total = $hargapacket + $random_car; 								
							$random_rp = number_format($random_car, 0, ',', '.');
							$hargapacket_rp = number_format($hargapacket, 0, ',', '.');
							$total_rp = number_format($total, 0, ',', '.');
							$hargapacket = $hargapacket_rp; 
							$jumlahtransfer = $total_rp;							

							// echo "perl /var/www/kaffahbiz/temp/basic.pl $website $email $password $quota $namalengkap $packet $product $visitor $templ $hargapacket $angkaunik $jumlahtransfer $jenisreseller";


							shell_exec("perl /var/www/kaffahbiz/temp/basic.pl $website $email $password $quota $namalengkap $packet $product $visitor $templ $hargapacket $angkaunik $jumlahtransfer $jenisreseller");								
						}

						else if(!empty($post['jenis_reseller']) && $post['jenis_reseller'] == 'system') {
							$hargapacket = 99000;
							$total = $hargapacket + $random_car; 								
							$random_rp = number_format($random_car, 0, ',', '.');
							$hargapacket_rp = number_format($hargapacket, 0, ',', '.');
							$total_rp = number_format($total, 0, ',', '.');
							$hargapacket = $hargapacket_rp; 
							$jumlahtransfer = $total_rp;

							shell_exec("perl /var/www/kaffahbiz/temp/system.pl $website $email $password $quota $namalengkap $packet $product $visitor $templ $hargapacket $angkaunik $jumlahtransfer $jenisreseller");								

						}

							

					}
					else{						

						/* SENDING EMAIL */		
						if($packet_id > 1){
							$random_car = mt_rand(100, 500);
							$random_car = (($random_car % 2) == 0) ? $random_car+=1 : $random_car = $random_car;
							$hargapacket = $packetrecent['packet_price'];
							$total = $hargapacket + $random_car; 
							
							$random_rp = number_format($random_car, 0, ',', '.');
							$hargapacket_rp = number_format($hargapacket, 0, ',', '.');
							$total_rp = number_format($total, 0, ',', '.');
							
							$website = strtolower('www.'.$domval);
							$email = $post['email'];
							$password = $post['password'];
							$quota = $packetrecent['packet_hd_space'];
							$namalengkap = $locatename[0];
							$packet = $packetrecent['packet_name'];
							$product = $packetrecent['packet_product'];
							$visitor = $packetrecent['packet_visitor'];
							$templ = $packetrecent['packet_template'];
							$hargapacket = $hargapacket_rp; 
							$angkaunik = $random_rp;
							$jumlahtransfer = $total_rp;
							$jenisreseller = $post['jenis_reseller'];			

							
							shell_exec("perl /var/www/kaffahbiz/temp/paid.smtp.pl $website $email $password $quota $namalengkap $packet $product $visitor $templ $hargapacket $angkaunik $jumlahtransfer");								
							
							
							//$message = "perl /var/www/kaffahbiz/temp/paid.smtp.pl $website $email $password $quota $namalengkap $packet $product $visitor $templ $hargapacket $angkaunik $jumlahtransfer";
						}	
						
						else{				
							$email = $post['email'];
							$password = $post['password'];
							$quota = $packetrecent['packet_hd_space'];
							$website = strtolower('www.'.$domval);
							shell_exec("perl /var/www/kaffahbiz/temp/email.smtp.pl $website $email $password $quota");	
							//$message =  "perl /var/www/kaffahbiz/temp/email.smtp.pl $website $email $password $quota";							
						}
					}
						
					echo json_encode(array('status' => TRUE));

				}
				else{
					echo json_encode(array('status' => FALSE));
				}
				
			}
			else{
				echo json_encode(array('status' => FALSE));
			}	

		}
	}

}
