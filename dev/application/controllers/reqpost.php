<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
ob_start(); 

class ReqPost extends CI_Controller {
	/* dari sini ini berpasangan dengan cat_article.php > template/back/default */
	function convertjson(){
		/* 
		ini digunakan untuk mengkonversi json ke 
		dalam array php kemudian di ke json kan lagi 
		agar serialize bisa di baca oleh json
		*/
		$serial = unserialize(base64_decode($_POST['post']['post_attribute']));
		echo json_encode($serial);
	}
	
	/* yang ini digunakan untuk mengkonversi json kedalam array php biasa */
	function convert_alljson(){
		$serial = @unserialize(base64_decode($_POST['post']));
		echo json_encode($serial);
	}				
	
	/* function ini untuk front end */
	function updateqty(){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
					&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){


		$post = $this->input->post(NULL, TRUE);
		$this->load->library('cart');
		$this->cart->update($post);
		$items = $this->cart->contents() ;

		$this->session->unset_userdata(
				array(
					'totalongkir' => '', 
					'jne' => '', 
					'kec' => '', 
					'price' => ''
				)

			);

		/* json for update shopping cart and total */
		$array_update = array(	'total' => 'Rp '.number_format($this->cart->total(),0,",",".") );

		if($_POST['qty'] > 0){
			$array_update['subtotal'] = 'Rp '.number_format($items[$_POST['rowid']]['subtotal'],0,",",".");
		}

		echo json_encode($array_update);

		}
	}

	function jnefixtax(){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
					&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){

			$this->load->library('cart');
			$cart = $this->cart->contents();
			$post = $this->input->post(NULL, TRUE);
			$totalberat = NULL;
			$totalongkir = NULL;

			if(!empty($cart)){

				foreach ($cart as $items){			
					$productID[] = $items['id'];
				}				

				$this->load->model('Global_model');

				$detailproduct = $this->Global_model->selectwherein($productID, 'kp_posts');	

				foreach ($cart as $items){	
					$atr = unserialize(base64_decode($detailproduct[$items['id']]['post_attribute']));
					$qty = $items['qty'];
					$berat = $atr['berat'];	
					$totalberat = $qty * $berat;
					$ongkirjne = (ceiling($totalberat,1000) / 1000) * $post['price'];
					$totalongkir += $ongkirjne ;
				}	

				$post['totalongkir'] = $totalongkir;
				$post['total_transfer'] = $totalongkir + $this->cart->total();

				$this->session->set_userdata($post);

				echo json_encode($post);
			}		

		}
	}

	/* *************************************** */
	/* ************ ADMIN ARTICLE ************ */
	/* *************************************** */
	
	/* ARTICLE LIST SECTION */

	function getallcat_except($type,$blogid,$slug=NULL){
		/* check if superadmin domain is logged in */
		$this->main->is_logged_in();

		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			
			$post = $this->input->post(NULL, TRUE);

			if(!empty($slug)){
				$slug = array($slug);
			}
			else{
				$slug = NULL;
			}
			$this->load->model('Post_model');
			$postcategory = $this->Post_model->get_all_cat($type,NULL,$this->main->getUser('dom_id'),$slug);
			
			/* jika domainnya ada */
			if(!empty($postcategory)){
				echo json_encode($postcategory);	
			}
			
		}			
	}

	/* dari sini ini berpasangn dengan newarticle.php > template/back/default */
	function pagingarticle($page,$type=NULL){
		/* check if superadmin domain is logged in */
		$this->main->is_logged_in();

		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			/* jika session belum di isi */
			$pagesession = $this->session->userdata('pagecurrent');		

			if($this->session->userdata('pagingtype') == $type){
				if(!empty($pagesession)){
					$array_page = array('pagecurrent' => $page, 'pagingtype' => $type);
					$this->session->set_userdata($array_page);			
				}
				else{
					$array_page = array('pagecurrent' => $page, 'pagingtype' => $type);
					$this->session->set_userdata($array_page);						
				}			
			}
			else{
					if(empty($page)){
						$array_page = array('pagecurrent' => '1', 'pagingtype' => $type);
						$this->session->unset_userdata($array_page);
					}
					else{
						$array_page = array('pagecurrent' => $page, 'pagingtype' => $type);
						$this->session->set_userdata($array_page);	
					}		
			}

			echo json_encode($array_page);
		}
	}	

	function paginginit($type,$status,$blogid,$mediafilter=NULL,$contentfilter=NULL){
		/* check if superadmin domain is logged in */
		$this->main->is_logged_in();

		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
					&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
		
			/* 	algoritma paging berapa banyak */
			$this->load->model('Global_model');			
			
			$where_array = array(
									'post_type' => $type,
									'blog_id' => $this->main->getUser('dom_id'),
								);
			
			/* ketika status tidak ada */
			if($status != '-'){
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

	function massdraftarticle(){
		/* check if superadmin domain is logged in */
		$this->main->is_logged_in();		
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			
			$this->load->model('Post_model');
			$post = $this->input->post(NULL, FALSE);
			if(isset($post['postid'])){
				$wherein = $post['postid'];
			}
			else{
				$wherein = NULL;
			}			
			
			$this->Post_model->mass_update_article( $wherein , $this->main->getUser('dom_id'), array('kp_posts.post_status' => 'draft'));
		}		
	}

	function massdelarticle(){
		/* check if superadmin domain is logged in */
		$this->main->is_logged_in();		
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		
			$this->load->model('Post_model');
			$post = $this->input->post(NULL, FALSE);
			if(isset($post['postid'])){
				$wherein = $post['postid'];
			}
			else{
				$wherein = NULL;
			}
			$this->Post_model->mass_del_article( $wherein , $this->main->getUser('dom_id'));
		}
	}	

	function delsinglearticle(){
		/* check if superadmin domain is logged in */
		$this->main->is_logged_in();		
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
					&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			$this->load->model('Global_model');
			$post = $this->input->post(NULL, TRUE);
			$where = array(	
							'post_author' => $this->main->getUser('user_id') ,
							'blog_id' => $this->main->getUser('dom_id'),
							'ID' => $post['id']
						);
			
			if($this->Global_model->del($where,'kp_posts')){
				return true;
			}
			else{
				return false;
			}
		}
	}	
 
	function getallarticle($type,$status,$blogid,$limit,$offset,$mediafilter=NULL,$contentfilter=NULL){
		/* check if superadmin domain is logged in */
		$this->main->is_logged_in();		
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			$this->load->model('Post_model');
			echo json_encode($this->Post_model->get_all_article($type,$status,$this->main->getUser('dom_id'),$limit,$offset,$mediafilter,$contentfilter));	
		}
	}

	/* CAT SARTICLE LIST SECTION */
	function ajax_arr_litem(){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			
			$iseditexist = strpos($_POST['postdata'],'Edit | Hapus');
			
			if($iseditexist > 0){
				$komma = str_replace(',','',$_POST['postdata']);
				$editdel = substr(str_replace('Edit | Hapus',',',$komma),0,-4);
				$postdata = explode(",",$editdel);
				$blogid = $this->main->getUser('dom_id');

			}
			else{
				$postdata = explode(",",substr($_POST['postdata'],0,-1));	
				// print_r($postdata);				
				$blogid = $this->main->getUser('dom_id');
			}
			
			/* arrange to database */
			for($x=0;$x<count($postdata);$x++){
				$sort = $x + 1;
				$sql = "UPDATE kp_terms SET `sort` = ?  WHERE `slug` = ? AND `term_blog_id` = ? AND term_user_id = ? ";
				$this->db->query($sql, array($sort,url_title(trim($postdata[$x]),'_', TRUE),$blogid,$this->main->getUser('user_id')) );
			}
			
			
		}	
	}		

	function getallcat_($type,$blogid){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
					&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			$this->load->model('Post_model');
			$postcategory = reListCat($this->Post_model->get_all_cat($type,NULL,$this->main->getUser('dom_id')));
			
			/* jadikan hirarki di setiap kategori */
			echo recursivePrint($postcategory,$type);
			
		}
	}

	function delcat(){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
				&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			
			$this->load->model('Global_model');
			$post = $this->input->post(NULL, TRUE);
			$where = array(	
							'term_blog_id' => $this->main->getUser('dom_id'),
							'slug' => $post['category']
						);
			
			$this->Global_model->del($where,'kp_terms');
		}
	}

	function editcat($type){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			
			$post = $this->input->post(NULL, TRUE);
			$this->load->model('Post_model');
			$array_json = $this->Post_model->get_cat($type,$post['slug'],$this->main->getUser('dom_id'));

			foreach($array_json as $key => $val){
				if($key == 'attribute'){
					$arrayy[$key] = unserialize(base64_decode($val));
				}
				else{
					$arrayy[$key] = $val;	
				}
				
			}
			
			echo json_encode($arrayy);
		}
	}	

	function addnewcat(){
		$this->main->is_logged_in();
		/* hanya bisa di akses oleh ajax call saja */
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
	
			$this->load->model('Global_model');
			$this->load->model('Post_model');
			/* selalu gunakan null true agar bisa menghilangkan XSS */
			$post = $this->input->post(NULL, TRUE);
			
			$atr = base64_encode(serialize(array('image' => @$post['imgcat'])));

			/* array untuk request json category initialize */
			$array_cat_add = array( 
									'name' => $post['title'],
									'slug' => url_title($post['title'],'_', TRUE),
									'parent' => $post['parentcat'],
									'desc' => $post['desc'],									
									'parent' => $post['parentcat'],
									'term_type' => $post['category_type'],
									'term_user_id' => $this->main->getUser('user_id'),
									'term_blog_id' => $this->main->getUser('dom_id')
								);
			if(!empty($post['imgcat'])){
				$atr = base64_encode(serialize(array('image' => $post['imgcat'])));
				$array_cat_add['attribute'] = $atr;
			}
			else{
				$atr = base64_encode(serialize(array())); 
				$array_cat_add['attribute'] = $atr;
			}			
			
			/* encode menjadi json */
			$iscatExist = $this->Post_model->is_cat_exist($post['category_type'], url_title($post['title'],'_', TRUE), $this->main->getUser('dom_id'));
			
			/* ketika kategorinya itu ada */
			if($iscatExist == FALSE){
				$this->Global_model->addNew($array_cat_add, 'kp_terms');	
				$array_cat_add['response'] = 'SUCCESS';
			}
			else{
				$array_cat_add['response'] = 'FAILED';
			}

			echo json_encode($array_cat_add);
		}		
	}		

	function updatecat(){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
		
			$post = $this->input->post(NULL, TRUE);
			$this->load->model('Global_model');						

			$array_where = array(
									'term_user_id' => $this->main->getUser('user_id'),
									'term_blog_id' => $this->main->getUser('dom_id'),
									'term_id' => $post['term_id']
								);
								
			$array_update = array(
									'name' => $post['title'],
									'slug' => url_title($post['title'],'_', TRUE),
									'parent' => $post['parentcat'],
									'desc' => $post['desc'],									
									'parent' => $post['parentcat'],
								);

			if(!empty($post['imgcat'])){
				$atr = base64_encode(serialize(array('image' => $post['imgcat'])));
				$array_update['attribute'] = $atr;
			}
			else{
				$atr = base64_encode(serialize(array())); 
				$array_update['attribute'] = $atr;
			}
								
			$this->Global_model->update($array_where,$array_update,'kp_terms');
			
			echo json_encode($post);
		}
	}

	function cat_init(){
		$this->main->is_logged_in();
		/* hanya bisa di akses oleh ajax call saja */
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			
			$this->load->model('Post_model');
			/* selalu gunakan null true agar bisa menghilangkan XSS */
			$post = $this->input->post(NULL, TRUE);
			
			if(!empty($post['title'])){
				/* pengecekan untuk kategori itu eksis, berdasarkan title dan blog_id */
				$iscatExist = $this->Post_model->is_cat_exist('category_article',url_title($post['title'],'_', TRUE), $this->main->getUser('dom_id'));
													
				/* array untuk request json category initialize */
				$array_cat_init = array(
										'cat_exist' => $iscatExist, 
										'title' => $post['title'],
										'blog_id' => $this->main->getUser('dom_id'),
										'desc' => $post['desc'],
										'parentcat' => $post['parentcat']
									);
				
				/* encode menjadi json */
				echo json_encode($array_cat_init);
			}
		}
	}

	/* edit article section */
	function editarticle($type,$id,$blogid){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){			
			$this->load->model('Post_model');
			$array_json = $this->Post_model->get_article($type,$id,$this->main->getUser('dom_id'));			
			echo json_encode($array_json);
		}		
	}

	function updatearticle(){
		global $SConfig;
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){	
			
			/* ambil data dari yang dikirimkan */			
			$post = $this->input->post(NULL, TRUE);
			$warning = array();
			$parent = '';
			$category = '';
			$postcode = '';
			$postprice = '';
			$postimage = '';
			$poststock = '';
			$atribut = '';
			$img = '';
			
			/* jika category,judul, dan jenis postnya adalah artikel */	
			if( ( !isset($post['category']) || (empty($post['titlearticle'])) ) && ($post['post_type'] == 'post')  ){
				/* siapkan peringatan untuk kesalahan */
				if(!isset($post['category'])) $warning['warning_category'] = 'Anda belum memilih kategori Artikel, jika belum ada silahkan lakukan tambah kategori';
				else $warning['warning_category'] = '';
				
				/* warning untuk judul yang kosong */
				if(empty($post['titlearticle'])) $warning['warning_titlearticle'] = 'Anda belum mengisi judul artikel...';
				else $warning['warning_titlearticle'] = '';
				
				/* keluarkan warning dalam bentuk json */
				echo json_encode($warning);
				
			}

			/* jika category,judul, dan jenis postnya adalah artikel */	
			else if( ( !isset($post['category']) || (empty($post['titlearticle'])) ) && ($post['post_type'] == 'product')  ){
				/* siapkan peringatan untuk kesalahan */
				if(!isset($post['category'])) $warning['warning_category'] = 'Anda belum memilih kategori Produk, jika belum ada silahkan lakukan tambah kategori';
				else $warning['warning_category'] = '';
				
				/* warning untuk judul yang kosong */
				if(empty($post['titlearticle'])) $warning['warning_titlearticle'] = 'Anda belum mengisi nama produk ...';
				else $warning['warning_titlearticle'] = '';
				
				/* keluarkan warning dalam bentuk json */
				echo json_encode($warning);
				
			}

			/* jika category,judul, dan jenis postnya adalah halaman */	
			else if((empty($post['titlearticle'])) && ($post['post_type'] == 'page')  ){
				/* warning untuk judul yang kosong */
				if(empty($post['titlearticle'])) $warning['warning_titlearticle'] = 'Anda belum mengisi judul artikel...';
				else $warning['warning_titlearticle'] = '';
				
				/* keluarkan warning dalam bentuk json */
				echo json_encode($warning);			
			}

			else{
				$this->load->model('Global_model');
				$this->load->model('Post_model');
				$this->load->helper('inflector');
				
				if(@$post['status'] == 'draft'){
					$status = 'draft';
				}
				else{
					$status = 'publish';
				}
				
				/* for date */
				$date = $post['year'].'-'.$post['month'].'-'.$post['date'].' '.$post['hour'].':'.$post['minute'] ;
				$datemodified = date('Y-m-d H:i:s');
				
				/* for comment */
				if(!isset($post['comment'])) $post['comment'] = 'off';
				if(!isset($post['notification'])) $post['notification'] = 'off';
					
				$atr = array(	'title' => $post['metatitle'], 
								'metakeyword' => $post['metakeyword'], 
								'metadescription' => $post['metadescription']
							);
				
				/* ketika jenis postnya adalah artikel maka masukkan kategorinya */			
				if($post['post_type'] == 'post' || $post['post_type'] == 'product') { 
					$category = implode(',',$post['category']); 
					$wheretrigger = array('blog_id' => $this->main->getUser('dom_id'), 'ID' => $post['article_id'] , 'post_author' => $this->main->getUser('user_id') );
					$title = humanize(url_title($post['titlearticle'],'_', TRUE));
					$post['titlearticle'] = $title;
					/* jika yang dikirimkan itu jenis postnya adalah product */
					if($post['post_type'] == 'product'){
						/* atribut yang akan digunakan untuk produk */
						$postcode = $post['product_code'];
						$postprice = $post['product_price'];						
						$poststock = $post['product_stock'];	
						$postpriceold = $post['product_price_old'];										

						/* ini untuk post image */
						if(!empty($post['postimage'])) { 
							$postimage = $post['postimage'] ;
							$warning['warning_image'] = NULL;
						} 
						else{ 
							$postimage = '';
							$warning['warning_image'] = 'Anda belum mengupload gambar (minimal 2 gambar)';
						}
						
						/* ini untuk post img */
						if(!empty($post['img'])) { 
							$img = $post['img']; 
							$warning['warning_image'] = NULL;
						}
						else{ 
							$img = ''; 
							$warning['warning_image'] = 'Anda belum mengupload gambar (minimal 2 gambar)';
						}						

						/* atribut yang digunakan dalam produk */
						$atribut = array(
										'berat' => $post['berat'],
										'satuanberat' => $post['satuanberat'],
										'tinggi' => $post['tinggi'],
										'satuantinggi' => $post['satuantinggi'],
										'lebar' => $post['lebar'],
										'satuanlebar' => $post['satuanlebar'],
										'panjang' => $post['panjang'],
										'satuanpanjang' => $post['satuanpanjang'],
										'warna' => $post['product_colour'],
										'ukuran' => $post['product_size'],
										'img' => $img
								);
						
						/* satukan array menuju kesatuan yang hakiki dan luar biasa */
						$atr = array_merge($atr,$atribut);						
						
					}
				} 

				else if($post['post_type'] == 'page'){
					$parent = $post['parentpage']; 
					$wheretrigger = array('blog_id' => $this->main->getUser('dom_id'), 'post_name' => $post['page_name'] , 'post_author' => $this->main->getUser('user_id') );
				}
											
				/* array for new article */
				$update_array = array(	
					'post_author' => $this->main->getUser('user_id'), 		
					'post_date' => $date,	
					'post_date_modified' => $datemodified,
					'post_date_gmt' => '',	 				
					'post_content' => $_POST['editor1'],	
					'post_title' => $_POST['titlearticle'], 	
					'post_status' => $status,	
					'comment_status' => $post['comment'], 	
					'notif_status' => $post['notification'],	
					'ping_status' => '',					
					'post_password' => '',
					'post_name' => url_title($post['titlearticle'],'_', TRUE),	
					'post_modified' => '',					
					'post_modified_gmt' => '',	
					'post_content_filtered' => '', 
					'post_parent' => $parent,	
					'guid' => '',							
					'menu_order' => '',	
					'post_type' => $post['post_type'],					
					'post_code' => $postcode,	
					'post_price' => toMoney($postprice),	
					'post_price_old' => toMoney($postpriceold),	
					'post_stock' => $poststock,	
					'post_image' => $postimage,						
					'post_attribute' => base64_encode(serialize($atr)),	
					'post_market' => '',	
					'post_category' => $category,	
					'post_moderation' => 0,
					'post_position' => '',					
					'post_counter' => '',	
					'post_mime_type' => '',		
					'blog_id' => $this->main->getUser('dom_id')
				);		

				$array_detail_product = $this->Post_model->get_article('product',$post['article_id'],$this->main->getUser('dom_id'));	
				if(!empty($array_detail_product['post_parent'])){
					unset($update_array['post_price']);
					unset($update_array['post_title']);
					unset($update_array['post_name']);
					unset($update_array['post_price_old']);
					unset($update_array['post_stock']);
					unset($update_array['post_category']);
					unset($update_array['post_parent']);
				}


				if($SConfig->storeID == $this->main->getUser('dom_id')){					 
					$update_array['post_reseller'] = @$post['product_reseller'];
					$update_array['post_basic'] =  @toMoney($post['harga_modal']);
					$update_array['post_reseller_fee'] = @toMoney($post['komisi_reseller']);
					$update_array['post_ks_fee'] = @toMoney($post['komisi_ks']);

					

					$this->Global_model->update(
						array(	'post_parent' => $post['article_id'] ),
						array(	'post_price' => toMoney($postprice),
								'post_stock' => $poststock, 
								'post_name' => url_title($post['titlearticle'],'_', TRUE), 
								'post_title' => $post['titlearticle'], 
								'post_status' => $status,
								'post_category' => $category
							),
								'kp_posts');
				}		
				
				if(@$this->Global_model->update($wheretrigger,$update_array,'kp_posts')){

					$warning['success'] = 'TRUE';
					$warning['page'] = url_title($post['titlearticle'],'_', TRUE);
				}
				else{
					$warning['success'] = 'FALSE';
				}
				
				echo json_encode($warning);
	
			}
		}		
	}

	function getallcatli($type,$blogid){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){			
			echo listcat($type,$this->main->getUser('dom_id'));
		}
	}

	/* add article section */		
	function addnewarticle(){
		global $SConfig;
		$this->main->is_logged_in();

		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){	
			
			/* ambil data dari yang dikirimkan */			
			$post = $this->input->post(NULL, TRUE);
			$warning = array();
			$parent = '';
			$category = '';
			$postcode = '';
			$postprice = '';
			$postimage = '';
			$poststock = '';
			$img = '';
			$atribut = '';
			
			/* jika category,judul, dan jenis postnya adalah artikel */	
			if( ( !isset($post['category']) || (empty($post['titlearticle'])) ) && ($post['post_type'] == 'post')  ){
				/* siapkan peringatan untuk kesalahan */
				if(!isset($post['category'])) $warning['warning_category'] = 'Anda belum memilih kategori Artikel, jika belum ada silahkan lakukan tambah kategori';
				else $warning['warning_category'] = '';
				
				/* warning untuk judul yang kosong */
				if(empty($post['titlearticle'])) $warning['warning_titlearticle'] = 'Anda belum mengisi judul artikel...';
				else $warning['warning_titlearticle'] = '';
				
				/* keluarkan warning dalam bentuk json */
				echo json_encode($warning);
				
			}
			
			/* jika category,judul, dan jenis postnya adalah produk */	
			else if( ( !isset($post['category']) || (empty($post['titlearticle'])) ) && ($post['post_type'] == 'product')  ){
				/* siapkan peringatan untuk kesalahan */
				if(!isset($post['category'])) $warning['warning_category'] = 'Anda belum memilih kategori produk, jika belum ada silahkan lakukan tambah kategori';
				else $warning['warning_category'] = '';
				
				/* warning untuk judul yang kosong */
				if(empty($post['titlearticle'])) $warning['warning_titlearticle'] = 'Anda belum mengisi nama produk...';
				else $warning['warning_titlearticle'] = '';
				
				/* keluarkan warning dalam bentuk json */
				echo json_encode($warning);
				
			}
			/* jika category,judul, dan jenis postnya adalah halaman */	
			else if((empty($post['titlearticle'])) && ($post['post_type'] == 'page')  ){
				/* warning untuk judul yang kosong */
				if(empty($post['titlearticle'])) $warning['warning_titlearticle'] = 'Anda belum mengisi judul artikel...';
				else $warning['warning_titlearticle'] = '';
				
				/* keluarkan warning dalam bentuk json */
				echo json_encode($warning);			
			}

			else{
				$this->load->model('Global_model');
				$this->load->model('Post_model');
				$this->load->helper('inflector');
				
				if(checkarticle($post['titlearticle'], $this->main->getUser('dom_id')) > 0){
					$post['titlearticle'] .= ' 2';
				}
				
				if(@$post['status'] == 'draft'){
					$status = 'draft';
				}
				else{
					$status = 'publish';
				}
				
				/* for date */
				$date = $post['year'].'-'.$post['month'].'-'.$post['date'].' '.$post['hour'].':'.$post['minute'] ;
				$datemodified = date('Y-m-d H:i:s');
				
				/* for comment */
				if(!isset($post['comment'])) $post['comment'] = 'off';
				if(!isset($post['notification'])) $post['notification'] = 'off';
					
				$atr = array(	'title' => $post['metatitle'], 
								'metakeyword' => $post['metakeyword'], 
								'metadescription' => $post['metadescription']
								
							);
				
				/* ketika jenis postnya adalah artikel maka masukkan kategorinya */			
				if($post['post_type'] == 'post' || $post['post_type'] == 'product' ) { 
					$category = implode(',',$post['category']); 
					$title = humanize(url_title($post['titlearticle'],'_', TRUE));
					$post['titlearticle'] = $title;

					/* jika yang dikirimkan itu jenis postnya adalah product */
					if($post['post_type'] == 'product'){
						/* atribut yang akan digunakan untuk produk */
						$postcode = $post['product_code'];
						$postprice = $post['product_price'];
						$postpriceold = $post['product_price_old'];
						
						/* ini untuk post image */
						if(!empty($post['postimage'])) { 
							$postimage = $post['postimage'] ;
							$warning['warning_image'] = NULL;
						} 
						else{ 
							$postimage = '';
							$warning['warning_image'] = 'Anda belum mengupload gambar (minimal 2 gambar)';
						}
						
						/* ini untuk post img */
						if(!empty($post['img'])) { 
							$img = $post['img']; 
							$warning['warning_image'] = NULL;
						}
						else{ 
							$img = ''; 
							$warning['warning_image'] = 'Anda belum mengupload gambar (minimal 2 gambar)';
						}
						
						$poststock = $post['product_stock'];
						
						
						/* atribut yang digunakan dalam produk */
						$atribut = array(
										'berat' => $post['berat'],
										'satuanberat' => $post['satuanberat'],
										'tinggi' => $post['tinggi'],
										'satuantinggi' => $post['satuantinggi'],
										'lebar' => $post['lebar'],
										'satuanlebar' => $post['satuanlebar'],
										'panjang' => $post['panjang'],
										'satuanpanjang' => $post['satuanpanjang'],
										'warna' => $post['product_colour'],
										'ukuran' => $post['product_size'],
										'img' => $img
								);
						
						/* satukan array menuju kesatuan yang hakiki dan luar biasa */
						$atr = array_merge($atr,$atribut);
						
					}
					
					else{
						$img = NULL; 
						$warning['warning_image'] = NULL;
					}
				} 
				else if($post['post_type'] == 'page'){
					$parent = $post['parentpage'];
					
				}
				
				
				/* array for new article */
				$addnew_array = array(	
					'post_author' => $this->main->getUser('user_id'), 		
					'post_date' => $date,	
					'post_date_modified' => $datemodified,
					'post_date_gmt' => '',	 				
					'post_content' => $_POST['editor1'],	
					'post_title' => $_POST['titlearticle'], 	
					'post_status' => $status,	
					'comment_status' => $post['comment'], 	
					'notif_status' => $post['notification'],	
					'ping_status' => '',					
					'post_password' => '',
					'post_name' => url_title($post['titlearticle'],'_', TRUE),	
					'post_modified' => '',					
					'post_modified_gmt' => '',	
					'post_content_filtered' => '', 
					'post_parent' => $parent,	
					'guid' => '',							
					'menu_order' => '',	
					'post_type' => $post['post_type'],					
					'post_code' => $postcode,	
					'post_price_old' => toMoney($postpriceold),	
					'post_price' => toMoney($postprice),	
					'post_stock' => $poststock,						
					'post_image' => $postimage,						
					'post_attribute' => base64_encode(serialize($atr)),	
					'post_market' => '',	
					'post_category' => $category,	
					'post_moderation' => 0,
					'post_position' => '',					
					'post_counter' => '',	
					'post_mime_type' => '',					
					'comment_count' => '',	
					'blog_id' => $this->main->getUser('dom_id')
				);											
	
				

				//echo $SConfig->storeID;
				$dom_id = $this->main->getUser('dom_id');
				if($SConfig->storeID == $dom_id && $post['post_type'] == 'product' ){
					if(isset($post['product_reseller'])){
						$addnew_array['post_reseller'] = @$post['product_reseller'];
	                                        $addnew_array['post_basic'] =  toMoney($post['harga_modal']);
        	                                $addnew_array['post_reseller_fee'] = toMoney($post['komisi_reseller']);
                	                        $addnew_array['post_ks_fee'] = toMoney($post['komisi_ks']);
					}					 
				}

				/* membatasi penambahan produk */
				$this->load->model('Site_model');
				$domaindetail = $this->Site_model->getdomaindetail($this->main->getUser('dom_id'));
				$productrows = $this->Global_model->numrows(array('blog_id' => $this->main->getUser('dom_id'), 'post_type' => 'product'), 'kp_posts');						

				if( $productrows < $domaindetail['packet_product'] || $domaindetail['packet_product'] == 'unlimited'){
					if($this->Post_model->articletitle_is_exist($post['titlearticle'], $this->main->getUser('dom_id')) == 0){
						
						if(@$warning['warning_image'] != NULL){
							$warning['success'] = 'FALSE';
						}
						else{
							if(@$this->Global_model->addNew($addnew_array,'kp_posts')){
								$warning['success'] = 'TRUE';
								$warning['img'] = @$_POST['img'];
							}
							else{
								$warning['success'] = 'FALSE';
							}							
						}
						
					}
					else{
							$warning['success'] = 'FALSE';
					}
				}
				else{
					$warning['limit'] = 'TRUE';
					$warning['packet_product'] = $domaindetail['packet_product'];
					$warning['success'] = 'FALSE';
				}
	
				echo json_encode($warning);
	
			}

		}		
	}

	/* *************************************** */
	/* ************ ADMIN PAGE ************ */
	/* *************************************** */
	
	/* PAGE LIST SECTION */
	function ajax_arr_litem_page(){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			
			$iseditexist = strpos($_POST['postdata'],'Edit | Hapus');
			
			if($iseditexist > 0){
				$komma = str_replace(',','',$_POST['postdata']);
				$komma = str_replace('(draft)','',$komma);
				$editdel = substr(str_replace('Edit | Hapus',',',$komma),0,-4);
				$postdata = explode(",",$editdel);
				$blogid = $this->main->getUser('dom_id');

			}
			else{
				$postdata = explode(",",substr($_POST['postdata'],0,-1));	
				// print_r($postdata);				
				$blogid = $this->main->getUser('dom_id');
			}
			
			/* arrange to database */
			for($x=0;$x<count($postdata);$x++){
				$sort = $x + 1;
				// UPDATE kp_posts SET menu_order = ? WHERE  post_name = ? AND blog_id = ? AND post_author = ? ;
				// $sql = "UPDATE kp_terms SET `sort` = ?  WHERE `slug` = ? AND `term_blog_id` = ? AND term_user_id = ? ";
				$sql = "UPDATE kp_posts SET menu_order = ? WHERE  post_name = ? AND blog_id = ? AND post_author = ?";
				$this->db->query($sql, array($sort,url_title(trim($postdata[$x]),'_', TRUE),$blogid,$this->main->getUser('user_id')) );
				//echo $postdata[$x];
			}						
		}	
	}

	function getallpage_($type,$blogid){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$this->load->model('Post_model');
			$pagelist = reListCatPage($this->Post_model->get_all_article($type,'',$this->main->getUser('dom_id'),0,0));
			// print_r($pagelist);
			// reListCat($this->Post_model->get_all_cat($type,$this->main->getUser('user_id'),$blogid));
			
			/* jadikan hirarki di setiap kategori */
			echo recursivePrintPage($pagelist);	
		}	
	}

	function delsinglepage(){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			$this->load->model('Global_model');
			$post = $this->input->post(NULL, TRUE);
			$where = array(	
							'post_author' => $this->main->getUser('user_id') ,
							'blog_id' => $this->main->getUser('dom_id'),
							'post_name' => $post['post_name']
						);
			
			if($this->Global_model->del($where,'kp_posts')){
				$wherechildren = array(	
							'post_author' => $this->main->getUser('user_id') ,
							'blog_id' => $this->main->getUser('dom_id'),
							'post_parent' => $post['post_id']
						);
				if($this->Global_model->del($wherechildren,'kp_posts')){
				
					return true;
				}
				
				else{
					return false;
				}		
						
				
			}
			else{
				return false;
			}
		}
	}

	function editpage($type,$name,$blogid){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			
			$this->load->model('Post_model');
			$array_json = $this->Post_model->get_page($type,$name,$this->main->getUser('dom_id'));
			
			echo json_encode($array_json);
		}		
	}		

	function getallpage($type,$blogid){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
					&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
						
			$this->load->model('Post_model');
			echo json_encode($this->Post_model->get_all_article($type,'',$this->main->getUser('dom_id'),0,0));	
		}		
	}		

	/* *************************************** */
	/* ************ ADMIN PRODUCT ************ */
	/* *************************************** */
	
	/* PRODUCT LIST SECTION */	
	function masspubarticle(){
		/* check if superadmin domain is logged in */
		$this->main->is_logged_in();

		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			
			$this->load->model('Post_model');
			$post = $this->input->post(NULL, FALSE);
			if(isset($post['postid'])){
				$wherein = $post['postid'];
			}
			else{
				$wherein = NULL;
			}			
			
			$this->Post_model->mass_update_article( $wherein , $this->main->getUser('dom_id'), array('kp_posts.post_status' => 'publish'));
		}		
	}	
	
	function massdelproduct(){
		/* check if superadmin domain is logged in */
		$this->main->is_logged_in();		
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		
			$this->load->model('Post_model');
			$post = $this->input->post(NULL, FALSE);
			if(isset($post['postid'])){
				$wherein = $post['postid'];
			}
			else{
				$wherein = NULL;
			}
			$this->Post_model->mass_del_article( $wherein , $this->main->getUser('dom_id'));
		}
	}

	function upload(){
		$this->main->is_logged_in();	
		
		$this->load->model('Global_model');
		$this->main->create_dir();
		$date = date('Y-m-d H:i:s');
		
		$yeardir = date('Y');
		$monthdir = date('M');
		$datedir = date('d');
		$datepath = date('d-M-Y');
		
		/* loading librart for upload */
		$this->load->library('upload', $this->main->media_upload_config());
		
		if ($this->upload->do_upload('userfile')){
			$upload_data = $this->upload->data();
			$upload_data['alttext'] = '';
			$upload_data['description'] = '';
			
			$filefullpath = base_url().'uploads/'.$yeardir.'/'.$monthdir.'/'.$datedir.'/'.$this->main->getUser('user_id').'/'.$upload_data['file_name'];

			$addnew_array = array(	
				'post_author' => $this->main->getUser('user_id'), 		
				'post_date' => $date,	
				'post_date_gmt' => '',	 				
				'post_content' => '',	
				'post_title' => $upload_data['file_name'], 	
				'post_status' => 'inherit',	
				'comment_status' => '', 	
				'notif_status' => '',	
				'ping_status' => '',					
				'post_password' => '',
				'post_name' => $upload_data['file_name'],	
				'post_modified' => '',					
				'post_modified_gmt' => '',	
				'post_content_filtered' => '', 
				'post_parent' => '',	
				'guid' => $filefullpath,							
				'menu_order' => '',	
				'post_type' => 'attachment',					
				'post_price' => '',	
				'post_image' => '',						
				'post_attribute' => base64_encode(serialize($upload_data)),	
				'post_market' => '',	
				'post_category' => '',	
				'post_position' => '',					
				'post_counter' => '',	
				'post_mime_type' => $upload_data['file_type'],					
				'comment_count' => '',	
				'blog_id' => $this->main->getUser('dom_id')
			);				
			
		
			if(@$this->Global_model->addNew($addnew_array,'kp_posts')){
				$array_where_get = array(
										'post_name' => $upload_data['file_name'],
										'post_author' => $this->main->getUser('user_id'),
										'post_type' => 'attachment', 
										'blog_id' => $this->main->getUser('dom_id'),
									);

				$warning = $this->Global_model->select($array_where_get, 'kp_posts', 'ID', 'DESC');
				$warning['success'] = 'TRUE';
				$warning['url'] = $filefullpath;
				
				/* jika */
				if(!empty($_POST['type']) && ($_POST['type'] == 'product')){
					$warning['img_original'] = $filefullpath;
					$warning['img'] = $this->main->resize_img($filefullpath,100,100,1);	
				}	

				if(!empty($_POST['tag'])){
					$warning['tag'] = $_POST['tag'];
				}
			
			}

			else{
				$warning['success'] = 'FALSE';
			}
			
			echo json_encode($warning);

		}
		else{
			$warning['success'] = 'FALSE';
			echo json_encode($warning);
		}

	}

	function mass_delete_transaction($blogid=null){
		/* check if superadmin domain is logged in */
		$this->main->is_logged_in();

		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			$this->load->model('Global_model');
			$this->load->helper('url');
			$post = $this->input->post();
			$orderid = $post['orderid'];
			$array_where = array('blog_id' => $this->main->getUser('dom_id'));	
			$tables = array('kp_shipping', 'kp_transaction', 'kp_trans_detail');
			if($this->Global_model->del($array_where,$tables,TRUE,$orderid,'transaction_id')){
				echo json_encode(array('status'=>TRUE));
			}	
		}
		
	}

	function mass_update_transaction($blogid=null,$status=null){
		/* check if superadmin domain is logged in */
		$this->main->is_logged_in();

		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){		
			$this->load->model('Global_model');
			$this->load->helper('url');
			$post = $this->input->post();
			switch($status){
				case 'pending' : $status = 'pending' ; break;
				case 'sudah_transfer' : $status = 'sudah_transfer' ; break;
				case 'sudah_dikirim' : $status = 'sudah_dikirim' ; break;
				case 'gagal' : $status = 'gagal' ; break;
				default: break;
			}

			$orderid = $post['orderid'];

			$array_update = array('transaction_status' =>  $status);
			$array_where = array('blog_id' => $this->main->getUser('dom_id'));		
			if($this->Global_model->update($array_where,$array_update,'kp_transaction',TRUE,$orderid,'kp_transaction.transaction_id')){
				echo json_encode(array('status'=>TRUE));
			}
		}
	}

	function delete_transaction($blogid=null){
		$this->main->is_logged_in();

		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){		
			$this->load->model('Global_model');
			$this->load->helper('url');
			$post = $this->input->post();
			
			$array_where = array('blog_id' => $this->main->getUser('dom_id'), 'transaction_id' => $post['transaction_id']);	
			$tables = array('kp_shipping', 'kp_transaction', 'kp_trans_detail');
			if($this->Global_model->del($array_where,$tables)){
				echo json_encode(array('status'=>TRUE));
			}	
		}	
	}	


	/* dari sini ini berpasangn dengan newarticle.php > template/back/default */
	function pagingorder($page){
		$this->main->is_logged_in();

		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){	
			/* jika session belum di isi */
			$pagesession = $this->session->userdata('pageordercurrent');
			
			if(!empty($pagesession)){
				$array_page = array('pageordercurrent' => $page);
				$this->session->set_userdata($array_page);			
			}
			else{
				$array_page = array('pageordercurrent' => $page);
				$this->session->set_userdata($array_page);						
			}
			
			echo json_encode($array_page);
		}		

	}	

	function updatetransaction($blogid=null){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$this->load->model('Global_model');
			$this->load->helper('url');
			$post = $this->input->post();
			
			$array_update_transaction = array(
					'transaction_status' => $post['status'],
					'all_total' => preg_replace("/[., ]/", "", $post['all_total']),
					'transfer_destination' => $post['transfer_destination'],
					'tracking_number' => $post['tracking_number'],
				);

			$array_update_shipping = array(
					'nama_lengkap' => $post['nama_lengkap'],
					'email' => $post['email'],
					'no_handphone' => $post['no_handphone'],
					'kota' => url_title($post['kota'], '_'),
					'provinsi' => url_title($post['provinsi'], '_'),
					'alamat' => $post['alamat'],
					'no_telepon' => $post['no_telepon']

				);

			$array_where = array('blog_id' => $this->main->getUser('dom_id'), 'transaction_id' => $post['transaction_id']);

			if($this->Global_model->update($array_where,$array_update_transaction,'kp_transaction') && 
				$this->Global_model->update($array_where,$array_update_shipping,'kp_shipping')){

				$this->load->model('Site_model');
				$domainatr = $this->Site_model->getDomainAtr($this->main->getUser('dom_id'),$this->main->getUser('user_id'));										
				if(!empty($domainatr['domain_reseller'])){
					$this->load->helper('inflector');

					if($post['status'] == 'konfirmasi_transfer'){
						$ke = ' ke '.$post['transfer_destination'];
					}
					else{
						$ke = '';
					}

					$array_update_transaction_reseller = array(
						'transaction_temp_status' => 'Permintaan '.humanize($post['status']).$ke
					);

					$array_where_reseller = array('transaction_parent' => $post['transaction_id']);
					$this->Global_model->update($array_where_reseller,$array_update_transaction_reseller,'kp_transaction');
					
				}

				echo json_encode(array('status'=>TRUE));
			}
		}			
	}

	/* yang sebelah sini digunakan untuk mengambil semua order yang ada di blog yang sedang dibuka */
	function getorder($blogid,$limit=NULL,$offset=NULL,$mediafilter=NULL,$contentfilter=NULL){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$this->load->model('Post_model');
			$allorder = $this->Post_model->getallorder($this->main->getUser('dom_id'),$limit,$offset,$mediafilter,$contentfilter);
			echo json_encode($allorder);
		}	
	}	

	function getdetailtransaction($blogid=NULL){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$this->load->model('Post_model');
			$post = $this->input->post();	
			$alldetailorder = $this->Post_model->gettransactiondetail($this->main->getUser('dom_id'),$post['transaction_id']);	

			$detail = array();

			foreach($alldetailorder as $key => $val){
				foreach($val as $k => $v){
					if($k == 'option'){
						$detail[$key][$k] = unserialize(base64_decode($v));
					}
					else{
						$detail[$key][$k] = $v;	
					}
					
				}
			}			

			echo json_encode($detail);
		}		
	}

	function orderpaginginit($blogid,$mediafilter=NULL,$contentfilter=NULL){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
		
			/* 	algoritma paging berapa banyak */
			$this->load->model('Global_model');

			$where_array = array( 'kp_shipping.blog_id' => $this->main->getUser('dom_id') );

			/* konfigurasi untuk paging */
			$total_rows = $this->Global_model->numrows($where_array,'kp_shipping',$mediafilter,$contentfilter);			
			
			$per_page = getconfig('limit');
			$total_page = ceil($total_rows / getconfig('limit')) ;			
			$array_json = array(	
								'banyak_baris' => $total_rows ,
								'per_page'	=>	$per_page,
								'total_page' => $total_page
								);
			
			/* tampilkan dalam json */							
			// echo $total_rows;
			echo json_encode($array_json);			

		}
	}	

	function mass_update_confirmation($blogid=null,$status=null){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){		
			$this->load->model('Global_model');
			$this->load->helper('url');
			$post = $this->input->post();
			switch($status){
				case 'pending' : $status = 'pending' ; break;
				case 'sudah_transfer' : $status = 'sudah_transfer' ; break;
				case 'gagal' : $status = 'gagal' ; break;
				default: break;
			}

			$confirmationid = $post['confirmationid'];

			$array_update = array('confirm_status' =>  $status);
			$array_where = array('blog_id' => $this->main->getUser('dom_id'));		
			if($this->Global_model->update($array_where,$array_update,'kp_confirmation',TRUE,$confirmationid,'confirm_id')){
				echo json_encode(array('status'=>TRUE));
			}
		}
	}

	function mass_delete_confirmation($blogid=null){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){		
			$this->load->model('Global_model');
			$this->load->helper('url');
			$post = $this->input->post();
			$confirmationid = $post['confirmationid'];
			$array_where = array('blog_id' => $this->main->getUser('dom_id'));	

			if($this->Global_model->del($array_where,'kp_confirmation',TRUE,$confirmationid,'confirm_id')){
				echo json_encode(array('status'=>TRUE));
			}	
		}		
	}	


	function updateconfirmation($blogid=null){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$this->load->model('Global_model');
			$this->load->helper('url');
			$post = $this->input->post();

			$array_update_confirmation = array(
					'confirm_status' => $post['status'],
					'total' => preg_replace("/[., ]/", "", $post['total']),
					'destination' => $post['destination'],
					'name' => $post['name'],
					'email' => $post['email'],
					'contact' => $post['contact'],
					'source' => $post['source'],
					'source_name' => $post['source_name'],
					'source_detail' => $post['source_detail']
				);			
			
			$array_where = array('blog_id' => $this->main->getUser('dom_id'), 'confirm_id' => $post['confirmation_id']);

			if($this->Global_model->update($array_where,$array_update_confirmation,'kp_confirmation')){
				echo json_encode(array('status'=>TRUE));
			}

		}			
	}	

	function delete_confirmation($blogid=null){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$this->load->model('Global_model');
			$this->load->helper('url');
			$post = $this->input->post();
			
			$array_where = array('blog_id' => $this->main->getUser('dom_id'), 'kp_confirmation.confirm_id' => $post['confirmation_id']);	

			if($this->Global_model->del($array_where,'kp_confirmation')){
				echo json_encode(array('status'=>TRUE));
			}	
		}	
	}	

	/* yang sebelah sini digunakan untuk mengambil semua order yang ada di blog yang sedang dibuka */
	function getconfirmation($blogid,$limit=NULL,$offset=NULL,$mediafilter=NULL,$contentfilter=NULL){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$this->load->model('Post_model');
			$allconfirmation = $this->Post_model->getallconfirmation($this->main->getUser('dom_id'),$limit,$offset,$mediafilter,$contentfilter);
			echo json_encode($allconfirmation);
		}	
	}	

	function pagingconfirmation($page){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			/* jika session belum di isi */
			$pagesession = $this->session->userdata('pageconfirmationcurrent');
			
			if(!empty($pagesession)){
				$array_page = array('pageconfirmationcurrent' => $page);
				$this->session->set_userdata($array_page);			
			}
			else{
				$array_page = array('pageconfirmationcurrent' => $page);
				$this->session->set_userdata($array_page);						
			}
			
			echo json_encode($array_page);
		}		
	
	}	


	function confirmationpaginginit($blogid,$mediafilter=NULL,$contentfilter=NULL){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
		
			/* 	algoritma paging berapa banyak */
			$this->load->model('Global_model');

			$where_array = array( 'kp_confirmation.blog_id' => $this->main->getUser('dom_id') );

			/* konfigurasi untuk paging */
			$total_rows = $this->Global_model->numrows($where_array,'kp_confirmation',$mediafilter,$contentfilter);			
			
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

	/* *************************************** */
	/* ************ COMMENT LIST ************ */
	/* *************************************** */	

	function commentpaginginit($blogid,$status=NULL,$mediafilter=NULL,$contentfilter=NULL){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
					&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
		
			/* 	algoritma paging berapa banyak */
			$this->load->model('Global_model');
			
			$where_array = array( 'comment_blog_id' => $this->main->getUser('dom_id') );

			/* jika comment approve ada isinya */
			if(!empty($status) && ($status != '-')){
				$where_array['comment_approved'] = $status;
			}	

			/* konfigurasi untuk paging */
			$total_rows = $this->Global_model->numrows($where_array,'kp_comments',$mediafilter,$contentfilter);			

			/* ... */
			$per_page = getconfig('limit');
			$total_page = ceil($total_rows / getconfig('limit')) ;			
			$array_json = array(	
								'banyak_baris' => $total_rows ,
								'per_page'	=>	$per_page,
								'total_page' => $total_page
								);
			
			/* tampilkan dalam json */							
			// echo $total_rows;
			echo json_encode($array_json);
		}
	}	

	/* dari sini ini berpasangn dengan newarticle.php > template/back/default */
	function pagingcomment($page){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){		
			/* jika session belum di isi */
			$pagesession = $this->session->userdata('pagecommentcurrent');
			
			if(!empty($pagesession)){
				$array_page = array('pagecommentcurrent' => $page);
				$this->session->set_userdata($array_page);			
			}
			else{
				$array_page = array('pagecommentcurrent' => $page);
				$this->session->set_userdata($array_page);						
			}
			
			echo json_encode($array_page);
		}
	}	

	function getallcomment($blogid,$limit=NULL,$offset=NULL,$status=NULL,$mediafilter=NULL,$contentfilter=NULL){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			$this->load->model('Comment_model');
			echo json_encode($this->Comment_model->get_all_comment($this->main->getUser('dom_id'),$limit,$offset,$status,$mediafilter,$contentfilter));	
		}
	}

	function approvecomment(){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
		
			$post = $this->input->post(NULL, TRUE);
			$this->load->model('Global_model');
			$this->load->library('coredb');

			/* check comment */
			$total_comment = $this->coredb->count_table_row(array('comment_post_ID' => $post['postid'], 'comment_approved' => 'terpasang', 'comment_blog_id' => $this->main->getUser('dom_id')),'kp_comments');			
			/* update post */
			$this->Global_model->update(array('ID' => $post['postid'], 'blog_id' => $this->main->getUser('dom_id')),array('comment_count' => $total_comment+1),'kp_posts');
			
			/* update comment */
			if($this->Global_model->update(array('comment_ID' => $post['commentid'], 'comment_blog_id' => $this->main->getUser('dom_id')),array('comment_approved' => 'terpasang'),'kp_comments')){
				echo json_encode(array('update' => TRUE));
			}
		}		
	}

	function unapprovecomment(){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
		
			$post = $this->input->post(NULL, TRUE);
			$this->load->model('Global_model');
			$this->load->library('coredb');

			/* check comment */
			$total_comment = $this->coredb->count_table_row(array('comment_post_ID' => $post['postid'], 'comment_approved' => 'terpasang', 'comment_blog_id' => $this->main->getUser('dom_id')),'kp_comments');			
			/* update post */
			$this->Global_model->update(array('ID' => $post['postid'], 'blog_id' => $this->main->getUser('dom_id')),array('comment_count' => $total_comment-1),'kp_posts');
			
			/* update comment */
			if($this->Global_model->update(array('comment_ID' => $post['commentid'], 'comment_blog_id' => $this->main->getUser('dom_id')),array('comment_approved' => 'tidak_terpasang'),'kp_comments')){
				echo json_encode(array('update' => TRUE));
			}
		}		
	}

	function massdelcomment(){
		/* check if superadmin domain is logged in */
		$this->main->is_logged_in();		
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		
			$this->load->model('Comment_model');
			$post = $this->input->post(NULL, FALSE);
			if(isset($post['comment_id'])){
				$wherein = $post['comment_id'];
			}
			else{
				$wherein = NULL;
			}
			$this->Comment_model->mass_del_comment( $wherein , $this->main->getUser('dom_id'));
		}
	}

	function updatecomment(){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
		
			$post = $this->input->post(NULL, TRUE);
			
			/* name_comment , email_comment , website_comment , content_comment , blog_id , comment_id */
			$this->load->model('Global_model');
			$this->load->library('coredb');

			/* comment update array */
			$updatecomment = array(
					'comment_author_name' => $post['name_comment'],
					'comment_author_email' => $post['email_comment'],
					'comment_author_url' => $post['website_comment'],
					'comment_content' => $post['content_comment']					
				);

			/* comment where array */
			$wherecomment = array('comment_ID' => $post['comment_id'], 'comment_blog_id' => $this->main->getUser('dom_id'));

			/* ketika update berhasil */
			if($this->Global_model->update($wherecomment,$updatecomment,'kp_comments')){
				echo json_encode(array('update' => TRUE));
			}
			
		}			
	}	
	
	function delcomment(){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
		
			$post = $this->input->post(NULL, TRUE);
			$this->load->model('Global_model');
			$this->load->library('coredb');
			
			/* delete comment */				
			if($this->Global_model->del(array('comment_ID' => $post['commentid']),'kp_comments')){
				/* check comment */
				$total_comment = $this->coredb->count_table_row(array('comment_post_ID' => $post['postid'], 'comment_approved' => 'terpasang', 'comment_blog_id' => $this->main->getUser('dom_id')),'kp_comments');			
				/* update post */
				$this->Global_model->update(array('ID' => $post['postid'], 'comment_blog_id' => $this->main->getUser('dom_id')),array('comment_count' => $total_comment),'kp_posts');					
				echo json_encode(array('delete' => TRUE));
			}
		}		
	}

	function mass_publish_comment(){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			
			$this->load->model('Comment_model');
			$post = $this->input->post(NULL, FALSE);
			
			if(isset($post['comment_id'])){
				$wherein = $post['comment_id'];
			}
			else{
				$wherein = NULL;
			}			
			
			if($this->Comment_model->mass_publish_comment( $wherein , $this->main->getUser('dom_id'), array('kp_comments.comment_approved' => 'terpasang'))){
				echo json_encode(array('update' => TRUE));
			}
		}				
	}

	function mass_pending_comment(){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			
			$this->load->model('Comment_model');
			$post = $this->input->post(NULL, FALSE);
			
			if(isset($post['comment_id'])){
				$wherein = $post['comment_id'];
			}
			else{
				$wherein = NULL;
			}			
			
			if($this->Comment_model->mass_publish_comment( $wherein , $this->main->getUser('dom_id'), array('kp_comments.comment_approved' => 'tidak_terpasang'))){
				echo json_encode(array('update' => TRUE));
			}
		}				
	}	



	/* *************************************** */
	/* ************ ALL MEDIA ATR ************ */
	/* *************************************** */	

	function getallcat($type,$blogid){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
					&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			
			$this->load->model('Post_model');
			$postcategory = $this->Post_model->get_all_cat($type,NULL,$this->main->getUser('dom_id'));
			
			/* jika domainnya ada */
			if(!empty($postcategory)){
				echo json_encode($postcategory);	
			}
		}	
	}

	/* dari sini yang berhubungan dengen halaman page.php dan page.js */		
	/* dari sini ini berpasangn dengan edit_article.php > template/back/default */
	/* ini digunakan semua hal yang berkaitan dengan fitur yang di akses oleh berbagai fitur */	
	
	function getmedia($width=NULL,$height=NULL){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$this->load->model('Global_model');
			$array_where_get = array(
										'post_type' => 'attachment', 
										'blog_id' => $this->main->getUser('dom_id'),
									);

			$data = $this->Global_model->select($array_where_get,'kp_posts','ID', 'DESC');
					
			foreach($data as $key => $val){
				
				foreach($val as $k => $v ){
					if($k == 'post_attribute'){
						$convert = unserialize(base64_decode($v));
						$convert['file_path'] ='';
						$convert['full_path'] ='';
						$media[$key][$k] = $convert;				
					}

					else if($k == 'guid'){
						$media[$key][$k] = array(
													'imgori' => $v,
													'img' => $this->main->resize_img($v,160,130,1)
												);
					}
					
					else{
						$media[$key][$k] = $v;	
					}					
					
				}
			}			

			echo json_encode($media);		
		}
	}
	
	function massresizemedia(){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			$post = $this->input->post();
			$disp = array();
			for($x=0;$x<count(($post['vrb'][0]));$x++){
				$disp[$x]['template_image'] = $this->main->resize_img($post['vrb'][0][$x]['template_image'],$post['width'],$post['height'],1);
				$disp[$x]['template_id'] = $post['vrb'][0][$x]['template_id'];
			}

			echo json_encode($disp);
		}
	}

	function resizemedia(){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){		
			$post = $this->input->post();
			$image = $post['image'];
			$width = $post['width'];
			$height = $post['height'];
			$type = $post['type'];
			echo json_encode(array('img' => $this->main->resize_img($image,$width,$height,$type)) );
		}
	}
	
	function saveimgatr(){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			$this->load->model('Global_model');
			$post = $this->input->post();
			//print_r(unserialize($post['attr']));
			$attr = unserialize(base64_decode($post['attr']));
			$id = $post['id'];
			$inputalt = $post['inputalt'];
			$textareadesc = $post['textareadesc'];
			$attr['alttext'] = $inputalt;
			$attr['description'] = $textareadesc;
			
			/* dari serialize kemudian di ambil lalu diserialize kan kembali */
			
			/* array for new article */
			$update_array = array(				
				'post_attribute' => base64_encode(serialize($attr)),	
			);				
			
			
			
			if($this->Global_model->update(array('blog_id' => $this->main->getUser('dom_id'), 'ID' => $id, 'post_author' => $this->main->getUser('user_id') ), $update_array,'kp_posts')){
											
				$warning['success'] = 'TRUE';
			}
			else{
				$warning['success'] = 'FALSE';
			}
		}
	
	}	

	/* ****************************************************************************** */
	/************************ INI DIGUNAKAN UNTUK KEPERLUAN RESELLER *****************/
	/* ****************************************************************************** */
	function getallarticlereseller($type,$status,$limit,$offset,$mediafilter=NULL,$contentfilter=NULL){
		/* check if superadmin domain is logged in */
		$this->main->is_logged_in();		
		$this->main->is_reseller();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			global $SConfig;
			$this->load->model('Post_model');
			echo json_encode($this->Post_model->get_all_article_reseller($type,$status,$SConfig->storeID,$limit,$offset,$mediafilter,$contentfilter));	
		}
	}

	function getallcat_except_reseller($type,$blogid,$slug=NULL){
		/* check if superadmin domain is logged in */
		global $SConfig;
		$this->main->is_logged_in();

		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			
			$post = $this->input->post(NULL, TRUE);

			if(!empty($slug)){
				$slug = array($slug);
			}
			else{
				$slug = NULL;
			}
			$this->load->model('Post_model');
			$this->load->model('Site_model');			
			

			$domainatr = $this->Site_model->getDomainAtr($this->main->getUser('dom_id'),$this->main->getUser('user_id'));		
			

			if(empty($domainatr['domain_reseller'])){
				$dom_id = $this->main->getUser('dom_id');
			}
			else{
				$dom_id = $SConfig->storeID;
			}

			$postcategory = $this->Post_model->get_all_cat($type,NULL,$dom_id,$slug);
			
			/* jika domainnya ada */
			if(!empty($postcategory)){
				echo json_encode($postcategory);	
			}
			
		}			
	}

	function paginginitreseller($type,$status,$blogid,$mediafilter=NULL,$contentfilter=NULL){
		global $SConfig;
		/* check if superadmin domain is logged in */
		$this->main->is_logged_in();

		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
					&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
		
			/* 	algoritma paging berapa banyak */
			$this->load->model('Global_model');			
			$this->load->model('Site_model');			
			

			$domainatr = $this->Site_model->getDomainAtr($this->main->getUser('dom_id'),$this->main->getUser('user_id'));		
			
			if(empty($domainatr['domain_reseller'])){
				$dom_id = $this->main->getUser('dom_id');
			}
			else{
				$dom_id = $SConfig->storeID;
			}

			$where_array = array(
									'post_type' => $type,
									'blog_id' => $dom_id,
								);		
			
			/* ketika status tidak ada */
			if($status != '-'){
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

	function addproductbyreseller(){
		global $SConfig;
		/* check if superadmin domain is logged in */
		$this->main->is_logged_in();

		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
					&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){			

			$this->load->model('Site_model');

			$domainatr = $this->Site_model->getDomainAtr($this->main->getUser('dom_id'),$this->main->getUser('user_id'));		
			
			if(empty($domainatr['domain_reseller'])){
				echo json_encode(array('success' => 'FALSE'));	
			}
			else{
				$this->load->model('Post_model');
				$this->load->model('Global_model');
				$this->load->helper('inflector');
				$post = $this->input->post();
				$prodid = $post['prodid'];
				$post_detail = $this->Global_model->select_single(array('ID' => $prodid, 'blog_id' => $SConfig->storeID), 'kp_posts');			

				/* add category from store.kaffahbiz */
				$post_category = explode(',',$post_detail['post_category']);

				if(count($post_category) > 1){
					/* array untuk request json category initialize */
					$array_cat_add = array( 
							'name' => humanize($post_category[0]),
							'slug' => $post_category[0],											
							'term_type' => 'category_product',
							'term_user_id' => $this->main->getUser('user_id'),
							'term_blog_id' => $this->main->getUser('dom_id')
						);	
				}
				else{
					/* array untuk request json category initialize */
					$array_cat_add = array( 
							'name' => humanize($post_detail['post_category']),
							'slug' => $post_detail['post_category'],											
							'term_type' => 'category_product',
							'term_user_id' => $this->main->getUser('user_id'),
							'term_blog_id' => $this->main->getUser('dom_id')
						);	

				}

				/* encode menjadi json */
				$iscatExist = $this->Post_model->is_cat_exist('category_product', $array_cat_add['slug'], $this->main->getUser('dom_id'));
				
				/* ketika kategorinya itu ada */
				if($iscatExist == FALSE){
					$this->Global_model->addNew($array_cat_add, 'kp_terms');					
				}


				if($this->Post_model->articleparent_is_exist($prodid, $this->main->getUser('dom_id'))){
					echo json_encode(array('success' => 'FALSE'));
				}
				else{				
					$post_detail['ID'] = NULL;
					$post_detail['post_parent'] = $prodid;	
					$post_detail['blog_id'] = $this->main->getUser('dom_id');
					$post_detail['post_author'] = $this->main->getUser('user_id');
					$post_detail['post_reseller'] = 0;
					$post_detail['post_basic'] = 0;
					$post_detail['post_reseller_fee'] = 0;
					$post_detail['post_ks_fee'] = 0;

					if(@$this->Global_model->addNew($post_detail,'kp_posts')){
						echo json_encode(array('success' => 'TRUE'));	
					}
					else{
						echo json_encode(array('success' => 'FALSE'));	
					}							
				}				
			}





			/* add product from store.kaffahbiz */
			
		}
	}

	function getallproductparent($blogid){
		/* check if superadmin domain is logged in */
		$this->main->is_logged_in();		
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			$this->load->model('Post_model');
			echo json_encode($this->Post_model->get_all_product_parent($this->main->getUser('dom_id')));	
		}
	}

	function getorder_byreseller($blogid,$limit=NULL,$offset=NULL,$mediafilter=NULL,$contentfilter=NULL){
		global $SConfig;
		$this->main->is_logged_in();
		if($this->main->getUser('dom_id') == $SConfig->storeID){
			if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
				$this->load->model('Post_model');
				$allorder = $this->Post_model->getallorder_byreseller($this->main->getUser('dom_id'),$limit,$offset,$mediafilter,$contentfilter);
				echo json_encode($allorder);
			}
		}	
	}	

	function getorder_byreseller_commision($blogid,$limit=NULL,$offset=NULL,$mediafilter=NULL,$contentfilter=NULL){
		global $SConfig;
		$this->main->is_logged_in();
		if($this->main->getUser('dom_id') == $SConfig->storeID){
			if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
				$this->load->model('Post_model');
				$allorder = $this->Post_model->getallorder_byreseller_commision($this->main->getUser('dom_id'),$limit,$offset,$mediafilter,$contentfilter);
				echo json_encode($allorder);
			}
		}	
	}

	function getdetailtransaction_byreseller($blogid=NULL){
		global $SConfig;
		$this->main->is_logged_in();
		if($this->main->getUser('dom_id') == $SConfig->storeID){
			if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
				$this->load->model('Post_model');
				$post = $this->input->post();	
				$alldetailorder = $this->Post_model->gettransactiondetail_byreseller($this->main->getUser('dom_id'),$post['transaction_id']);	

				$detail = array();

				foreach($alldetailorder as $key => $val){
					foreach($val as $k => $v){
						if($k == 'option'){
							$detail[$key][$k] = unserialize(base64_decode($v));
						}
						else{
							$detail[$key][$k] = $v;	
						}
						
					}
				}			

				echo json_encode($detail);
			}	
		}	
	}

	function updatetransaction_byreseller($blogid=null){
		global $SConfig;
		$this->main->is_logged_in();
		if($this->main->getUser('dom_id') == $SConfig->storeID){
			if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
				$this->load->model('Global_model');
				$this->load->helper('url');
				$post = $this->input->post();
				
				$array_update_transaction = array(
						'transaction_status' => $post['status'],
						'all_total' => preg_replace("/[., ]/", "", $post['all_total']),
						'transfer_destination' => $post['transfer_destination'],
						'tracking_number' => $post['tracking_number'],
						'transaction_temp_status' => '',
					);

				$array_update_shipping = array(
						'nama_lengkap' => $post['nama_lengkap'],
						'email' => $post['email'],
						'no_handphone' => $post['no_handphone'],
						'kota' => url_title($post['kota'], '_'),
						'provinsi' => url_title($post['provinsi'], '_'),
						'alamat' => $post['alamat'],
						'no_telepon' => $post['no_telepon']

					);

				$array_where = array('blog_id' => $this->main->getUser('dom_id'), 'transaction_parent' => $post['transaction_id']);
				$array_where_reseller = array('transaction_id' => $post['transaction_id']);

				if($this->Global_model->update($array_where,$array_update_transaction,'kp_transaction') && 
					$this->Global_model->update($array_where,$array_update_shipping,'kp_shipping') && 
					$this->Global_model->update($array_where_reseller,$array_update_transaction,'kp_transaction') && 
					$this->Global_model->update($array_where_reseller,$array_update_shipping,'kp_shipping')
					){


					if($post['status'] == 'sudah_transfer'){

						/* sebelah sini digunakan untuk mengupdate semua komisi berdasarkan produknya */
						/* ambil product id */
						$getproductid = $this->Global_model->select(array('transaction_parent' => $post['transaction_id']), 'kp_trans_detail', 'transaction_parent', 'DESC');
						foreach($getproductid as $row){
							$prodidreseller[] = $row['product_id'];
						}

						/* ambil parentnya */
						$getparentid = $this->Global_model->selectwherein($prodidreseller,'kp_posts');
						foreach($getparentid as $row){
							$parentid[$row['post_parent']]['post_parent'] = $row['post_parent'];
							$parentid[$row['post_parent']]['ID'] = $row['ID'];
							$parent[] = $row['post_parent'];
						}

						/* ambil detil komisi */
						$getparentdetail = $this->Global_model->selectwherein($parent,'kp_posts');

						/* langsung update */
						foreach($getparentdetail as $row){
							$this->Global_model->update(
								array('transaction_parent' => $post['transaction_id'], 'product_id' => $parentid[$row['ID']]['ID']),
								array('commision_status' => 'belum_dibayar', 'commision_reseller' => $row['post_reseller_fee'], 'commision_ks' => $row['post_ks_fee']),
								'kp_trans_detail');
						}

					}
					else{
						/* sebelah sini digunakan untuk mengupdate semua komisi berdasarkan produknya */
						/* ambil product id */
						$getproductid = $this->Global_model->select(array('transaction_parent' => $post['transaction_id']), 'kp_trans_detail', 'transaction_parent', 'DESC');
						foreach($getproductid as $row){
							$prodidreseller[] = $row['product_id'];
						}

						/* ambil parentnya */
						$getparentid = $this->Global_model->selectwherein($prodidreseller,'kp_posts');
						foreach($getparentid as $row){
							$parentid[$row['post_parent']]['post_parent'] = $row['post_parent'];
							$parentid[$row['post_parent']]['ID'] = $row['ID'];
							$parent[] = $row['post_parent'];
						}

						/* ambil detil komisi */
						$getparentdetail = $this->Global_model->selectwherein($parent,'kp_posts');

						/* langsung update */
						foreach($getparentdetail as $row){
							$this->Global_model->update(
								array('transaction_parent' => $post['transaction_id'], 'product_id' => $parentid[$row['ID']]['ID']),
								array('commision_status' => '', 'commision_reseller' => 0, 'commision_ks' => 0),
								'kp_trans_detail');
						}						
					}
					
					/* update komisi dari detil product id */

					echo json_encode(array('status'=>TRUE));
				}
			}
		}			
	}

	function updatecommision_byreseller($blogid=null){
		global $SConfig;
		$this->main->is_logged_in();
		if($this->main->getUser('dom_id') == $SConfig->storeID){
			if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
				$this->load->model('Global_model');
				$post = $this->input->post();

				if($this->Global_model->update(array('product_id' => $post['product_id'], 'transaction_parent' => $post['transaction_id']),array('commision_status' => $post['status_komisi']),'kp_trans_detail')){
					echo json_encode(array('status'=>TRUE));	
				}

				
			}
		}
	}			
	
	function orderpaginginit_commision($blogid,$mediafilter=NULL,$contentfilter=NULL){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
		
			/* 	algoritma paging berapa banyak */
			$this->load->model('Global_model');

			$where_array = array( 'kp_trans_detail.blog_id' => $this->main->getUser('dom_id'), 'kp_trans_detail.transaction_parent >' => 0 );

			/* konfigurasi untuk paging */
			$total_rows = $this->Global_model->numrows($where_array,'kp_trans_detail',$mediafilter,$contentfilter);			
			
			$per_page = getconfig('limit');
			$total_page = ceil($total_rows / getconfig('limit')) ;			
			$array_json = array(	
								'banyak_baris' => $total_rows ,
								'per_page'	=>	$per_page,
								'total_page' => $total_page
								);
			
			/* tampilkan dalam json */							
			// echo $total_rows;
			echo json_encode($array_json);			

		}
	}

	function getrekeninguser($userid){
		global $SConfig;
		$this->main->is_logged_in();
		if($this->main->getUser('dom_id') == $SConfig->storeID){
			if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
				$this->load->model('Global_model');
				$user_detail = $this->Global_model->select_single(array('user_id' => $userid), 'kp_user_detail');
				echo json_encode($user_detail);			
			}
		}		
	}

	function delete_transaction_byreseller($blogid=null){
		$this->main->is_logged_in();

		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){		
			$this->load->model('Global_model');
			$this->load->helper('url');
			$post = $this->input->post();
			
			$array_where = array('transaction_parent' => $post['transaction_id']);	
			$tables = array('kp_shipping', 'kp_transaction', 'kp_trans_detail');
			if($this->Global_model->del($array_where,$tables)){
				echo json_encode(array('status'=>TRUE));
			}	
		}	
	}	

	function getorder_byself_commision($blogid,$limit=NULL,$offset=NULL,$mediafilter=NULL,$contentfilter=NULL){
		global $SConfig;

		$this->main->is_logged_in();
		$this->load->model('Site_model');
		$domainatr = $this->Site_model->getDomainAtr($this->main->getUser('dom_id'),$this->main->getUser('user_id'));		
		
		if(!empty($domainatr['domain_reseller'])){
			if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {				
				$this->load->model('Post_model');
				$allorder = $this->Post_model->getallorder_byself_commision($this->main->getUser('dom_id'),$limit,$offset,$mediafilter,$contentfilter);
				echo json_encode($allorder);
			}
		}		
	}

	function orderpaginginit_commision_byself($blogid,$mediafilter=NULL,$contentfilter=NULL){
		$this->main->is_logged_in();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
		
			/* 	algoritma paging berapa banyak */
			$this->load->model('Global_model');

			$where_array = array( 'kp_transaction.blog_id_parent' => $this->main->getUser('dom_id'), 'kp_trans_detail.transaction_parent >' => 0 );

			/* konfigurasi untuk paging */
			$total_rows = $this->Global_model->numrows($where_array,'kp_trans_detail',$mediafilter,$contentfilter, TRUE);			
			
			$per_page = getconfig('limit');
			$total_page = ceil($total_rows / getconfig('limit')) ;			
			$array_json = array(	
								'banyak_baris' => $total_rows ,
								'per_page'	=>	$per_page,
								'total_page' => $total_page
								);
			
			/* tampilkan dalam json */							
			// echo $total_rows;
			echo json_encode($array_json);			

		}
	}

	function getdetailtransaction_byself($blogid=NULL){
		global $SConfig;
		$this->main->is_logged_in();
		$this->load->model('Site_model');
		$domainatr = $this->Site_model->getDomainAtr($this->main->getUser('dom_id'),$this->main->getUser('user_id'));		
		
		if(!empty($domainatr['domain_reseller'])){
			if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
				$this->load->model('Post_model');
				$post = $this->input->post();	
				$alldetailorder = $this->Post_model->gettransactiondetail_byself($this->main->getUser('dom_id'),$post['transaction_id']);	

				$detail = array();

				foreach($alldetailorder as $key => $val){
					foreach($val as $k => $v){
						if($k == 'option'){
							$detail[$key][$k] = unserialize(base64_decode($v));
						}
						else{
							$detail[$key][$k] = $v;	
						}
						
					}
				}			

				echo json_encode($detail);
			}	
		}	
	}	
}

/* End of file reqpost.php */
/* Location: ./application/controllers/reqpost.php */
