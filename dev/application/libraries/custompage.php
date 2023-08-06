<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Custompage {

	public $custom_title = NULL;

	function beli($post=NULL){
		$_this = get_instance();
		$_this->load->library('cart');
		$domainatr = $_this->main->allAtr ;

		if(!empty($post)){

			$_this->session->unset_userdata(
					array(
						'totalongkir' => '', 
						'jne' => '', 
						'kec' => '', 
						'price' => ''

					)

				);

			$_this->load->model('Global_model');
			$detailproduct = $_this->Global_model->select(array('ID' => $post['product_id'], 'blog_id' => $domainatr->domain_id), 'kp_posts','ID','Desc');				
			$domainreseller = $domainatr->domain_reseller;



			/* jika yang di akses itu adalah website reseller */
			if(!empty($domainreseller)){		
				/* syarat masuk keranjang belanja adalah harus produk kaffahstore */
				// && ($detailproduct[0]['post_reseller'] == 'yes') 
				if( ($detailproduct[0]['post_parent'] > 0) && ($detailproduct[0]['post_stock'] > 0) || ($detailproduct[0]['post_stock'] == '-') ){
					$data = array(
					               'id'      => $post['product_id'],
					               'qty'     => $post['qty'],
					               'price'   => $post['product_price'],
					               'name'    => $post['product_name'],
					               'options' => array('Size' => $post['product_size'], 'Color' => $post['product_color'])
					            );

					// $_this->cart->destroy();
					$_this->cart->insert($data);
					redirect(base_url().'produk/keranjang_belanja');
				}			
				else{
					//echo $detailproduct['post_parent'];
					redirect($_SERVER['HTTP_REFERER']);
				}
			}

			/* jika yang diakses itu bukan website reseller */
			else{
				if(($detailproduct[0]['post_stock'] > 0) || ($detailproduct[0]['post_stock'] == '-')){
					$data = array(
					               'id'      => $post['product_id'],
					               'qty'     => $post['qty'],
					               'price'   => $post['product_price'],
					               'name'    => $post['product_name'],
					               'options' => array('Size' => $post['product_size'], 'Color' => $post['product_color'])
					            );

					// $_this->cart->destroy();
					$_this->cart->insert($data);
					redirect(base_url().'produk/keranjang_belanja');
				}			
				else{
					redirect($_SERVER['HTTP_REFERER']);
				}
			}


		}	
	}

	function keranjang_belanja($param){
		$_this =& get_instance();			
		$_this->load->library('cart');
		$_this->load->helper('inflector');
		$cart = $_this->cart->contents();		
		$all_ts_array = $_this->template->templatesetting;
		$template_setting = unserialize(base64_decode($all_ts_array['option_value']));
		$sess = $_this->session->userdata;		
		$domainatr = $_this->main->allAtr;



		/* table dari keranjang belanja */
		$display = '<p>Berikut adalah daftar belanjaan Anda : </p>';
		$display .= '<form id="listcart" action="'.base_url().'produk/pemeriksaan_pembelian" method="POST">';
		$display .= '<table id="shopping_cart" cellpadding="6" cellspacing="1" style="width:100%" border="0">';
		$display .= '<tr>	
					  <th width="70%">Item Detail</th>
					  <th width="10%">Qty</th>					  					  
					  <th width="20%" style="text-align:right">Jumlah</th>
					</tr>';	


		/* mengambil semua id product dari cart */
		foreach ($cart as $items){			
			$productID[] = $items['id'];
		}		


		if(!empty($productID)){
			/* ambil detil produk */
			$_this->load->model('Global_model');
			$detailproduct = $_this->Global_model->selectwherein($productID, 'kp_posts');	

			// echo $detailproduct['333']['post_name'];					
			/*  pengulangan isi keranjang belanja */
			foreach ($cart as $items){	

				if(!empty($sess['price']) && (!empty($sess['totalongkir']))){

				}

				$atr = unserialize(base64_decode($detailproduct[$items['id']]['post_attribute']));					
				$display .= '<tr>
								<td><img src="'.get_thumb($detailproduct[$items['id']]['post_image'],64,64,1).'" class="image_product" />
								<h5 class="name_product">'. humanize($items['name']) .'</h5>
								<p class="desc_product">'.character_limiter(strip_tags($detailproduct[$items['id']]['post_content']), 45).' (@ Rp '.@number_format($items['price'],0,",",".").' / '.$atr['berat'].' '.$atr['satuanberat'].')</p></td>
								<td style="text-align:center"><input type="text" id="'. $items['rowid'] .'" class="qtyinput" value="'. $items['qty'] .'" autocomplete="off" /><a href=# class="delcart" name="'.$items['rowid'].'"></a></td>							
								<td style="text-align:right" class="total_product">Rp '.@number_format($items['subtotal'],0,",",".") .'</td>
							</tr>';		

			}

			if(@$template_setting['store_setting']['jne_modul'] == 'yes'){
				$tax = '<div class="jnelisttax"><ul id="uljne"></ul></div>';
				$taxdetail = '<div class="jnetaxdetail">
								<p class="captiontaxtable">Silahkan Pilih Metode Pengiriman ke <strong id="kecamatanjne">Bogor ( Kota Bogor )</strong> dengan <strong>TIKI JNE</strong> : </p>
								<table class="tax_the_city" align="center">
								<thead>
								<tr><th>Jenis JNE</th><th>Berat</th><th>Tarif</th><th class="action">Aksi</th></tr>
								</thead>	
								<tbody>
								<tr><td>Reguler (REG)</td><td>1000 gram</td><td>Rp 8.000</td><td><a href="" class="choice_tax" name="JNE_REG" title="8000">Pilih JNE REG</a></td></tr>
								<tr class="even"><td>Ongkos Kirim Ekonomis (OKE)</td><td>1000 gram</td><td>Rp 7.000</td><td><a href="" name="JNE_OKE" class="choice_tax" title="7000">Pilih JNE OKE</a></td></tr>
								<tr><td>Yakin Esok Sampai (YES)</td><td>1000 gram</td><td>Rp 15.000</td><td><a href="" name="JNE_YES" title="15000" class="choice_tax">Pilih JNE YES</a></td></tr>								
								</tbody>
								</table>
							</div>';

				$display .= '<tr><td colspan="2" style="text-align:right" class="jne_taxx">Biaya Pengiriman (JNE). Masukan kecamatan tempat Anda : <input type="text" class="jnetax" id="jnetax" value="'.@$sess['kec'].'" autocomplete="off" />'.$tax.'</td><td style="text-align:right" class="jne_cost">Rp '.@number_format($sess['totalongkir'],0,",",".").'</td></tr>';
				$display .= '<tr class="trtaxdetail"><td colspan="3">'.$taxdetail.'</td></tr>';
			}
			
			$display .= '<tr><td colspan="2" style="text-align:right"><h4>Total yang harus dibayar</h4></td><td style="text-align:right"> <h4 class="sum_product">Rp '.@number_format($_this->cart->total() + $sess['totalongkir'],0,",",".").'</h4></td></tr>';
		}

		else{
			$display .= '<tr><td colspan="3">Anda belum memilih produk</td></tr>';
		}

		$display .= '</table>';
		$display .= '<input type="submit" value="Belanja Selesai!" class="bigbtn bigright"/> ';
		$display .= '<input type="submit" value="Pesan yang Lain..." class="bigbtn" /><br class="floating" />';
		
		$display .= '</form>';


		/* array for all */
		$title = $_this->custom->list_page;		

		$arraytodisplay = array(
							'post_content' => $display,
							'post_title' => $title[$param] ,										
							'post_date' => '',
							'post_type' => '',
							'ID' => '',
							'post_category' => '',
							'post_name' => '', 
							'post_price' => '', 
							'post_stock' => '', 
							'post_stock' => '', 
							'comment_count' => '', 
						);		

		return $arraytodisplay;
	}

	function pemeriksaan_pembelian($param){
		global $SConfig;
		$_this =& get_instance();	
		$display = NULL;
		$totalongkir = $_this->session->userdata('totalongkir');
		$sess = $_this->session->userdata;
		$all_ts_array = $_this->template->templatesetting;
		$template_setting = unserialize(base64_decode($all_ts_array['option_value']));		
		$domainatr = $_this->main->allAtr ;
	
		if((empty($totalongkir)) && (@$template_setting['store_setting']['jne_modul'] == 'yes')){
			redirect($_SERVER['HTTP_REFERER']);			
		}	

		else{
			$_this->load->library('form_validation');
			$_this->load->model('Global_model');
			$_this->load->library('cart');
			$_this->form_validation->set_message('required', 'Anda belum mengisi %s');
			$_this->form_validation->set_message('valid_email', 'Maaf, Email tidak valid');
			$_this->form_validation->set_error_delimiters('<p class="error">', '</p>');
			
			if ($_this->form_validation->run('transaction') == TRUE){
				$post = $_this->input->post(NULL, TRUE);
				$randnumber = rand(1, 200);
				$date = date('Y-m-d H:i:s');

				$arraytransaction = array(
						'transaction_status' => 'pending', 
						'transaction_session' => $sess['session_id'], 
						'transaction_time' => $date,
						'total' => $_this->cart->total(), 
						'random' => $randnumber, 
						'tax' => $sess['price'], 
						'total_tax' => $sess['totalongkir'], 
						'all_total' => $_this->cart->total() + $sess['totalongkir'] + $randnumber, 
						'tax_type' => $sess['jne'], 
						'tax_city' => $sess['kec'], 
						'transfer_destination' => '', 
						'tracking_number' => '', 
						'blog_id' => $domainatr->domain_id
					);

				/* persiapan insert to db  */				
				$_this->Global_model->add($arraytransaction,'kp_transaction');

				/* get db last transaction with sessiond id */
				$recenttransact = $_this->Global_model->select_single(
											array('transaction_time' => $date, 
												'transaction_session' => $sess['session_id'],
												'blog_id' => $domainatr->domain_id	
												),'kp_transaction');

				
				/* ******************************************************************* */
				/* GUNAKAN TRANSAKSI YANG TADI UNTUK DI MASUKKAN KE KAFFAHSTORE ADMIN */
				/* ******************************************************************* */
				$domainreseller = $domainatr->domain_reseller;
				if(!empty($domainreseller)){
					$webownerdetail = $_this->Global_model->select_single(array('user_id' => $domainatr->user_id),'kp_user_detail');

					$arraytransaction['transaction_parent'] = $recenttransact['transaction_id'];
					$arraytransaction['blog_id'] 			= $SConfig->storeID;
					$arraytransaction['domain_name_parent'] = $domainatr->domain_name;
					$arraytransaction['blog_id_parent'] 	= $domainatr->domain_id;
					$arraytransaction['name_parent'] = $webownerdetail['nama_depan'].' '.$webownerdetail['nama_belakang'];
					$arraytransaction['user_id_parent'] = $domainatr->user_id;
					$arraytransaction['handphone_parent'] = $webownerdetail['handphone'];

					$_this->Global_model->add($arraytransaction,'kp_transaction');				
				}
					

				$arrayshipping = array(
						'user_id' => !empty($sess['user_id']) ? $sess['user_id'] : '0',
						'transaction_id' => $recenttransact['transaction_id'],
						'nama_lengkap' => $post['nama_lengkap'],
						'alamat' => $post['alamat'],
						'provinsi' => $post['provinsi'],
						'kota' => $post['provinsi_kota'],
						'no_telepon' => $post['no_telepon'],
						'no_handphone' => $post['no_handphone'],
						'email' => $post['email'],
						'blog_id' => $domainatr->domain_id					
					);

				$_this->Global_model->add($arrayshipping,'kp_shipping');

				/* ******************************************************************* */
				/* SEBELAH SINI DIGUNAKAN UNTUK UPDATE SHIPPING UNTUK KAFFAHSTORE ADMIN */
				/* ******************************************************************* */
				if(!empty($domainreseller)){
					$arrayshipping = array(
							'user_id' => !empty($sess['user_id']) ? $sess['user_id'] : '0',
							'transaction_id' => '',
							'transaction_parent' => $recenttransact['transaction_id'],
							'nama_lengkap' => $post['nama_lengkap'],
							'alamat' => $post['alamat'],
							'provinsi' => $post['provinsi'],
							'kota' => $post['provinsi_kota'],
							'no_telepon' => $post['no_telepon'],
							'no_handphone' => $post['no_handphone'],
							'email' => $post['email'],
							'blog_id' => $SConfig->storeID					
						);

					$_this->Global_model->add($arrayshipping,'kp_shipping');
				}

				
				/* insert batch transaction detil */
				$cart_content = $_this->cart->contents();
				$arraydetailtransact = array();

				/* transaction_id, product_id, name, option, quantity, price, sub_total */
				$x = 0;
				foreach($cart_content as $row){

					$arraydetailtransact[$x] = array(
						'transaction_id' => $recenttransact['transaction_id'], 
						'product_id' =>  $row['id'],
						'name' => $row['name'],
						'option' => base64_encode(serialize($row['options'])),
						'quantity' => $row['qty'],
						'price' => $row['price'],
						'sub_total' => $row['subtotal'],
						'blog_id' => $domainatr->domain_id
						);
					$x++;
				}

				// print_r($arraydetailtransact);

				$_this->Global_model->addBatch($arraydetailtransact,'kp_trans_detail');	


				/* ******************************************************************* */
				/* SEBELAH SINI DIGUNAKAN UNTUK UPDATE TRANSAKSI KE KAFFAHSTORE ADMIN */
				/* ******************************************************************* */
				if(!empty($domainreseller)){
					$arraydetailtransactr = array();
					$x = 0;
					foreach($cart_content as $row){
						$arraydetailtransactr[$x] = array(
							'transaction_id' => '', 
							'transaction_parent' => $recenttransact['transaction_id'], 
							'product_id' =>  $row['id'],
							'name' => $row['name'],
							'option' => base64_encode(serialize($row['options'])),
							'quantity' => $row['qty'],
							'price' => $row['price'],
							'sub_total' => $row['subtotal'],
							'blog_id' => $SConfig->storeID
							);
						$x++;
					}


					$_this->Global_model->addBatch($arraydetailtransactr,'kp_trans_detail');	
			
				}

				$_POST['random'] = $randnumber;
				$_POST['id'] = $recenttransact['transaction_id'];
				$_this->session->set_userdata($_POST);				
				redirect(base_url().'produk/pembelian_selesai');				
			}

			else{
			
				$_this->load->library('cart');
				$_this->load->helper('inflector');
				$cart = $_this->cart->contents();	
				$_this->load->helper('provkotkab_helper');

				/* kondisi ketika ketika sudah login */

				if($_this->session->userdata('logged_in') == TRUE){
					$display =	'<div class="check_transaction"  id="buy_without_membership">';
					$display .= '<h3>Form Pembelian</h3>';
					$display .= '<p>Silahkan isi form dibawah ini untuk menyelesaikan pemesanan produk</p>';
					$display .= '<form action="'.base_url().'produk/pemeriksaan_pembelian" method="post" accept-charset="utf-8">
								<label class="label">Nama Lengkap :</label><input value="'.set_value('nama_lengkap').'" type="text" name="nama_lengkap" class="reg_input">'.form_error('nama_lengkap').'
								<label class="label">Email :</label><input type="text" value="'.set_value('email').'" name="email" class="reg_input">'.form_error('email').'
								<label class="label">Alamat:</label><textarea class="reg_input" name="alamat">'.set_value('alamat').'</textarea>'.form_error('alamat').'
								<label class="label">Provinsi :</label>'.provinsi_dropdown().'<br />'.form_error('provinsi').'
								<label class="label">Kota :</label>'.provinsi_kota_dropdown().'<br />'.form_error('provinsi_kota').'
								<label class="label">Telephone :</label><input type="text" name="no_telepon" value="'.set_value('no_telepon').'" class="reg_input">'.form_error('no_telepon').'
								<label class="label">Handphone :</label><input name="no_handphone" type="text" value="'.set_value('no_handphone').'" class="reg_input">'.form_error('no_handphone').'
								<input type="submit" class="bigbtn" name="reg_submit" value="Lanjutkan >">
								</form>';

					$display .= '</div>';				
				}

				else{
					$display =	'<div class="check_transaction"  id="buy_without_membership">';
					$display .= '<h3>Pembelian Tanpa Menjadi Member</h3>';
					$display .= '<p>Anda bisa melakukan pembelian dengan menjadi member ataupun tanpa menjadi member.</p>';
					$display .= '<form action="'.base_url().'produk/pemeriksaan_pembelian" method="post" accept-charset="utf-8">
								<label class="label">Nama Lengkap :</label><input value="'.set_value('nama_lengkap').'" type="text" name="nama_lengkap" class="reg_input">'.form_error('nama_lengkap').'
								<label class="label">Email :</label><input type="text" value="'.set_value('email').'" name="email" class="reg_input">'.form_error('email').'
								<label class="label">Alamat:</label><textarea class="reg_input" name="alamat">'.set_value('alamat').'</textarea>'.form_error('alamat').'
								<label class="label">Provinsi :</label>'.provinsi_dropdown().'<br />'.form_error('provinsi').'
								<label class="label">Kota :</label>'.provinsi_kota_dropdown().'<br />'.form_error('provinsi_kota').'
								<label class="label">Telephone :</label><input type="text" name="no_telepon" value="'.set_value('no_telepon').'" class="reg_input">'.form_error('no_telepon').'
								<label class="label">Handphone :</label><input name="no_handphone" type="text" value="'.set_value('no_handphone').'" class="reg_input">'.form_error('no_handphone').'
								<label class="label">Daftar jadi Member? </label><input type="radio" id="yes" value="yes" name="reg_chc">
								<label class="option" for="yes">Ya</label><input name="reg_chc" type="radio" value="no" id="no" checked="checked">
								<label class="option" for="no">Tidak</label><br><input type="submit" class="bigbtn" name="reg_submit" value="Lanjutkan >">
								</form>';

					$display .= '</div>';				

					$display .=	'<div class="check_transaction" id="buy_with_membership">';
					$display .= '<h3>Login Member</h3>';
					$display .= '<p>Masukkan email dan password untuk login (atau silahkan login menggunakan akun <a href="">facebook</a> atau <a href="">twitter</a> (Anda) untuk menyelesaikan pembelian :</p>';
					$display .= '<form action="'.base_url().'user/login" method="post" accept-charset="utf-8">
								<label class="label">Email :</label><input type="text" name="email" class="reg_input" value=""><br>
								<label class="label">Password :</label><input type="password" name="password" class="reg_input"><br>
								<input type="checkbox" name="remember" id="remember">
								<label for="remember" class="option" >Ingat Password</label>
								<p class="forgot_password"><a href="'.base_url().'user/lupa_password">Apakah Anda Lupa Password ? Klik disini</a>
								</p><input type="submit" class="bigbtn" value="Login!"></form>';
					
					$display .= '</div>';
				}

				$display .= '<p class="textnote">Berikut adalah daftar belanjaan Anda : </p>';
				$display .= '<table id="shopping_cart" class="pemeriksaan" cellpadding="6" cellspacing="1" style="width:100%" border="0">';
				$display .= '<tr>	
							  <th width="70%">Item Detail</th>
							  <th width="10%">Qty</th>					  					  
							  <th width="20%" style="text-align:right">Jumlah</th>
							</tr>';	


				/* mengambil semua id product dari cart */
				foreach ($cart as $items){			
					$productID[] = $items['id'];
				}	


				if(!empty($productID)){
					/* ambil detil produk */
					$_this->load->model('Global_model');
					$detailproduct = $_this->Global_model->selectwherein($productID, 'kp_posts');	

					// echo $detailproduct['333']['post_name'];					
					/*  pengulangan isi keranjang belanja */
					foreach ($cart as $items){	

						if(!empty($sess['price']) && (!empty($sess['totalongkir']))){

						}

						$atr = unserialize(base64_decode($detailproduct[$items['id']]['post_attribute']));					
						$display .= '<tr>
										<td><img src="'.get_thumb($detailproduct[$items['id']]['post_image'],64,64,1).'" class="image_product" />
										<h5 class="name_product">'. humanize($items['name']) .'</h5>
										<p class="desc_product">'.character_limiter(strip_tags($detailproduct[$items['id']]['post_content']), 45).' (@ Rp '.@number_format($items['price'],0,",",".").' / '.$atr['berat'].' '.$atr['satuanberat'].')</p></td>
										<td style="text-align:center">'. $items['qty'] .'</td>							
										<td style="text-align:right" class="total_product">Rp '.@number_format($items['subtotal'],0,",",".") .'</td>
									</tr>';		

					}

					$display .= '<tr><td colspan="2" style="text-align:right" class="jne_taxx">Total biaya pengiriman (JNE) ke '.@$sess['kec'].'</td><td style="text-align:right" class="jne_cost">Rp '.@number_format($sess['totalongkir'],0,",",".").'</td></tr>';
					$display .= '<tr><td colspan="2" style="text-align:right"><h4>Total yang harus dibayar</h4></td><td style="text-align:right"> <h4 class="sum_product">Rp '.@number_format($_this->cart->total() + $sess['totalongkir'],0,",",".").'</h4></td></tr>';
				}

				else{
					$display .= '<tr><td colspan="3">Anda belum memilih produk</td></tr>';
				}		

				$display .= '</table>';									
			}	

			/* array for all */
			$title = $_this->custom->list_page;

			$arraytodisplay = array(
								'post_content' => $display,
								'post_title' => $title[$param] ,										
								'post_date' => '',
								'post_type' => '',
								'ID' => '',
								'post_category' => '',
								'post_name' => '', 
								'post_price' => '', 
								'post_stock' => '', 
								'post_stock' => '', 
								'comment_count' => '', 
							);

			return $arraytodisplay;	
		}
	}

	function pembelian_selesai($param){

		$_this =& get_instance();
		$_this->load->helper('inflector');
		$_this->load->library('cart');

		// print_r($_this->session->userdata);

		// echo $_this->session->userdata('session_id');
		$domainatr = $_this->main->allAtr ;
		$all_ts_array = $_this->template->templatesetting;
		$template_setting = unserialize(base64_decode($all_ts_array['option_value']));
		$alldata = $_this->session->userdata;	
		$_this->load->model('Post_model');
		$cart = $_this->cart->contents();		
		
		/* if(@$template_setting['store_setting']['template_email'] == 'yes') */
		$display = '<p>Terima kasih telah melakukan pembelian produk di website kami, untuk prosedur pembayaran silahkan baca selengkapnya di bawah ini ... </p>';
		
		$order_list = '<p>Berikut adalah daftar belanjaan Anda : </p>';		
		$order_list .= '<table id="shopping_cart" cellpadding="6" cellspacing="1" style="width:100%" border="0">';
		$order_list .= '<tr>	
					  <th width="70%">Item Detail</th>
					  <th width="10%">Qty</th>					  					  
					  <th width="20%" style="text-align:right">Jumlah</th>
					</tr>';	

					
		foreach ($cart as $items){			
			$productID[] = $items['id'];
		}		


		if(!empty($productID)){
			/* ambil detil produk */
			$_this->load->model('Global_model');
			$detailproduct = $_this->Global_model->selectwherein($productID, 'kp_posts');	

			// echo $detailproduct['333']['post_name'];					
			/*  pengulangan isi keranjang belanja */
			foreach ($cart as $items){	

				if(!empty($sess['price']) && (!empty($sess['totalongkir']))){

				}

				$atr = unserialize(base64_decode($detailproduct[$items['id']]['post_attribute']));					
				$order_list .= '<tr>
										<td><img src="'.get_thumb($detailproduct[$items['id']]['post_image'],64,64,1).'" class="image_product" />
										<h5 class="name_product">'. humanize($items['name']) .'</h5>
										<p class="desc_product">'.character_limiter(strip_tags($detailproduct[$items['id']]['post_content']), 45).' (@ Rp '.@number_format($items['price'],0,",",".").' / '.$atr['berat'].' '.$atr['satuanberat'].')</p></td>
										<td style="text-align:center">'. $items['qty'] .'</td>							
										<td style="text-align:right" class="total_product">Rp '.@number_format($items['subtotal'],0,",",".") .'</td>
									</tr>';			

			}
		}					


		

		$order_list .= '</table>';

		/* daftar rekening */
		$rekening = NULL;
		$totalrekening = count(@$template_setting['store_setting']['jenis_rekening']);

		for($x=0;$x<$totalrekening;$x++){
			@$rekening .= '<strong>Rekening Bank '.$template_setting['store_setting']['jenis_rekening'][$x].'</strong><br />';
			@$rekening .= 'Atas nama : '.$template_setting['store_setting']['atasnama'][$x].'<br />';
			@$rekening .= 'No. Rekening : '.$template_setting['store_setting']['no_rek'][$x].'<br /><br />';
		}

		$array_search = array(
			'%%order_list%%',
			'%%nama%%',
			'%%alamat%%',
			'%%kota%%',
			'%%provinsi%%',
			'%%email%%',
			'%%hp%%',
			'%%telephone%%',
			'%%hargabarang%%',
			'%%ongkoskirim%%',
			'%%random_rp%%',
			'%%totaltransfer%%',
			'%%order_no%%', 
			'%%website%%', 
			'%%confirm_page%%',
			'%%handphone_admin%%',
			'%%telephone_admin%%',
			'%%payment_list%%'
			);

		$array_replace = array(
			@$order_list,
			@$alldata['nama_lengkap'],
			@$alldata['alamat'],
			humanize(@$alldata['provinsi_kota']),
			humanize(@$alldata['provinsi']),
			@$alldata['email'],
			@$alldata['no_handphone'],
			@$alldata['no_telepon'],
			number_format(@$_this->cart->total(),0,",","."),
			number_format(@$alldata['totalongkir'],0,",","."),
			@$alldata['random'],
			number_format(@$_this->cart->total() + @$alldata['random'] + @$alldata['totalongkir'],0,",","."),
			@$alldata['id'], 
			'<a href="'.base_url().'">'.base_url().'</a>', 
			'<a href="'.base_url().'halaman/'.$template_setting['store_setting']['hal_pil_konfirmasi'].'">'.base_url().'halaman/'.$template_setting['store_setting']['hal_pil_konfirmasi'].'</a>',
			$template_setting['store_setting']['handphone'],
			$template_setting['store_setting']['telepon'],
			$rekening
			);

		
		
		$display .= '<div id="invoice_detail">'.str_replace($array_search, $array_replace, $template_setting['store_setting']['template_email']).'</div>';
		
		/* unset all data store */

		$arrayunset = array(
				'cart_contents' => '', 
				'total_transfer' => '',
				'nama_lengkap' => '',
				'email' => '',
				'alamat' => '',
				'provinsi' => '',
				'provinsi_kota' => '',
				'no_telepon' => '',
				'no_handphone' => '',
				'random' => '',
				'id' => '',
				'jne' => '',
				'kec' => '',
				'price' => '',
				'totalongkir' => ''
			);

		$_this->session->unset_userdata($arrayunset);

		/* array for all */
		$title = $_this->custom->list_page;

		$arraytodisplay = array(
							'post_content' => $display,
							'post_title' => $title[$param] ,										
							'post_date' => '',
							'post_type' => '',
							'ID' => '',
							'post_category' => '',
							'post_name' => '', 
							'post_price' => '', 
							'post_stock' => '', 
							'post_stock' => '', 
							'comment_count' => '', 
						);

		return $arraytodisplay;		
	}

	function login($param){

		$_this =& get_instance();
		$_this->load->library('form_validation');
		/* 	masukkan array post ke dalam variable post */
		$post = $_this->input->post();
		$_this->main->form_valid_conf();
		$title = $_this->custom->list_page;

		$_this->form_validation->set_error_delimiters('<p class="error">', '</p>');

		$display = NULL;
		
		if($_this->session->userdata('logged_in') == TRUE){
			redirect(base_url().'user/transaksi');
		}

		/* jika validasi tidak sesuai dengan kriteria */
		if($_this->form_validation->run('login') == FALSE){	


			$logindisplay = '<p>Masukkan email dan password untuk login (atau silahkan login menggunakan akun <a href="">facebook</a> atau <a href="">twitter</a> (Anda) untuk menyelesaikan pembelian :</p>';
			$logindisplay .= '<div class="user_form"><form action="'.base_url().'user/login" method="post" accept-charset="utf-8">
						<label class="label">Email :</label><input type="text" name="email" class="reg_input" value="'.set_value('email').'">'.form_error('email').'<br>
						<label class="label">Password :</label><input type="password" name="password" class="reg_input">'.form_error('password').'<br>
						<input type="checkbox" name="remember" id="remember">
						<label for="remember" class="inblock" >Ingat Password</label>
						<p class="forgot_password"><a href="'.base_url().'user/lupa_password">Apakah Anda Lupa Password ? Klik disini</a>
						</p><input type="submit" class="submit" value="Login!"></form></div>';


			$display = array(
							'post_content' => $logindisplay,
							'post_title' => $title[$param] ,										
							'post_date' => '',
							'post_type' => '',
							'ID' => '',
							'post_category' => '',
							'post_name' => '', 
							'post_price' => '', 
							'post_stock' => '', 
							'post_stock' => '', 
							'comment_count' => '', 
						);			
		}
		else{

			$_this->load->model('Site_model');
			/* 	username dan password */
			$email = $post['email'];
			$password = $post['password'];
			
			/* 	tampilkan password yang di dapatkan dari database lewat site_model */
			$userlogin = $_this->Site_model->login($email);
			
			/* 	save password from db to variable */
			$passworddb = $userlogin->password;
			
			/* 	hash password */
			$passwordhash = $_this->main->hash_passwd($password, $passworddb);
			
			/* 	jika tidak ada perbedaan antara yang di masukkan dalam database 
				dan di masukkan dalam form */
			if(strcasecmp($passwordhash, $passworddb) == 0){
			
				/* yang di perlukan untuk tiap halaman user */
				$array_logged_in = array(
											'user_id' => $userlogin->id,
											'email' => $userlogin->email,
											'name' => $userlogin->name,
											'logged_in' => TRUE 
										);

				if($userlogin->reseller > 0){
					$array_logged_in['reseller'] = $userlogin->reseller_type;
					$array_logged_in['password'] = $post['password']; 
				}
										
				/* keperluan akses tiap halaman */
				$_this->session->set_userdata($array_logged_in);				


				if(strpos($_SERVER['HTTP_REFERER'],'pemeriksaan_pembelian') > 0){
					redirect(base_url().'produk/pemeriksaan_pembelian');
				}
				else{
					redirect(base_url().'user/dashboard');
				}			
				
			}
			else{
				/* keperluan untuk menampilkan error yang terjadi */
				set_form_error(array('error_msg'=>'Mohon maaf, Anda salah menginputkan <strong>email atau password</strong> , Silahkan coba kembali!'));
				redirect(base_url().'user/login/');
			}			
		}

		return $display;
	}	

	function dashboard($param){
		global $SConfig;
		$_this =& get_instance();

		$title = $_this->custom->list_page;

		$domainatr = $_this->main->allAtr ;
		
		if($_this->main->allAtr->domain_name == $SConfig->store){
			$post_content = '<p>Selamat datang <b>'.$_this->session->userdata('name').'</b>, Anda bisa melakukan aksi sebagai berikut : </p>';
			$post_content .= '<ul class="dash_menu">';
			$post_content .= '<li><a href="/user/transaksi">Lihat History Transaksi</a></li>';
			$post_content .= '<li><a href="/user/setting">Setting Akun</a></li>';
			$post_content .= '<li><a href="/user/setting" class="loginlink">Masuk Website Reseller</a></li>';
			$post_content .='</ul>';
		}
		else{
			$post_content = '<p>Selamat datang <b>'.$_this->session->userdata('name').'</b>, Anda bisa melakukan aksi sebagai berikut : </p>';
			$post_content .= '<ul class="dash_menu">';
			$post_content .= '<li><a href="/user/transaksi">Lihat History Transaksi</a></li>';
			$post_content .= '<li><a href="/user/setting">Setting Akun</a></li>';			
			$post_content .='</ul>';

		}

		$display = array(
				'post_content' => $post_content,
				'post_title' => $title[$param] ,										
				'post_date' => '',
				'post_type' => '',
				'ID' => '',
				'post_category' => '',
				'post_name' => '', 
				'post_price' => '', 
				'post_stock' => '', 
				'post_stock' => '', 
				'comment_count' => '', 
			);	

		return $display;
	}

	function register($param){
		global $SConfig;
		$_this =& get_instance();
		$title = $_this->custom->list_page;
		$_this->load->library('form_validation');
		$_this->load->library('passwd');
		$_this->load->helper('provkotkab_helper');
		$_this->form_validation->set_error_delimiters('<p class="error">', '</p>');
		$post = $_this->input->post(NULL, TRUE);
		$domainatr = $_this->main->allAtr ;
		$regdisplay = NULL;

		if($_this->uri->segment(1) == 'reseller' && ($_this->main->allAtr->domain_name == $SConfig->store)){
			if($_this->form_validation->run('register') == FALSE){	
				$title[$param] = 'Formulir Pendaftaran Reseller';
				$regdisplay = '<p class="headin">Untuk melakukan pendaftaran reseller silahkan mengisi formulir ini :</p>';
				$regdisplay .= '<div id="form_reg" class="user_form">';				
				$regdisplay .= '<form action="'.base_url().'user/register" method="post" id="regform" accept-charset="utf-8">';
				$regdisplay .= '<label for="jenis_reseller" class="label">Jenis Reseller</label>';
				$regdisplay .= '<select name="jenis_reseller" id="jenis_reseller"><option value="">pilih jenis reseller</option>';
				$regdisplay .= '<option value="silver">Reseller SILVER - Rp 1.155.000</option>';
				$regdisplay .= '<option value="basic">Reseller BASIC - Rp 345.000</option>';
				$regdisplay .= '<option value="system">Reseller + System - Rp 99.000</option>';
				$regdisplay .= '<option value="free">Reseller FREE (Manual) - Rp 0</option>';
				$regdisplay .= '</select><br class="floating">';
				$regdisplay .= '<div class="formreseller">';			
				$regdisplay .= '<p class="note">Untuk detil fasilitas reseller silahkan <a href="#">klik disini</a></p>';
				$regdisplay .= '<label for="nama_lengkap" class="label">Nama Lengkap</label><input value="" autocomplete="off" class="textlong" type="text" name="nama_lengkap" id="nama_lengkap">';			
				$regdisplay .= '<p class="note" id="nama_lengkap_status">Mohon isikan nama lengkap sesuai dengan KTP</p>';
				$regdisplay .= '<label for="" class="label">Provinsi</label>'.provinsi_dropdown().'<br class="floating">';
				$regdisplay .= '<label for="" class="label">Kota</label>'.provinsi_kota_dropdown().'<br class="floating">';
				$regdisplay .= '<label for="alamat_lengkap" class="label">Alamat</label><textarea name="alamat_lengkap" id="alamat_lengkap" class="alamat"></textarea>';											
				$regdisplay .= '<label for="emai_reg " class="label">Email</label><input value="" autocomplete="off" class="textlong" type="text" name="email_reg" id="email_reg">';			
				$regdisplay .= '<p id="email_reg_status" class="status "></p>';
				$regdisplay .= '<label for="password_reg" class="label">Password</label><input value="" autocomplete="off" type="password" class="textlong" name="password_reg" id="password_reg">';			
				$regdisplay .= '<p id="password_reg_status" class="status "></p>';
				$regdisplay .= '<label for="no_hp_reg" class="label">No.Handphone</label><input value="" autocomplete="off" class="textlong" type="text" name="no_hp_reg" id="no_hp_reg">';			
				$regdisplay .= '<p id="no_hp_reg_status" class="status"></p>';
				$regdisplay .= '<label for="alamat_web" class="label">Alamat Website</label><input value="" autocomplete="off" class="textlong domain" type="text" name="alamat_web" id="alamat_web">';
				$regdisplay .= '<select name="tld" id="tld"><option value="-" selected>-</option></select>';			
				$regdisplay .= '<p id="alamat_web_status" class="status"></p>';
				$regdisplay .= '<input type="checkbox" name="syarat" id="syarat" value="yes"><label for="syarat" class="inblock">Saya sudah membaca dan menyetujui <a href="http://www.kaffah.biz/artikel/news/syarat_dan_ketentuan">Syarat dan Ketentuan</a></label>';
				$regdisplay .= '<br class="floating"><input type="submit" name="daftar" id="daftar" disabled="disabled" value="Daftar Sekarang!" class="reg_submit disabled">';
				$regdisplay .= '</div>';
				$regdisplay .= '<div class="iffree">Untuk <strong>reseller FREE (Rp 0)</strong>, silahkan melakukan pendaftaran via SMS/WhatsApp <br />ke Nomor <strong>0838 11 575 876</strong>, dengan format SMS/WA sebagai berikut: <br /><br />DAFTAR RESELLER#NAMA LENGKAP#NO HP#NO WHATSAPP#ALAMAT LENGKAP<br /><br /><strong>Contoh:</strong><br />Daftar Reseller # Loka Dwiartara # 083819377575 # 083819377575 # Jl. Durian Raya No.14, Bogor, Jawa Barat<br /><br /> Kirim ke Nomor <strong>0838 11 575 876</strong></div>';
				$regdisplay .= '</form></div>';
			}
			else{

			}
				
		}
		else{
			if($_this->form_validation->run('register') == FALSE){	
				$regdisplay = '<p>Masukkan email (atau gunakan akun <a href="">facebook</a> atau <a href="">twitter</a> (Anda) untuk melakukan registrasi :</p>';
				$regdisplay .= '<div class="user_form"><form action="'.base_url().'user/register" method="post" accept-charset="utf-8">
							<label class="label">Email :</label><input type="text" name="email" class="reg_input" value="">'.form_error('email').'<br>																	
							<input type="submit" class="submit" value="Daftar!"></form></div>';
			}

			else{

				$_this->load->model('Global_model');	
				$randcode = getRandomWord(8);
				$date = date('Y-m-d H:i:s');
				$password = $_this->passwd->to_password($randcode);

				/* daftar user baru */
				$arraynewmember = array(
										'name' => $post['email'],
										'password' => $password ,
										'email' => $post['email'],
										'activation_code' => $randcode,
										'created_on' => $date,
								);

				/* jika member belum terdaftar di table user maka buat di table user, 
				jika sudah ada maka hanya buat di table multi site login */
				$userexist = $_this->Global_model->select_single(array('email' => $post['email']), 'kp_users');

				$arraymultisitelogin = array(
								'user_id' => $userexist['id'],
								'type' => 'user',
								'blog_id' => $domainatr->domain_id
							);

				$multisiteexist = $_this->Global_model->select_single(array('user_id' => $userexist['id']), 'kp_multi_sitelogin');

				if(count($userexist) > 0){
					if(count($multisiteexist) > 0){
						$regdisplay  = '<p>Register member telah selesai, silahkan cek email Anda untuk info lengkapnya. Silahkan cari email yang sebelumnya pernah dikirim oleh noreply@kaffah.biz. (Mohon mengecek bulk/spam jika tidak terdapat di inbox)</p>';
						/* 1. kirim email di sini tempatnya */
					}
					else{
						$_this->Global_model->add($arraymultisitelogin,'kp_multi_sitelogin');
						$regdisplay  = '<p>Register member telah selesai, silahkan cek email Anda untuk info lengkapnya. Silahkan cari email yang sebelumnya pernah dikirim oleh noreply@kaffah.biz. (Mohon mengecek bulk/spam jika tidak terdapat di inbox)</p>';	
						/* 2. kirim email di sini tempatnya */				
					}

				}
				else{
					if($_this->Global_model->add($arraynewmember,'kp_users')){
						/* relasi dengan tabel multi site login */										

						$userexist = $_this->Global_model->select_single(array('email' => $post['email']), 'kp_users');

						$arraymultisitelogin = array(
										'user_id' => $userexist['id'],
										'type' => 'user',
										'blog_id' => $domainatr->domain_id
									);

						if($_this->Global_model->add($arraymultisitelogin,'kp_multi_sitelogin')){
							$regdisplay  = '<p>Register member telah selesai,  silahkan cek email Anda untuk info lengkapnya. (Mohon mengecek bulk/spam jika tidak terdapat di inbox)</p>';
							

							$website = $domainatr->domain_name;
							$email = $post['email'];
							$activation_code = $randcode ;
							$emailreply = $domainatr->email ; 
							$password = $randcode;

							// $regdisplay .= "register1.pl $website $email $password $activation_code $emailreply";

							/* kirim email di sini tempatnya */
							$output=shell_exec("perl /var/www/kaffahbiz/temp/register1.pl $website $email $password $activation_code $emailreply");						
						}


					}
				}
			}
		}
			

		$display = array(
				'post_content' => $regdisplay,
				'post_title' => $title[$param] ,										
				'post_date' => '',
				'post_type' => '',
				'ID' => '',
				'post_category' => '',
				'post_name' => '', 
				'post_price' => '', 
				'post_stock' => '', 
				'post_stock' => '', 
				'comment_count' => '', 
			);	

		return $display;	
	}

	function transaksi($param){
		$_this =& get_instance();
		$title = $_this->custom->list_page;
		$_this->load->model('Post_model');
		$_this->load->model('Global_model');
		$domainatr = $_this->main->allAtr;
		$_this->load->helper('date');
		$_this->load->helper('inflector');

		if($_this->session->userdata('logged_in') == TRUE){
			$transaction = $_this->Post_model->get_all_transaction($domainatr->domain_id, $_this->session->userdata('user_id'));			
			$displaytransaksi = '<p>Berikut adalah daftar transaksi Anda : </p>' ;
			$displaytransaksi .= '<table id="transaction_table"><tr><th id="transaction_detail">Detil Transaksi</th><th id="transaction_status">Status</th><th id="transaction_total">Total Bayar</th><th id="transaction_action">Aksi</th></tr>';						

			
			if(!empty($transaction)){
				/* get array transaction id for looping detail */
				foreach ($transaction as $record) {
					$arraytransactionid[] = $record['transaction_id'];
				}

				/* model for looping detail */
				$transactiondetail = $_this->Global_model->selectwherein_what($arraytransactionid,'kp_trans_detail', 'transaction_id');
				
				foreach($transaction as $record){
					$time = strtotime($record['transaction_time']);
					$fixtime = mdate('%d/%m/%Y', $time);					
					$displaytransaksi .= '<tr>';
					$displaytransaksi .= '<td><strong>Invoice '.$record['transaction_id'].' | Tgl. '.$fixtime.'</strong>';
					$displaytransaksi .= '<div class="detail_transaction"><ul>';

					foreach ($transactiondetail as $row) {
						if($row['transaction_id'] == $record['transaction_id']){
							$displaytransaksi .= '<li>'.humanize($row['name']).' x '.$row['quantity'].' (@'.@number_format($row['price'],0,",",".").')';

							$displaytransaksi .= '</li>';
						}
					}

					$displaytransaksi .= '</ul><a href="" class="show_list">+ Klik untuk Detil</a>';
					$displaytransaksi .= '<div class="detail_order_list">';
					$displaytransaksi .= 'Jumlah : <strong>Rp '.@number_format($record['total'],0,",",".").'</strong><br>';
					$displaytransaksi .= 'Ongkos Kirim : <strong>Rp '.@number_format($record['total_tax'],0,",",".").'</strong> (JNE '.$record['tax_type'].') <br>';
					$displaytransaksi .= 'Angka Unik : <strong>Rp '.@number_format($record['random'],0,",",".").'</strong><br>';
					$displaytransaksi .= 'Total pembayaran : ';
					$displaytransaksi .= '<strong class="totalseluruh">Rp '.@number_format($record['all_total'],0,",",".").'</strong>';
					$displaytransaksi .= '</div>';
					$displaytransaksi .='</div>';
					$displaytransaksi .= '</td>';
					$displaytransaksi .= '<td style="text-align:center">'.$record['transaction_status'].'</td>';
					$displaytransaksi .= '<td style="text-align:right"><strong>Rp '.@number_format($record['all_total'],0,",",".").'</strong></td>';
					$displaytransaksi .= '<td style="text-align:center" class="action_button"><a class="btn reject_trasanction" href="" id="transaction_'.$record['transaction_id'].'">Gagal!</a></td>';
					$displaytransaksi .= '</tr>';
				}
			}

			else{
				$displaytransaksi .= '<tr><td colspan="4">Anda tidak memiliki transaksi...</td></tr>';
			}

			$displaytransaksi .= '</table>';

			$display = array(
					'post_content' => $displaytransaksi,
					'post_title' => $title[$param] ,										
					'post_date' => '',
					'post_type' => '',
					'ID' => '',
					'post_category' => '',
					'post_name' => '', 
					'post_price' => '', 
					'post_stock' => '', 
					'post_stock' => '', 
					'comment_count' => '', 
				);	
			return $display;
		}
		else{
			redirect(base_url().'user/login/');
		}	
	}

	function setting($param){
		$_this =& get_instance();
		$_this->load->library('form_validation');
		$domainatr = $_this->main->allAtr ;

		if($_this->session->userdata('logged_in') == TRUE){
			$title = $_this->custom->list_page;
			$_this->load->model('Global_model');
			$userexist = $_this->Global_model->select_single(array('user_id' => $_this->session->userdata('user_id')), 'kp_user_detail');

			if($_this->form_validation->run('setting') == FALSE){	
								
				
				$displaysetting = '<p>Anda bisa melakukan perubahan informasi akun Anda pada halaman ini : </p>

					<div class="user_form"><h4 class="blockheader">Data Profil</h4>

					<form action="'.base_url().'user/setting/save" method="POST">
					<label for="nama_depan" class="label">Nama Depan*</label><input type="text" class="reg_input" name="nama_depan" id="nama_depan" value="'.@$userexist['nama_depan'].'"><br />
					<label for="nama_belakang" class="label">Nama Belakang*</label><input type="text" class="reg_input" name="nama_belakang" id="nama_belakang" value="'.@$userexist['nama_belakang'].'"><br />

					<h4 class="blockheader">Data Kontak</h4>
					<label for="email" class="label">Email*</label><input type="text" class="reg_input" name="email" id="emailinput"  value="'.$_this->session->userdata('email').'"><br />
					<label for="homepage" class="label">Website</label><input type="text" class="reg_input" name="website" id="homepage" value="'.@$userexist['website'].'"><br />
					<label for="facebook" class="label">Facebook</label><input type="text" class="reg_input" name="facebook" id="facebook"  value="'.@$userexist['facebook'].'"><br />
					<label for="twitter" class="label">Twitter</label><input type="text" class="reg_input" name="twitter" id="twitter"  value="'.@$userexist['twitter'].'"><br />

					<h4 class="blockheader">Data Alamat</h4>
					<label for="alamat" class="label">Alamat Lengkap</label><textarea  class="reg_input" id="alamat" name="alamat">'.@$userexist['alamat'].'</textarea><br />
					<label for="provinsi" class="label">Provinsi</label><input type="text" class="reg_input" name="provinsi" id="provinsi"  value="'.@$userexist['provinsi'].'"><br />
					<label for="kota" class="label">Kota</label><input type="text" class="reg_input" name="kota" id="kota"  value="'.@$userexist['kota'].'"><br />
					<label for="kodepos" class="label">Kodepos</label><input type="text" class="reg_input" name="kodepos" id="kodepos"  value="'.@$userexist['kodepos'].'"><br />
					<label for="handphone" class="label">Handphone</label><input type="text" class="reg_input" name="handphone" id="handphone" value="'.@$userexist['handphone'].'"><br />

					<h4 class="blockheader">Data Akun</h4>
					<label for="password" class="label">Password Baru</label><input type="password" class="reg_input" name="password" id="password" value="">
					<p class="pnote">Kosongkan saja jika Anda tidak ingin merubah password sekarang</p>
					<input type="submit" class="bigbtn" value="Simpan!"></form></div>';

			}
			else{

				$post = $_this->input->post(NULL, TRUE);

				if(count($userexist) > 0){
					$where = array('blog_id' => $domainatr->domain_id, 'user_id' => $_this->session->userdata('user_id'));
					$array_update = array(
						'nama_depan' => $post['nama_depan'],
						'nama_belakang' => $post['nama_belakang'],
						'handphone' => $post['handphone'],
						'alamat' => $post['alamat'],
						'website' => $post['website'],
						'facebook' => $post['facebook'],
						'twitter' => $post['twitter'],
						'provinsi' => $post['provinsi'],
						'kota' => $post['kota'],
						'kodepos' => $post['kodepos'],
					);

					$_this->Global_model->update($where,$array_update,'kp_user_detail');
				}
				else{
					$array_add = array(
						'nama_depan' => $post['nama_depan'],
						'nama_belakang' => $post['nama_belakang'],
						'handphone' => $post['handphone'],
						'alamat' => $post['alamat'],
						'website' => $post['website'],
						'facebook' => $post['facebook'],
						'twitter' => $post['twitter'],
						'provinsi' => $post['provinsi'],
						'kota' => $post['kota'],
						'kodepos' => $post['kodepos'],
						'user_id' => $_this->session->userdata('user_id'),
						'blog_id' => $domainatr->domain_id
					);
					$_this->Global_model->add($array_add, 'kp_user_detail');

				}

				if(!empty($post['password'])){
					$_this->load->library('passwd');
					$password = $_this->passwd->to_password($post['password']);
					$_this->Global_model->update(array('id' => $_this->session->userdata('user_id') ),array('password' => $password ),'kp_users');
				}

				redirect(base_url().'user/setting');

			}


			$display = array(
					'post_content' => $displaysetting,
					'post_title' => $title[$param] ,										
					'post_date' => '',
					'post_type' => '',
					'ID' => '',
					'post_category' => '',
					'post_name' => '', 
					'post_price' => '', 
					'post_stock' => '', 
					'post_stock' => '', 
					'comment_count' => '', 
				);	
			return $display;	
		}
		else{
			redirect(base_url().'user/login/');
		}
	}

	function logout($param){
		$_this =& get_instance();
		$_this->session->sess_destroy();
		redirect(base_url().'user/login/');
	}	

	function lupa_password($param){
		$_this =& get_instance();
		$title = $_this->custom->list_page;
		$_this->load->library('form_validation');
		$_this->load->library('passwd');
		$_this->form_validation->set_error_delimiters('<p class="error">', '</p>');
		$post = $_this->input->post(NULL, TRUE);
		$domainatr = $_this->main->allAtr ;
		$regdisplay = NULL;
		$_this->load->helper('email');

		/*if($_this->form_validation->run('forgot_password') == FALSE){				
			$regdisplay = '<p>Masukkan email untuk melakukan reset password :</p>';
			$regdisplay .= '<div class="user_form"><form action="'.base_url().'user/lupa_password" method="post" accept-charset="utf-8">
						<label class="label">Email :</label><input type="text" name="email" class="reg_input" value="">'.form_error('email').'<br>																	
						</p><input type="submit" class="bigbtn" value="Reset Password!"></form></div>';
			
		}*/

		if(empty($post) || !valid_email($post['email'])){
			if((!empty($post)) && (!valid_email($post['email']))){
				$error_note = '<p class="error">Email tidak valid</p>';
			}
			else{
				$error_note = NULL;
			}

			$regdisplay = '<p>Masukkan email untuk melakukan reset password :</p>';
			$regdisplay .= '<div class="user_form"><form action="'.base_url().'user/lupa_password" method="post" accept-charset="utf-8">
						<label class="label">Email :</label><input type="text" name="email" class="reg_input" value="">'.$error_note.'<br>																	
						</p><input type="submit" class="bigbtn" value="Reset Password!"></form></div>';
		}

		else{

			$_this->load->model('Global_model');	
			$randcode = getRandomWord(6);
			$date = date('Y-m-d H:i:s');
			
			/* jika member belum terdaftar di table user maka buat di table user, 
			jika sudah ada maka hanya buat di table multi site login */
			$userexist = $_this->Global_model->select_single(array('email' => $post['email']), 'kp_users');

			if(count($userexist) > 0){

				if($_this->Global_model->update(array('email' => $post['email']), array('password' => $_this->passwd->to_password($randcode) ), 'kp_users')){
					/* 3. kirim email di sini tempatnya */

						$website = $domainatr->domain_name;
						$email = $post['email'];
						$activation_code = $randcode ;
						$emailreply = $domainatr->email; 
						$password = $randcode;

						// $regdisplay .= "register1.pl $website $email $password $activation_code $emailreply";
						/* kirim email di sini tempatnya */
						$output=shell_exec("perl /var/www/kaffahbiz/temp/lupa_password.pl $website $email $password $emailreply");	


					$regdisplay = '<p>Silahkan cek email Anda, password baru sudah dikirimkan...</p>';	
					//$regdisplay .= "lupa_password.pl $website $email $password $emailreply";
				}				
			}
			else{
				$regdisplay = '<p>Mohon maaf email tidak terdaftar...</p>';
			}
		}

		$display = array(
				'post_content' => $regdisplay,
				'post_title' => $title[$param] ,										
				'post_date' => '',
				'post_type' => '',
				'ID' => '',
				'post_category' => '',
				'post_name' => '', 
				'post_price' => '', 
				'post_stock' => '', 
				'post_stock' => '', 
				'comment_count' => '', 
			);	
		return $display;
	}

	function konfirmasi_selesai($param){
		$_this =& get_instance();
		$title = $_this->custom->list_page;
		$display = array(
				'post_content' => '<p>Terima kasih Anda telah melakukan konfirmasi, kurang dari 1x24 jam kami akan menghubungi Anda...</p>',
				'post_title' => $title[$param] ,										
				'post_date' => '',
				'post_type' => '',
				'ID' => '',
				'post_category' => '',
				'post_name' => '', 
				'post_price' => '', 
				'post_stock' => '', 
				'post_stock' => '', 
				'comment_count' => '', 
			);	
		return $display;		
	}

	function aktivasi($param){
		$_this =& get_instance();

		$aktdisplay = NULL;

		if(($_this->uri->total_segments() == 3 ) && ($_this->uri->segment(2) == 'aktivasi')){
			$code = $_this->uri->segment(3);
			$_this->load->model('Global_model');
			$useractive = $_this->Global_model->select_single(array('activation_code' => $code), 'kp_users');

			if(count($useractive) > 0){
				$_this->Global_model->update(array('activation_code' => $code),array('activation_code' => NULL, 'active' => '1'),'kp_users');
				$aktdisplay = '<p>Terima kasih, akun Anda telah aktif. Silahkan login <a href="'.base_url().'user/login">di sini</a> dengan menggunakan akun yang sebelumnya telah dikirimkan ke email Anda</p>';
			}
			else{
				$aktdisplay = '<p>Mohon maaf, aktivasi gagal, kode aktivasi tidak ada / sudah digunakan...</p>';
			}			
		}

		$title = $_this->custom->list_page;
		$display = array(
				'post_content' => $aktdisplay,
				'post_title' => $title[$param] ,										
				'post_date' => '',
				'post_type' => '',
				'ID' => '',
				'post_category' => '',
				'post_name' => '', 
				'post_price' => '', 
				'post_stock' => '', 
				'post_stock' => '', 
				'comment_count' => '', 
			);	
		return $display;
	}

	function kaffahConfirmationPage($display){
		global $SConfig;
		$_this =& get_instance();
		$setting = $_this->template->templatesetting;
		$templatesetting = unserialize(base64_decode(@$setting['option_value']));
		$rekening = array();
		$totalrekening = count(@$templatesetting['store_setting']['jenis_rekening']);
		$_this->load->library('form_validation');

		if($_this->form_validation->run('konfirmasi') == FALSE){	
			for($x=0;$x<$totalrekening;$x++){
				@$rekening[$templatesetting['store_setting']['jenis_rekening'][$x].'_'.$templatesetting['store_setting']['no_rek'][$x]] = 
				$templatesetting['store_setting']['jenis_rekening'][$x].' - '.$templatesetting['store_setting']['no_rek'][$x];
			}		

			$rekening = array_merge(array('' => 'Pilih No. Rek.'), @$rekening);

			$_this->load->helper('form');
			
			$formconfirm = '<div class="user_form">
							<form action="'.base_url().'halaman/'.$templatesetting['store_setting']['hal_pil_konfirmasi'].'/save" method="POST">
							<label class="label">Nama Lengkap :</label><input value="" type="text" name="nama_lengkap" class="reg_input"><br>
							<label class="label">Email :</label><input type="text" value="" name="email" class="reg_input"><br>
							<label class="label">Telephone / HP :</label><input type="text" name="no_telepon" value="" class="reg_input"><br>';
			
			$domainatr = $_this->main->allAtr ;
		
			if($_this->main->allAtr->domain_name == $SConfig->store){
				$formconfirm .= '<label class="label">Jenis Reseller :</label><input type="text" name="no_order" value="" class="reg_input qty"><br>';
			}
			else{
				$formconfirm .= '<label class="label">No. Invoice :</label><input type="text" name="no_order" value="" class="reg_input qty"><br>';
			}


			$formconfirm .= 	'<label class="label">Jumlah Bayar :</label><input type="text" name="jumlah_bayar" value="" class="reg_input price"><br>
							<label class="label">Pembayaran Ke : </label>'.form_dropdown('rekening', $rekening, '', ' class="zona"').'<br>
							<label class="label">Bank Pengirim:</label><input type="text" name="bank_pengirim" value="" class="reg_input"><br>
							<label class="label">Pemilik Rekening:</label><input type="text" name="pemilik_rekening" value="" class="reg_input"><br>
							<br /><input type="submit" class="bigbtn" value="Kirim Konfirmasi!">
							</form>
							</div>';

			$display = str_replace('%%form-confirmation%%', $formconfirm, $display);	
		}

		else{
			$post = $_this->input->post(NULL, TRUE);
			$date = date('Y-m-d H:i:s');
			$domainatr = $_this->main->allAtr ;
			$array_add_confirmation = array(
					'confirm_status' => 'pending',
					'name' => $post['nama_lengkap'],
					'email' => $post['email'],
					'contact' => $post['no_telepon'],
					'confirm_time' => $date,
					'total' => $post['jumlah_bayar'],
					'transaction_id' => $post['no_order'],
					'destination' => $post['rekening'],
					'source' => $post['bank_pengirim'],
					'source_name' => $post['pemilik_rekening'],
					'source_detail' => '',
					'blog_id' => $domainatr->domain_id
				);

			$_this->load->model('Global_model');
			$_this->Global_model->add($array_add_confirmation, 'kp_confirmation');

			redirect(base_url().'halaman/konfirmasi_selesai');	
		}		

		return $display;
	}	
}