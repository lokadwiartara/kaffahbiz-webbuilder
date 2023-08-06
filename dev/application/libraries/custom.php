
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Custom {

	public $temp_page = NULL;
	public $list_page = array(
			'keranjang_belanja' => 'Keranjang Belanja' , 
			'pemeriksaan_pembelian' => 'Pemeriksaan Pembelian', 
			'pembelian_selesai'  => 'Pembelian Selesai' ,
			'login'  => 'LogIn Member' ,
			'dashboard' => 'Dashboard',
			'register'  => 'Register Member' ,
			'transaksi' => 'Daftar Transaksi' , 
			'setting' => 'Setting Akun',
			'lupa_password' => 'Lupa Password',
			'aktivasi' => 'Aktivasi Member',
			'konfirmasi_selesai' => 'Konfirmasi Selesai'

		);

	function custom_page($param=NULL){
		$_this =& get_instance();	
		$_this->load->library('custompage');		

		if(!empty($param)){
			$list_page = $this->list_page; 

			switch($param){

				case 'login' : 

					$display = $_this->custompage->login($param);					
					
					break;

				case 'konfirmasi_selesai' : 					

					$display = $_this->custompage->konfirmasi_selesai($param);					
					
					break;

				case 'dashboard' : 					

					$display = $_this->custompage->dashboard($param);					
					
					break;

				case 'lupa_password' : 

					$display = $_this->custompage->lupa_password($param);					
					
					break;

				case 'aktivasi' : 

					$display = $_this->custompage->aktivasi($param);					
					
					break;										

				case 'transaksi' : 

					$display = $_this->custompage->transaksi($param);					
					
					break;

				case 'setting' : 

					$display = $_this->custompage->setting($param);					
					
					break;


				case 'logout' : 

					$display = $_this->custompage->logout($param);					
					
					break;					

				case 'register' :  

					$display = $_this->custompage->register($param);
					
					break;					

				case 'keranjang_belanja' : 									

					$display = $_this->custompage->keranjang_belanja($param);
					
					break;
				
				case  'pemeriksaan_pembelian' : 
					
					$display = $display = $_this->custompage->pemeriksaan_pembelian($param);
					
					break; 

				case 'pembelian_selesai' : 							

					$display = $_this->custompage->pembelian_selesai($param);
					
					break;					


				
				default: 

					break;
			}

		}

		else{
			$display = NULL;
		}
		

		return @$display;
	}

}