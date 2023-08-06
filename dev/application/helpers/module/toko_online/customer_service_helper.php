<?php
	function customer_service_backend($type=NULL,$post=NULL,$position=NULL,$modulekey=NULL,$blogid=NULL){
			$display = NULL;
			$_this =& get_instance();		
	
			if($type=='front'){
				$display = '';
			}

			else if($type=='desc'){
				$display = '<div class="modulbox">
							<h3>Customer Service</h3>
							<img src="'.get_asset('images').'/contact.png" />
							<p>Modul ini digunakan untuk menampilkan Kontak / Support / Customer Service website Anda</p>			
							<a href="#" id="customer_service_backend">+ Tambah Modul Ini</a>
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
				$display .= '<h3>Customer Service</h3>'."\n";
				$display .= '<div class="ymform">'."\n";				
				$display .= '<label class="forframe" for="judul">Judul CS</label><input value="'.$post['judul'].'" id="judul" name="judul" class="input_text" type="text">'."\n";
				$display .= '<p class="helpernote">(Silahkan masukkan Judul Customer Service-nya, misalnya : Customer Service )</p>'."\n";
				
				$display .= '<label class="forframe" for="deskripsi">Deskripsi</label><textarea id="deskripsi" name="deskripsi" class="desc">'.$post['deskripsi'].'</textarea>'."\n";
				$display .= '<p class="helpernote">(Anda bisa memasukkan deskripsinya di sini)</p>'."\n";
				$display .= '<label class="forframe" for="telepon">Telepon</label><input id="telepon" value="'.$post['atribut']['telepon'].'" name="telepon" class="input_text" type="text">'."\n";
				$display .= '<label class="forframe" for="sms">SMS</label><input id="sms" name="sms" value="'.$post['atribut']['sms'].'" class="input_text" type="text">'."\n";
				$display .= '<label class="forframe" for="wa">Whatsapp</label><input id="wa" name="wa" value="'.$post['atribut']['wa'].'" class="input_text" type="text">'."\n";
				$display .= '<label class="forframe" for="line">Line</label><input id="line" name="line" value="'.$post['atribut']['line'].'" class="input_text" type="text">'."\n";
				$display .= '<label class="forframe" for="bbm">Pin BB</label><input id="bbm" name="bbm" value="'.$post['atribut']['bbm'].'" class="input_text " type="text">'."\n";

				$display .= '</div>'."\n";
				$display .= '<input type="hidden" value="customer_service" id="customer_service_backend" name="modulename" class="framesubmit btnAct"  />'."\n";
				$display .= '<input type="hidden" value="'.$position.'" id="position" name="position" />'."\n";
				$display .= '<input type="hidden" value="'.$blogid.'" id="blogid" name="blogid" />'."\n";
				$display .= '<input type="hidden" value="'.$modulekey.'" id="key" name="modulekey" />'."\n";
				$display .= '<input type="submit" value="Simpan!" id="simpan_modul" class="submit"  />'."\n";
				$display .= form_close();
			}																																
											
			else if($type=='insert'){
				$display .= form_open(base_url().'admin/tampilan/tambah_modul_pilihan', 'class="insert" name="module_form" id="module_form"');
				$display .= '<h3>Customer Service</h3>'."\n";
				$display .= '<div class="ymform">'."\n";
				$display .= '<label class="forframe" for="judul">Judul CS</label><input id="judul" name="judul" class="input_text" type="text">'."\n";
				$display .= '<p class="helpernote">(Silahkan masukkan Judul Customer Service-nya, misalnya : Customer Service )</p>'."\n";
				
				$display .= '<label class="forframe" for="deskripsi">Deskripsi</label><textarea id="deskripsi" name="deskripsi" class="desc"></textarea>'."\n";
				$display .= '<p class="helpernote">(Anda bisa memasukkan deskripsinya di sini)</p>'."\n";							
				$display .= '<label class="forframe" for="telepon">Telepon</label><input id="telepon" name="telepon" class="input_text" type="text">'."\n";
				$display .= '<label class="forframe" for="sms">SMS</label><input id="sms" name="sms" class="input_text" type="text">'."\n";
				$display .= '<label class="forframe" for="wa">Whatsapp</label><input id="wa" name="wa" class="input_text" type="text">'."\n";
				$display .= '<label class="forframe" for="line">Line</label><input id="line" name="line" class="input_text" type="text">'."\n";
				$display .= '<label class="forframe" for="bbm">Pin BB</label><input id="bbm" name="bbm" class="input_text " type="text">'."\n";

				$display .= '</div>'."\n";
				$display .= '<input type="hidden" value="customer_service" id="customer_service_backend" name="modulename" class="framesubmit btnAct"  />'."\n";
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
													'module' => 'customer_service',
													'judul' => $post['judul'],
													'deskripsi' => $post['deskripsi'],
													'atribut' => array(
																	'telepon' => $post['telepon'],																			
																	'sms' => $post['sms'],
																	'wa' => @$post['wa'],
																	'line' => @$post['line'],																	
																	'bbm' => @$post['bbm']	,
																	)													
												);		
			
			$where_update_module = 	array(
										'option_name' => 'module_setting',
										'blog_id' => $blogid
									);						
										
			$_this->Global_model->update($where_update_module, 
									array('option_value' => base64_encode(serialize($listmodule))),
									'kp_options');		

			
			$array_customer_service = array(
										'status' => TRUE,
									);
									
			$display = json_encode($array_customer_service);
					
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

			
			$pattern = "#\s*?customer_service*#s";
			$grabmodule = preg_match_all($pattern, base64_decode($isExistModuleSetting['option_value']), $result);
			$countmodule = count($result[0]);		
			$newmoduleexist = ($countmodule / 2) + 1;

			if($countmodule > 0) { 
				$customer_service_r = 'customer_service_'.$newmoduleexist; 
				$customer_service_R = 'Customer service '.$newmoduleexist; 
			} 
			else {
				$customer_service_r = 'customer_service' ;
				$customer_service_R = 'Customer service';
			}
			

						
			/* jika dalam kp_options ada module setting */
			if($isExistModuleSetting != NULL){
				
				
				$old_customer_service_array = unserialize(base64_decode($isExistModuleSetting['option_value']));

				$countrowmodule = count($old_customer_service_array[$position]);
				
				/* is exist module */	
				$customer_service_arraytodb = array(
											$position => array(
												$customer_service_r	=> array(
													'sort' => $countrowmodule,
													'module' => 'customer_service',
													'judul' => $post['judul'],
													'deskripsi' => $post['deskripsi'],
													'atribut' => array(														
																	'telepon' => $post['telepon'],																			
																	'sms' => $post['sms'],
																	'wa' => @$post['wa'],
																	'line' => @$post['line'],																	
																	'bbm' => @$post['bbm']	,
																	)														
													)
												)
											);		
				
				
				if(is_array($old_customer_service_array)){
					$modulefinish = array_merge_recursive($old_customer_service_array,$customer_service_arraytodb);
					
				}
				
				else{
					$modulefinish = $customer_service_arraytodb;
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

				$old_customer_service_array = unserialize(base64_decode($isExistModuleSetting['option_value']));

				$countrowmodule = count(@$old_customer_service_array[$position]);

				/* is exist module */	
				$customer_service_arraytodb = array(
											$position => array(
												$customer_service_r	=> array(
													'sort' => $countrowmodule ,
													'module' => 'customer_service',
													'judul' => $post['judul'],
													'deskripsi' => $post['deskripsi'],
													'atribut' => array(														
																	'telepon' => $post['telepon'],																			
																	'sms' => $post['sms'],
																	'wa' => @$post['wa'],
																	'line' => @$post['line'],																	
																	'bbm' => @$post['bbm']	,
																	)														
													)
												)
											);		
				
				
				$modulefinish = $customer_service_arraytodb;
				
				$insert_module = 	array(
											'option_name' => 'module_setting',
											'blog_id' => $blogid,
											'option_value' => base64_encode(serialize($modulefinish))
										);						
				
				$_this->Global_model->addNew($insert_module, 'kp_options');
											
			}
			
			/* array for view */
			$array_customer_service = array(
										'status' => TRUE,
										'front' => $customer_service_R,
										'frontname' => $customer_service_r,
										'module' => 'customer_service'
									);
			
			/* json post */
			$display =  json_encode($array_customer_service);
		}																				
	
		return $display;
	}
			
	function customer_service($content=NULL){
		$_this =& get_instance();		
		$display = NULL;
		$domainatr = $_this->main->allAtr;
		
		$display = '<div class="module widget catlist cs">';
		
		if(!empty($content['judul'])){
			$display .= '<h3 class="module">'.$content['judul'].'</h3>';			
		}

		$image = 'http://www.kaffah.biz/assets/images/cs/';

		$display .= '<p class="modulehead">'.$content['deskripsi'].'</hp>';	
		$display .= '<ul id="cs">';
		foreach($content['atribut'] as $key => $val){
			$display .= '<li><img src="'.$image.$key.'.png" /><em class="kontak">'.$val.'</em></li>';	
		}
		
		$display .= '</ul>';

		$display .= '</div>';
		return $display;
	}													
?>