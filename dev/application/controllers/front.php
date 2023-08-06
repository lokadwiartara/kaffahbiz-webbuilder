<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Front extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->main->init_main();		
	}

	public function temp(){
		global $SConfig;
		$this->load->helper('captcha');
		$vals = array(
			'word' => getRandomWord(4),
			'img_path'	=> './captcha/',
			'img_url'	=> base_url().'captcha/',
			'img_width'	=> 150,
		    'img_height' => 40,
		    'expiration' => 7200
		);

		$cap = create_captcha($vals);
		print_r($cap);
	}

	public function directory(){}

	public function forreg(){
	
		$array = array
					(
					    'general_setting' => array
					        (
					            'judul' => '',
					            'tagline' => '',
					            'alamatweb' => 'www.lokadwiartara.web-id.co',
					            'email' => 'lokadwiartara@ilmuwebsite.com',
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
					            'provinsi' => '',
					            'provinsi_kota' => '',
					            'telepon' => '',
					            'handphone' => '',
					            'alamat_lengkap' => '',
					            'jenis_rekening' => '',
					            'no_rek' => '',
					            'atasnama' => '',
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
					            'hal_pil_konfirmasi' => 'konfirmasi',
					            'hal_konfirmasi' => 'Anda dapat melakukan konfirmasi pembayaran dengan cara mengisi form pembayaran dibawah ini :

					%%form-confirmation%%',
					        ),

					    'display_setting' => array(),

					    'seo_setting' => array
					        (
					            'url_canonical' => 'yes',
					            'home_title' => '-',
					            'home_keyword' => '-',
					            'home_description' => '-',
					            'keyword_description_kategori' => 'yes',
					            'keyword_description_single' => 'yes',
					            'webmaster_meta' => '-',
					            'google_analytic' => '',
					            'alexa_meta' => '-'
					        )

					);
		
		echo base64_encode(serialize($array)) ;

		// print_r($array);
	}

	public function json(){
		$query = $this->db->get('kp_modul_jne');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $row){
				$data[] = $row;
			}
			
			$query->free_result();
			
		}
		else{
			$data = NULL;
		}
		
		echo json_encode($data);	
	}

	public function index(){
		/* 	ketika yang di akses itu adalah domain utama 
			maka tampilkan view index
		*/
		
		global $SConfig;
		
		if(@$this->main->allAtr->domain_name == $SConfig->domain){
			//$this->output->cache(10);
			$this->main->isHome = TRUE;
			$this->main->forTitle = $SConfig->sitetitle;
			$data = array();
			$this->main->visitorLog();
			$this->main->view_themes('index',$data, TRUE);
		}
		
		else if(@$this->main->allAtr->domain_name == $SConfig->panel){
			/* logging into log */
			//$this->output->cache(10);
			$this->main->visitorLog();
			
			/* redirect into login menu */
			redirect('/req/login');
		}
		
		/* custom dpomain member */
		else if(@$this->main->allAtr->domain_name ){
			/* 	yang ini menandakan bahwa yang sedang di akses saat ini adalah
				halaman home depan hal ini di definisikan agar tidak lagi mengambil
				variable melalui uri->segment, hasilnya nanti lebih cepat & mudah akurat	
			*/
			$this->main->isHome = TRUE;
			
			/* searching area */
			$this->load->library('search');
			$this->search->init_search();

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
		if(@$this->main->allAtr->domain_name ){
			/* ambil variable yang dihasilkan oleh server */			
			$post = $this->input->post(NULL, TRUE);

			/* searching area */
			$this->load->library('comment');
			$this->comment->init_comment();
		}
		
		else{
			
		}	
	}

	public function search(){
		global $SConfig;
		
		if(@$this->main->allAtr->domain_name ){
			$this->main->isSearch = TRUE;			
			/* tentukan variable yang akan digunakan nantinya di halaman tersebut */
			$data = array();
			$this->main->view_themes('index',$data, TRUE);
		}
		
		else{
			$data = array();
			$this->main->view_themes('index',$data, TRUE);
		}
	}

	public function reg(){
		global $SConfig;
		
		if(@$this->main->allAtr->domain_name == $SConfig->domain){
			$data = array();
			$this->main->visitorLog();
			$this->main->view_themes('reg',$data, TRUE);
		}		
	}

	public function feed(){		
		$this->load->helper('xml');
		$domainatr = $this->main->allAtr ;
		$this->load->library('template');
		$this->load->model('Site_model');		
		$this->load->helper('inflector');
		$this->load->model('Post_model');
		$template_setting = $this->Site_model->getTemplateSetting($domainatr->domain_id);		
		$set = unserialize(base64_decode(@$template_setting['option_value']));	
		$data = array('template_setting' => $set['general_setting'], 'domainatr' => $domainatr);
		header("Content-Type: application/rss+xml");
		$this->load->view('feed', $data);
	}

	public function sitemap(){
		$domainatr = $this->main->allAtr ;
		$this->load->model('Global_model');
		$data = array('sitemap' => $this->Global_model->select(array('blog_id' => $domainatr->domain_id, 'post_type !=' => 'attachment'  ), 'kp_posts', 'ID', 'DESC' ));
        header("Content-Type: text/xml;charset=iso-8859-1");
        $this->load->view("sitemap",$data);		

	}

}

/* End of file front.php */
/* Location: ./application/controllers/front.php */
