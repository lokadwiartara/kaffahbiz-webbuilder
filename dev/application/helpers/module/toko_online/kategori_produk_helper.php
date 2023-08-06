<?php
	function kategori_produk_backend($type=NULL,$post=NULL,$position=NULL,$modulekey=NULL,$blogid=NULL){
			$display = NULL;
			$_this =& get_instance();		
	
			if($type=='front'){
				$display = '';
			}

			else if($type=='desc'){
				$display = '<div class="modulbox">
							<h3>Kategori Produk</h3>
							<img src="'.get_asset('images').'/cart.png" />
							<p>Modul ini digunakan sebagai navigasi untuk pengunjung yang ingin melihat kategori produk di website Anda</p>			
							<a href="#" id="kategori_produk_backend">+ Tambah Modul Ini</a>
							</div>';
			}								
	
			else if($type=='edit'){
				if(@$post['atribut']['tipemenu'] == "dropdown"){
					$atrdrop = 'checked';
					$atrtext = '';
				}
				else{
					$atrdrop = '';
					$atrtext = 'checked';
				}

				$display .= form_open(base_url().'admin/tampilan/update_modul_pilihan', 'name="module_form" class="update" id="module_form"');
				$display .= '<h3>Kategori Produk</h3>'."\n";
				$display .= '<div class="ymform">'."\n";				
				$display .= '<label class="forframe" for="judul">Judul Daftar Kategori</label><input value="'.$post['judul'].'" id="judul" name="judul" class="input_text" type="text">'."\n";
				$display .= '<p class="helpernote">(Silahkan masukkan Judul Daftar Kategori-nya, misalnya : Kategori Produk )</p>'."\n";
				$display .= '<label class="forframe">Tipe Menu Kategori</label><input type="radio" id="tipemenu1" name="tipemenu" value="dropdown" class="input_text" '.$atrdrop.' ><label for="tipemenu1" class="labelcheck">Aktifkan menu dropdown</label>
							<input type="radio" id="tipemenu2" name="tipemenu" class="input_text" value="text" '.$atrtext.' ><label for="tipemenu2" class="labelcheck twice">Aktifkan menu teks</label>
							<br />'."\n";
				$display .= '</div>'."\n";
				$display .= '<input type="hidden" value="kategori_produk" id="kategori_produk_backend" name="modulename" class="framesubmit btnAct"  />'."\n";
				$display .= '<input type="hidden" value="'.$position.'" id="position" name="position" />'."\n";
				$display .= '<input type="hidden" value="'.$blogid.'" id="blogid" name="blogid" />'."\n";
				$display .= '<input type="hidden" value="'.$modulekey.'" id="key" name="modulekey" />'."\n";
				$display .= '<input type="submit" value="Simpan!" id="simpan_modul" class="submit"  />'."\n";
				$display .= form_close();
			}																																
											
			else if($type=='insert'){
				$display .= form_open(base_url().'admin/tampilan/tambah_modul_pilihan', 'class="insert" name="module_form" id="module_form"');
				$display .= '<h3>Kategori Produk</h3>'."\n";
				$display .= '<div class="ymform">'."\n";
				$display .= '<label class="forframe" for="judul">Judul Daftar</label><input id="judul" name="judul" class="input_text" type="text">'."\n";
				$display .= '<p class="helpernote">(Silahkan masukkan Judul Daftar Kategori-nya, misalnya : Kategori Produk )</p>'."\n";
				$display .= '<label class="forframe">Tipe Menu Kategori</label><input type="radio" id="tipemenu1" name="tipemenu" class="input_text" value="dropdown" ><label for="tipemenu1" class="labelcheck">Aktifkan menu dropdown</label>
							<input type="radio" id="tipemenu2" name="tipemenu" class="input_text" checked value="text" ><label for="tipemenu2" class="labelcheck twice">Aktifkan menu teks</label>
							<br />'."\n";
				$display .= '</div>'."\n";
				$display .= '<input type="hidden" value="kategori_produk" id="kategori_produk_backend" name="modulename" class="framesubmit btnAct"  />'."\n";
				$display .= '<input type="hidden" value="" id="position" name="position" />'."\n";
				$display .= '<input type="hidden" value="" id="blogid" name="blogid" />'."\n";
				$display .= '<input type="submit" value="Simpan!" id="simpan_modul" class="submit"  />'."\n";
				$display .= form_close();
			}																						
			
		else if($type=='updatedb'){
			/* load model global */
			$_this->load->model('Global_model');
			$_this->load->model('Site_model');
			
			/* is exist module */
			$isExistModuleSetting = $_this->Site_model->is_modulexist(
						array(
							'option_name' => 'module_setting',
							'blog_id' => $blogid
							), 
						'kp_options');
						
			$listmodule = unserialize(base64_decode($isExistModuleSetting['option_value']));
			
			$listmodule[$position][$modulekey] = array(
													'sort' => $listmodule[$position][$modulekey]['sort'],
													'module' => 'kategori_produk',
													'judul' => $post['judul'],
													'atribut' => array(
																		'tipemenu' => $post['tipemenu']
																	)													
												);		
			
			$where_update_module = 	array(
										'option_name' => 'module_setting',
										'blog_id' => $blogid
									);						
										
			$_this->Global_model->update($where_update_module, 
									array('option_value' => base64_encode(serialize($listmodule))),
									'kp_options');		

			
			$array_kategori_produk = array(
										'status' => TRUE,
									);
									
			$display = json_encode($array_kategori_produk);
					
		}
		
		else if($type=='inserttodb'){
			/* load model global */
			$_this->load->model('Global_model');
			$_this->load->model('Site_model');					

			/* is exist module */
			$isExistModuleSetting = $_this->Site_model->is_modulexist(
						array(
							'option_name' => 'module_setting',
							'blog_id' => $blogid
							), 
						'kp_options');

			
			$pattern = "#\s*?kategori_produk*#s";
			$grabmodule = preg_match_all($pattern, base64_decode($isExistModuleSetting['option_value']), $result);
			$countmodule = count($result[0]);		
			$newmoduleexist = ($countmodule / 2) + 1;

			if($countmodule > 0) { 
				$kategori_produk_r = 'kategori_produk_'.$newmoduleexist; 
				$kategori_produk_R = 'Kategori produk '.$newmoduleexist; 
			} 
			else {
				$kategori_produk_r = 'kategori_produk' ;
				$kategori_produk_R = 'Kategori produk';
			}
			

						
			/* jika dalam kp_options ada module setting */
			if($isExistModuleSetting != NULL){
				
				
				$old_kategori_produk_array = unserialize(base64_decode($isExistModuleSetting['option_value']));

				$countrowmodule = count(@$old_kategori_produk_array[$position]);
				
				/* is exist module */	
				$kategori_produk_arraytodb = array(
											$position => array(
												$kategori_produk_r	=> array(
													'sort' => $countrowmodule,
													'module' => 'kategori_produk',
													'judul' => $post['judul'],
													'atribut' => array(
																		'tipemenu' => $post['tipemenu']
																	)														
													)
												)
											);		
				
				
				if(is_array($old_kategori_produk_array)){
					$modulefinish = array_merge_recursive($old_kategori_produk_array,$kategori_produk_arraytodb);
					
				}
				
				else{
					$modulefinish = $kategori_produk_arraytodb;
				}
			
				
				$where_update_module = 	array(
											'option_name' => 'module_setting',
											'blog_id' => $blogid
										);						
											
				$_this->Global_model->update($where_update_module, 
										array('option_value' => base64_encode(serialize($modulefinish))),
										'kp_options');		
			}
			
			/* jika module setting di kp_options tidak ada maka */
			else{

				$old_kategori_produk_array = unserialize(base64_decode($isExistModuleSetting['option_value']));

				$countrowmodule = count($old_kategori_produk_array[$position]);

				/* is exist module */	
				$kategori_produk_arraytodb = array(
											$position => array(
												$kategori_produk_r	=> array(
													'sort' => $countrowmodule ,
													'module' => 'kategori_produk',
													'judul' => $post['judul'],
													'atribut' => array(
																		'tipemenu' => $post['tipemenu']
																	)														
													)
												)
											);		
				
				
				$modulefinish = $kategori_produk_arraytodb;
				
				$insert_module = 	array(
											'option_name' => 'module_setting',
											'blog_id' => $blogid,
											'option_value' => base64_encode(serialize($modulefinish))
										);						
				
				$_this->Global_model->addNew($insert_module, 'kp_options');
											
			}
			
			/* array for view */
			$array_kategori_produk = array(
										'status' => TRUE,
										'front' => $kategori_produk_R,
										'frontname' => $kategori_produk_r,
										'module' => 'kategori_produk'
									);
			
			/* json post */
			$display =  json_encode($array_kategori_produk);
		}																				
	
		return $display;
	}
			
	function kategori_produk($content=NULL){
		$_this =& get_instance();		
		$display = NULL;
		$domainatr = $_this->main->allAtr;
		
		$display = '<div class="module widget catlist">';
		
		if(!empty($content['judul'])){
			$display .= '<h3 class="module">'.$content['judul'].'</h3>';			
		}

		$_this->load->model('Post_model');
		$postcategory = $_this->Post_model->get_all_cat('category_product',NULL,$domainatr->domain_id);		
		
		if($content['atribut']['tipemenu'] == 'dropdown'){
			$display .= '<select name="menu" class="dropddown_module" id="dropddown_module" >';
			$display .= '<option value="/">Pilih Kategori</option>';
			foreach($postcategory as $row){
				if($row['parent'] == 0){
				 	$parenting = $row['term_id'];
					$topofthem = 0;
					$separate = '';
				 }
				 else{
				 	if($topofthem == 0 ){
						$parenting = $row['parent'];
						if($parenting == $row['parent']){
							$separate = '--';
							$topofthem = $row['term_id']; 
						}
					}
					
					else if($row['parent'] == $topofthem){
						$separate = $separate . '--' ;
						$topofthem = $row['term_id']; 
					}
					
					else{
						$separate = '--' ;
						$topofthem = $row['term_id']; 
					}	
				 }

				 $display .= '<option value="/produk/'.$row['slug'].'">'. $separate . $row['name'] .'</option>';

			}
			$display .='</select>';
		}
		else{
			$postcategory = reListCat($postcategory);			

			

			$display .= menuCategory($postcategory,'product',TRUE);	
		}
		
		
		

		$display .= '</div>';
		return $display;
	}													
?>