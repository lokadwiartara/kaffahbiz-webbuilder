<?php
	function media_pembayaran_backend($type=NULL,$post=NULL,$position=NULL,$modulekey=NULL,$blogid=NULL){
		$display = NULL;
		$_this =& get_instance();		

		if($type=='front'){			
			$display = '';
		}

		else if($type=='desc'){
			$display = '<div class="modulbox">
						<h3>Media Pembayaran</h3>
						<img src="'.get_asset('images').'/card.png" />
						<p>Digunakan untuk menampilkan media pembayaran seperti Rekening Bank</p>			
						<a href="#" id="media_pembayaran_backend">+ Tambah Modul Ini</a>
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
			$display .= '<h3>Media Pembayaran</h3>'."\n";
			$display .= '<div class="ymform">'."\n";				
			$display .= '<label class="forframe" for="judul">Judul Daftar Kategori</label><input value="'.$post['judul'].'" id="judul" name="judul" class="input_text" type="text">'."\n";
			$display .= '<p class="helpernote">(Silahkan masukkan Judul Daftar Kategori-nya, misalnya : Media Pembayaran )</p>'."\n";			
			$display .= '<label class="forframe" for="deskripsi">Deskripsi</label><textarea id="deskripsi" name="deskripsi" class="desc">'.@$post['deskripsi'].'</textarea>'."\n";
			$display .= '<p class="helpernote">(Anda bisa memasukkan deskripsinya disini)</p>'."\n";
			$display .= '</div>'."\n";
			$display .= '<input type="hidden" value="media_pembayaran" id="media_pembayaran_backend" name="modulename" class="framesubmit btnAct"  />'."\n";
			$display .= '<input type="hidden" value="'.$position.'" id="position" name="position" />'."\n";
			$display .= '<input type="hidden" value="'.$blogid.'" id="blogid" name="blogid" />'."\n";
			$display .= '<input type="hidden" value="'.$modulekey.'" id="key" name="modulekey" />'."\n";
			$display .= '<input type="submit" value="Simpan!" id="simpan_modul" class="submit"  />'."\n";
			$display .= form_close();
		}																																
										
		else if($type=='insert'){
			$display .= form_open(base_url().'admin/tampilan/tambah_modul_pilihan', 'class="insert" name="module_form" id="module_form"');
			$display .= '<h3>Media Pembayaran</h3>'."\n";
			$display .= '<div class="ymform">'."\n";
			$display .= '<label class="forframe" for="judul">Judul Media Pembayaran</label><input id="judul" name="judul" class="input_text" type="text">'."\n";
			$display .= '<p class="helpernote">(Masukkan Judul-nya, ex: Media Pembayaran ), konten di ambil dari konfigurasi/setting Online Store.</p>'."\n";
			$display .= '<label class="forframe" for="deskripsi">Deskripsi</label><textarea id="deskripsi" name="deskripsi" class="desc"></textarea>'."\n";
			$display .= '<p class="helpernote">(Anda bisa memasukkan deskripsinya disini)</p>'."\n";
			$display .= '</div>'."\n";
			$display .= '<input type="hidden" value="media_pembayaran" id="media_pembayaran_backend" name="modulename" class="framesubmit btnAct"  />'."\n";
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
													'module' => 'media_pembayaran',
													'judul' => $post['judul'],
													'deskripsi' => $post['deskripsi'],												
												);		
			
			$where_update_module = 	array(
										'option_name' => 'module_setting',
										'blog_id' => $blogid
									);						
										
			$_this->Global_model->update($where_update_module, 
									array('option_value' => base64_encode(serialize($listmodule))),
									'kp_options');		

			
			$array_media_pembayaran = array(
										'status' => TRUE,
									);
									
			$display = json_encode($array_media_pembayaran);
					
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

			
			$pattern = "#\s*?media_pembayaran*#s";
			$grabmodule = preg_match_all($pattern, base64_decode($isExistModuleSetting['option_value']), $result);
			$countmodule = count($result[0]);		
			$newmoduleexist = ($countmodule / 2) + 1;

			if($countmodule > 0) { 
				$media_pembayaran_r = 'media_pembayaran_'.$newmoduleexist; 
				$media_pembayaran_R = 'Media Pembayaran '.$newmoduleexist; 
			} 
			else {
				$media_pembayaran_r = 'media_pembayaran' ;
				$media_pembayaran_R = 'Media Pembayaran';
			}
			

						
			/* jika dalam kp_options ada module setting */
			if($isExistModuleSetting != NULL){
				
				
				$old_media_pembayaran_array = unserialize(base64_decode($isExistModuleSetting['option_value']));

				$countrowmodule = count(@$old_media_pembayaran_array[$position]);
				
				/* is exist module */	
				$media_pembayaran_arraytodb = array(
											$position => array(
												$media_pembayaran_r	=> array(
													'sort' => $countrowmodule,
													'module' => 'media_pembayaran',
													'judul' => $post['judul'],
													'deskripsi' => $post['deskripsi'],													
													)
												)
											);		
				
				
				if(is_array($old_media_pembayaran_array)){
					$modulefinish = array_merge_recursive($old_media_pembayaran_array,$media_pembayaran_arraytodb);
					
				}
				
				else{
					$modulefinish = $media_pembayaran_arraytodb;
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

				$old_media_pembayaran_array = unserialize(base64_decode($isExistModuleSetting['option_value']));

				$countrowmodule = count($old_media_pembayaran_array[$position]);

				/* is exist module */	
				$media_pembayaran_arraytodb = array(
											$position => array(
												$media_pembayaran_r	=> array(
													'sort' => $countrowmodule ,
													'module' => 'media_pembayaran',
													'judul' => $post['judul'],
													'deskripsi' => $post['deskripsi'],													
													)
												)
											);		
				
				
				$modulefinish = $media_pembayaran_arraytodb;
				
				$insert_module = 	array(
											'option_name' => 'module_setting',
											'blog_id' => $blogid,
											'option_value' => base64_encode(serialize($modulefinish))
										);						
				
				$_this->Global_model->addNew($insert_module, 'kp_options');
											
			}
			
			/* array for view */
			$array_media_pembayaran = array(
										'status' => TRUE,
										'front' => $media_pembayaran_R,
										'frontname' => $media_pembayaran_r,
										'module' => 'media_pembayaran'
									);
			
			/* json post */
			$display =  json_encode($array_media_pembayaran);
		}																				
	
		return $display;
	}
			
	function media_pembayaran($content=NULL){
		$_this =& get_instance();		
		$display = NULL;
		$domainatr = $_this->main->allAtr;
		
		$display = '<div class="module widget catlist payment">';
		
		if(!empty($content['judul'])){
			$display .= '<h3 class="module">'.$content['judul'].'</h3>';					
		}

		$display .= '<p class="modulehead">'.$content['deskripsi'].'</hp>';	

		$templatesetting = $_this->template->templatesetting;
		// print_r($templatesetting['store_setting']['jenis_rekening']);
		// print_r($templatesetting['store_setting']['no_rek']);
		// print_r($templatesetting['store_setting']['atasnama']);		

		$rektype = $templatesetting['store_setting']['jenis_rekening'];
		$norek = $templatesetting['store_setting']['no_rek'];
		$atasnama = $templatesetting['store_setting']['atasnama'];

		if(count($rektype) > 0){
			$display .= '<ul id="media">';
			for($x=0;$x<count($rektype);$x++){
				$image = 'http://www.kaffah.biz/assets/images/bank/';
				switch($rektype[$x]){
					case 'Mandiri' : $image .= 'mandiri.png'; break;
					case 'BCA' : $image .= 'bca.png'; break;
					case 'BNI_Syariah' : $image .= 'bnisyariah.png'; break;
					case 'Mandiri_syariah' : $image .= 'mandirisyariah.png'; break;
					case 'BNI' : $image .= 'bni.png'; break;
					case 'BRI' : $image .= 'bri.png'; break;
					default:break;
				}

				$display .= '<li><img src="'.$image.'" />';
				$display .= '<em class="atas_nama">'.$atasnama[$x].'</em><br />';
				$display .= '<em class="no_rek">'.$norek[$x].'</em>';				
				$display .= '</li>';
			}
			$display .= '</ul>';
		}


		$display .= '</div>';
		return $display;
	}													
?>