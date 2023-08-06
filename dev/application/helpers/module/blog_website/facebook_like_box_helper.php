<?php
	function facebook_like_box_backend($type=NULL,$post=NULL,$position=NULL,$modulekey=NULL,$blogid=NULL){
			$display = NULL;
			$_this =& get_instance();		
	
			if($type=='front'){
				$display = '';
			}

			else if($type=='desc'){
				$display = '<div class="modulbox">
							<h3>Facebook Like box</h3>
							<img src="'.get_asset('images').'/facebookblavk.png" />
							<p>Modul Facebook Like Box = menampilkan likers dari fans page Anda website Anda, update terbaru di fans page</p>			
							<a href="#" id="facebook_like_box_backend">+ Tambah Modul Ini</a>
							</div>';
			}								
	
			else if($type=='edit'){
				if(@$post['atribut']['face'] == 'true'){
					$atrface = 'checked';
				}
				else{
					$atrface = '';
				}

				if(@$post['atribut']['status'] == 'true'){
					$atrstatus = 'checked';
				}
				else{
					$atrstatus = '';
				}

				if(@$post['atribut']['label'] == 'true'){
					$atrlabel = 'checked';
				}
				else{
					$atrlabel = '';
				}								

				$display .= form_open(base_url().'admin/tampilan/update_modul_pilihan', 'name="module_form" class="update" id="module_form"');
				$display .= '<h3>Facebook Like box</h3>'."\n";
				$display .= '<div class="ymform">'."\n";				
				$display .= '<label class="forframe" for="judul">Judul Facebook Like Box</label><input value="'.$post['judul'].'" id="judul" name="judul" class="input_text" type="text">'."\n";
				$display .= '<p class="helpernote">(Silahkan masukkan Facebook Like Box-nya, misalnya : Ikuti Facebook kami )</p>'."\n";
				$display .= '<label class="forframe" for="url">Alamat/URL Fans Page</label><input value="'.$post['atribut']['url'].'" id="url" name="url" class="input_text" type="text">'."\n";
				$display .= '<p class="helpernote">(Silahkan masukkan alamat/URL fans page facebook, misalnya : http://www.facebook.com/kaffah.biz)</p>'."\n";
				$display .= '<label class="forframe" for="width">Lebar / Tinggi</label><input id="width" name="width" value="'.$post['atribut']['width'].'" class="input_text short" type="text"> px
							/ <input id="height" name="height" class="input_text short" type="text" value="'.$post['atribut']['height'].'"> px'."<br />\n";	
				$display .= '<label class="forframe">Tampilkan Wajah</label><input type="checkbox" id="face" name="face" class="input_text" value="true" '.$atrface.' >
							<label for="face" class="labelcheck">Ceklis Untuk Menampilkan Wajah di fanspage like box</label>							
							<br />'."\n";
				$display .= '<label class="forframe">Status</label><input type="checkbox" id="status" name="status" class="input_text" value="true" '.$atrstatus.'>
							<label for="status" class="labelcheck">Ceklis Untuk Menampilkan Status Timeline</label>							
							<br />'."\n";
				$display .= '<label class="forframe">Label Facebook</label><input type="checkbox" id="label" name="label" class="input_text" value="true" '.$atrlabel.'>
							<label for="label" class="labelcheck">Ceklis Untuk Menampilkan Label Fanspage</label>							
							<br />'."\n";
				$display .= '</div>'."\n";
				$display .= '<input type="hidden" value="facebook_like_box" id="facebook_like_box_backend" name="modulename" class="framesubmit btnAct"  />'."\n";
				$display .= '<input type="hidden" value="'.$position.'" id="position" name="position" />'."\n";
				$display .= '<input type="hidden" value="'.$blogid.'" id="blogid" name="blogid" />'."\n";
				$display .= '<input type="hidden" value="'.$modulekey.'" id="key" name="modulekey" />'."\n";
				$display .= '<input type="submit" value="Simpan!" id="simpan_modul" class="submit"  />'."\n";
				$display .= form_close();
			}																																
											
			else if($type=='insert'){
				$display .= form_open(base_url().'admin/tampilan/tambah_modul_pilihan', 'class="insert" name="module_form" id="module_form"');
				$display .= '<h3>Facebook Like box</h3>'."\n";
				$display .= '<div class="ymform">'."\n";
				$display .= '<label class="forframe" for="judul">Judul Like Box</label><input id="judul" name="judul" class="input_text" type="text">'."\n";
				$display .= '<p class="helpernote">(Silahkan masukkan Facebook Like Box-nya, misalnya : Ikuti Facebook kami )</p>'."\n";
				$display .= '<label class="forframe" for="url">URL Fans Page</label><input id="url" name="url" class="input_text" type="text">'."\n";
				$display .= '<p class="helpernote">(Silahkan masukkan alamat/URL fans page facebook, misalnya : http://www.facebook.com/kaffah.biz)</p>'."\n";
				$display .= '<label class="forframe" for="width">Lebar / Tinggi</label><input id="width" name="width" class="input_text short" type="text"> px
							/ <input id="height" name="height" class="input_text short" type="text"> px'."<br />\n";	
				$display .= '<label class="forframe">Tampilkan Wajah</label><input type="checkbox" id="face" name="face" class="input_text" value="dropdown" >
							<label for="face" class="labelcheck">Ceklis Untuk Menampilkan Wajah di fanspage like box</label>							
							<br />'."\n";
				$display .= '<label class="forframe">Status</label><input type="checkbox" id="status" name="status" class="input_text" value="dropdown" >
							<label for="status" class="labelcheck">Ceklis Untuk Menampilkan Status Timeline</label>							
							<br />'."\n";
				$display .= '<label class="forframe">Label Facebook</label><input type="checkbox" id="label" name="label" class="input_text" value="dropdown" >
							<label for="label" class="labelcheck">Ceklis Untuk Menampilkan Label Fanspage</label>							
							<br />'."\n";
				$display .= '</div>'."\n";
				$display .= '<input type="hidden" value="facebook_like_box" id="facebook_like_box_backend" name="modulename" class="framesubmit btnAct"  />'."\n";
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
													'module' => 'facebook_like_box',
													'judul' => $post['judul'],
													'atribut' => array(
																		'url' => @$post['url'],
																		'width' => @$post['width'],
																		'height' => @$post['height'],
																		'face' => @$post['face'],
																		'status' => @$post['status'],
																		'label' => @$post['label']
																	)													
												);		
			
			$where_update_module = 	array(
										'option_name' => 'module_setting',
										'blog_id' => $blogid
									);						
										
			$_this->Global_model->update($where_update_module, 
									array('option_value' => base64_encode(serialize($listmodule))),
									'kp_options');		

			
			$array_facebook_like_box = array(
										'status' => TRUE,
									);
									
			$display = json_encode($array_facebook_like_box);
					
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

			
			$pattern = "#\s*?facebook_like_box*#s";
			$grabmodule = preg_match_all($pattern, base64_decode($isExistModuleSetting['option_value']), $result);
			$countmodule = count($result[0]);		
			$newmoduleexist = ($countmodule / 2) + 1;

			if($countmodule > 0) { 
				$facebook_like_box_r = 'facebook_like_box_'.$newmoduleexist; 
				$facebook_like_box_R = 'FacebooK like box '.$newmoduleexist; 
			} 
			else {
				$facebook_like_box_r = 'facebook_like_box' ;
				$facebook_like_box_R = 'FacebooK like box';
			}
			

						
			/* jika dalam kp_options ada module setting */
			if($isExistModuleSetting != NULL){
				
				
				$old_facebook_like_box_array = unserialize(base64_decode($isExistModuleSetting['option_value']));

				$countrowmodule = count(@$old_facebook_like_box_array[$position]);
				
				/* is exist module */	
				$facebook_like_box_arraytodb = array(
											$position => array(
												$facebook_like_box_r	=> array(
													'sort' => $countrowmodule,
													'module' => 'facebook_like_box',
													'judul' => $post['judul'],
													'atribut' => array(
																		'url' => @$post['url'],
																		'width' => @$post['width'],
																		'height' => @$post['height'],
																		'face' => @$post['face'],
																		'status' => @$post['status'],
																		'label' => @$post['label']
																	)														
													)
												)
											);		
				
				
				if(is_array($old_facebook_like_box_array)){
					$modulefinish = array_merge_recursive($old_facebook_like_box_array,$facebook_like_box_arraytodb);
					
				}
				
				else{
					$modulefinish = $facebook_like_box_arraytodb;
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

				$old_facebook_like_box_array = unserialize(base64_decode($isExistModuleSetting['option_value']));

				$countrowmodule = count($old_facebook_like_box_array[$position]);

				/* is exist module */	
				$facebook_like_box_arraytodb = array(
											$position => array(
												$facebook_like_box_r	=> array(
													'sort' => $countrowmodule ,
													'module' => 'facebook_like_box',
													'judul' => $post['judul'],
													'atribut' => array(
																		'url' => @$post['url'],
																		'width' => @$post['width'],
																		'height' => $post['height'],
																		'face' => @$post['face'],
																		'status' => @$post['status'],
																		'label' => @$post['label']
																	)														
													)
												)
											);		
				
				
				$modulefinish = $facebook_like_box_arraytodb;
				
				$insert_module = 	array(
											'option_name' => 'module_setting',
											'blog_id' => $blogid,
											'option_value' => base64_encode(serialize($modulefinish))
										);						
				
				$_this->Global_model->addNew($insert_module, 'kp_options');
											
			}
			
			/* array for view */
			$array_facebook_like_box = array(
										'status' => TRUE,
										'front' => $facebook_like_box_R,
										'frontname' => $facebook_like_box_r,
										'module' => 'facebook_like_box'
									);
			
			/* json post */
			$display =  json_encode($array_facebook_like_box);
		}																				
	
		return $display;
	}
			
	function facebook_like_box($content=NULL){
		$_this =& get_instance();		
		$display = NULL;
		$domainatr = $_this->main->allAtr;
		
		$display = '<div class="module widget catlist">';
		
		if(!empty($content['judul'])){
			$display .= '<h3 class="module">'.$content['judul'].'</h3>';			
		}
		
		$display .= '<div class="fb-like-box" style="background:#fff" data-href="'.$content['atribut']['url'].'" data-width="'.$content['atribut']['width'].'" data-show-faces="'.$content['atribut']['face'].'" data-stream="'.$content['atribut']['status'].'" data-header="'.$content['atribut']['label'].'"></div>';		

		$display .= '</div>';
		return $display;
	}													
?>