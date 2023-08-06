<?php
	function custom_text_backend($type=NULL,$post=NULL,$position=NULL,$modulekey=NULL,$blogid=NULL){
		$display = NULL;
		$_this =& get_instance();		
		
		if($type=='front'){
			$display = '';
		}
		
		else if($type=='desc'){
			$display = '<div class="modulbox">
						<h3>Custom Text</h3>
						<img src="'.get_asset('images').'custom_text.png" />
						<p>Modul ini digunakan untuk kostum teks, Anda bisa menggunakan tag HTML sesuai dengan kebutuhkan</p>
						<a href="#" id="custom_text_backend">+ Tambah Modul Ini</a>
						</div>';
		}								

		else if($type=='edit'){
			$display .= form_open(base_url().'admin/tampilan/update_modul_pilihan', 'name="module_form" class="update" id="module_form"');
			$display .= '<h3>Custom Text</h3>'."\n";
			$display .= '<div class="custom_text_helper">'."\n";
			$display .= '<label class="forframe" for="judul">Judul Custom Text</label><input id="judul" name="judul" class="input_text" type="text" value="'.$post['judul'].'">'."\n";
			$display .= '<p class="helpernote">(Silahkan masukkan Judul Custom Text-nya, misalnya : Facebook FansBox )</p>'."\n";
			$display .= '<label class="forframe" for="deskripsi">Custom Text</label><textarea id="deskripsi" name="deskripsi" class="desc">'.$post['deskripsi'].'</textarea>'."\n";
			$display .= '<p class="helpernote">(Anda bisa memasukkan kode html maupun javascript di sini)</p>'."\n";
			$display .= '</div>'."\n";
			$display .= '<input type="hidden" value="custom_text" id="custom_text_backend" name="modulename" class="framesubmit btnAct"  />'."\n";
			$display .= '<input type="hidden" value="'.$position.'" id="position" name="position" />'."\n";
			$display .= '<input type="hidden" value="'.$blogid.'" id="blogid" name="blogid" />'."\n";
			$display .= '<input type="hidden" value="'.$modulekey.'" id="key" name="modulekey" />'."\n";
			$display .= '<input type="submit" value="Simpan Custom Text!" id="simpan_modul" class="submit"  />'."\n";
			$display .= form_close();
		}																																
										
		else if($type=='insert'){
			$display .= form_open(base_url().'admin/tampilan/tambah_modul_pilihan', 'class="insert" name="module_form" id="module_form"');
			$display .= '<h3>Custom Text</h3>'."\n";
			$display .= '<div class="custom_text_helper">'."\n";
			$display .= '<label class="forframe" for="judul">Judul Custom Text</label><input id="judul" name="judul" class="input_text" type="text">'."\n";
			$display .= '<p class="helpernote">(Silahkan masukkan Judul Custom Text-nya, misalnya : Facebook FansBox )</p>'."\n";
			$display .= '<label class="forframe" for="deskripsi">Custom Text</label><textarea id="deskripsi" name="deskripsi" class="desc"></textarea>'."\n";
			$display .= '<p class="helpernote">(Anda bisa memasukkan kode html maupun javascript di sini)</p>'."\n";
			$display .= '</div>'."\n";
			$display .= '<input type="hidden" value="custom_text" id="custom_text_backend" name="modulename" class="framesubmit btnAct"  />'."\n";
			$display .= '<input type="hidden" value="" id="position" name="position" />'."\n";
			$display .= '<input type="hidden" value="" id="blogid" name="blogid" />'."\n";
			$display .= '<input type="submit" value="Tambahkan Custom Text!" id="simpan_modul" class="submit"  />'."\n";
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
													'module' => 'custom_text',
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

			
			$array_custom_text = array(
										'status' => TRUE,
									);
									
			$display = json_encode($array_custom_text);
					
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

			
			$pattern = "#\s*?custom_text*#s";
			$grabmodule = preg_match_all($pattern, base64_decode($isExistModuleSetting['option_value']), $result);
			$countmodule = count($result[0]);		
			$newmoduleexist = ($countmodule / 2) + 1;

			if($countmodule > 0) { 
				$custom_text_r = 'custom_text_'.$newmoduleexist; 
				$custom_text_R = 'Custom Text '.$newmoduleexist; 
			} 
			else {
				$custom_text_r = 'custom_text' ;
				$custom_text_R = 'Custom Text';
			}
			

						
			/* jika dalam kp_options ada module setting */
			if($isExistModuleSetting != NULL){
				
				
				$old_custom_text_array = unserialize(base64_decode($isExistModuleSetting['option_value']));

				$countrowmodule = count(@$old_custom_text_array[$position]);
				
				/* is exist module */	
				$custom_text_arraytodb = array(
											$position => array(
												$custom_text_r	=> array(
													'sort' => $countrowmodule,
													'module' => 'custom_text',
													'judul' => $post['judul'],
													'deskripsi' => $post['deskripsi'],
													)
												)
											);		
				
				
				if(is_array($old_custom_text_array)){
					$modulefinish = array_merge_recursive($old_custom_text_array,$custom_text_arraytodb);
					
				}
				
				else{
					$modulefinish = $custom_text_arraytodb;
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

				$old_custom_text_array = unserialize(base64_decode($isExistModuleSetting['option_value']));

				$countrowmodule = count($old_custom_text_array[$position]);

				/* is exist module */	
				$custom_text_arraytodb = array(
											$position => array(
												$custom_text_r	=> array(
													'sort' => $countrowmodule ,
													'module' => 'custom_text',
													'judul' => $post['judul'],
													'deskripsi' => $post['deskripsi'],
													)
												)
											);		
				
				
				$modulefinish = $custom_text_arraytodb;
				
				$insert_module = 	array(
											'option_name' => 'module_setting',
											'blog_id' => $blogid,
											'option_value' => base64_encode(serialize($modulefinish))
										);						
				
				$_this->Global_model->addNew($insert_module, 'kp_options');
											
			}
			
			/* array for view */
			$array_custom_text = array(
										'status' => TRUE,
										'front' => $custom_text_R,
										'frontname' => $custom_text_r,
										'module' => 'custom_text'
									);
			
			/* json post */
			$display =  json_encode($array_custom_text);
		}																				
	
		return $display;
	}
	
	function custom_text($content=NULL){
		$_this =& get_instance();		
		$display = NULL;

		$display = '<div class="module widget custom_text">';
		
		if(!empty($content['judul'])){
			$display .= '<h3 class="module">'.$content['judul'].'</h3>';
			
		}
		
	
		
		$display .= '<p class="module">'.$content['deskripsi'].' </p>';		
		$display .= "</div>\n";
		return $display;		
	}
?>