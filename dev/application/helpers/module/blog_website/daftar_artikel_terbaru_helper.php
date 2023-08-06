<?php
	function daftar_artikel_terbaru_backend($type=NULL,$post=NULL,$position=NULL,$modulekey=NULL,$blogid=NULL){
			$display = NULL;
			$_this =& get_instance();		
			
			if($type=='front'){
				$display = '';
			}
			
			else if($type=='desc'){
				$display = '<div class="modulbox">
							<h3>Daftar Artikel Terbaru</h3>
							<img src="'.get_asset('images').'/recentpost.png" />
							<p>Modul ini digunakan menampilkan daftar artikel terbaru pada fasilitas modul di website Anda</p>			
							<a href="#" id="daftar_artikel_terbaru_backend">+ Tambah Modul Ini</a>
							</div>';
			}								
	
			else if($type=='edit'){
				if($post['atribut']['thumbnail'] == "on"){
					$atr = 'checked';
				}
				else{
					$atr ='';
				}

				if(@$post['atribut']['intro'] == "on"){
					$atrintro = 'checked';
				}
				else{
					$atrintro ='';
				}

				$arraychoosetmb = array(
										0 => 'Silahkan dipilih' , 
										64 => '64x64 pixels' ,
										100 => '100x100 pixels' ,
										128 => '128x128 pixels' ,
										256 => '256x256 pixels' 
									);

				$display .= form_open(base_url().'admin/tampilan/update_modul_pilihan', 'name="module_form" class="update" id="module_form"');
				$display .= '<h3>Daftar Artikel Terbaru</h3>'."\n";
				$display .= '<div class="daftar_artikel_terbaru_helper">'."\n";
				$display .= '<label class="forframe" for="judul">Judul Daftar Artikel </label><input value="'.$post['judul'].'" id="judul" name="judul" class="input_text" type="text">'."\n";
				$display .= '<p class="helpernote">(Silahkan masukkan Judul Daftar Artikel Terbarunya-nya, misalnya : Daftar Artikel Terbaru )</p>'."\n";
				$display .= '<label class="forframe" for="jumlah">Jumlah Tampil</label><input value="'.$post['atribut']['jumlah'].'" id="jumlah" name="jumlah" class="input_text" type="text">'."\n";
				$display .= '<label class="forframe">Thumbnail</label><input type="checkbox" id="thumbnail" name="thumbnail" class="input_text" '.$atr.' ><label for="thumbnail" class="labelcheck">Aktifkan gambar/thumbnail artikel</label><br />'."\n";
				$display .= '<label class="forframe">Ukuran Thumbnail</label>'.form_dropdown('tmbsize',$arraychoosetmb,@$post['atribut']['tmbsize'],' class="input_select" ')."<br />\n";
				$display .= '<label class="forframe">Intro Artikel</label><input type="checkbox" id="intro" name="intro" class="input_text" '.$atrintro.' ><label for="intro" class="labelcheck">Aktifkan intro artikel</label><br />'."\n";
				$display .= '</div>'."\n";
				$display .= '<input type="hidden" value="daftar_artikel_terbaru" id="daftar_artikel_terbaru_backend" name="modulename" class="framesubmit btnAct"  />'."\n";
				$display .= '<input type="hidden" value="'.$position.'" id="position" name="position" />'."\n";
				$display .= '<input type="hidden" value="'.$blogid.'" id="blogid" name="blogid" />'."\n";
				$display .= '<input type="hidden" value="'.$modulekey.'" id="key" name="modulekey" />'."\n";
				$display .= '<input type="submit" value="Simpan Daftar Artikel Terbaru!" id="simpan_modul" class="submit"  />'."\n";
				$display .= form_close();
			}																																
											
			else if($type=='insert'){

				$arraychoosetmb = array(
										0 => 'Silahkan dipilih' , 
										64 => '64x64 pixels' ,
										100 => '100x100 pixels' ,
										128 => '128x128 pixels' ,
										256 => '256x256 pixels' 
									);

				$display .= form_open(base_url().'admin/tampilan/tambah_modul_pilihan', 'class="insert" name="module_form" id="module_form"');
				$display .= '<h3>Daftar Artikel Terbaru</h3>'."\n";
				$display .= '<div class="daftar_artikel_terbaru_helper">'."\n";
				$display .= '<label class="forframe" for="judul">Judul Daftar Artikel</label><input id="judul" name="judul" class="input_text" type="text">'."\n";
				$display .= '<p class="helpernote">(Silahkan masukkan Judul Daftar Artikel Terbaru-nya, misalnya : Daftar Artikel Terbaru )</p>'."\n";
				$display .= '<label class="forframe" for="jumlah">Jumlah Tampil</label><input id="jumlah" name="jumlah" class="input_text" type="text">'."\n";
				$display .= '<label class="forframe">Thumbnail</label><input type="checkbox" id="thumbnail" name="thumbnail" class="input_text"><label for="thumbnail" class="labelcheck">Aktifkan gambar/thumbnail artikel</label>'."<br />\n";
				$display .= '<label class="forframe">Ukuran Thumbnail</label>'.form_dropdown('tmbsize',$arraychoosetmb,$post['atribut']['tmbsize'],' class="input_select" ')."<br />\n";				
				$display .= '<label class="forframe">Intro Artikel</label><input type="checkbox" id="intro" name="intro" class="input_text" ><label for="intro" class="labelcheck">Aktifkan intro artikel</label><br />'."\n";
				$display .= '</div>'."\n";
				$display .= '<input type="hidden" value="daftar_artikel_terbaru" id="daftar_artikel_terbaru_backend" name="modulename" class="framesubmit btnAct"  />'."\n";
				$display .= '<input type="hidden" value="" id="position" name="position" />'."\n";
				$display .= '<input type="hidden" value="" id="blogid" name="blogid" />'."\n";
				$display .= '<input type="submit" value="Simpan Daftar Artikel Terbaru!" id="simpan_modul" class="submit"  />'."\n";
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
														'module' => 'daftar_artikel_terbaru',
														'judul' => $post['judul'],
														'atribut' => array(
																			'jumlah' => $post['jumlah'],
																			'thumbnail' => @$post['thumbnail'],
																			'intro' => @$post['intro'],
																			'tmbsize' => @$post['tmbsize']
																		)
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
					
				$pattern = "#\s*?daftar_artikel_terbaru*#s";
				$grabmodule = preg_match_all($pattern, base64_decode($isExistModuleSetting['option_value']), $result);
				$countmodule = count($result[0]);
				$newmoduleexist = ($countmodule / 2) + 1;

				if($countmodule > 0) { 
					$module_r = 'daftar_artikel_terbaru_'.$newmoduleexist; 
					$module_R = 'Daftar Artikel Terbaru '.$newmoduleexist; 
				} 
				else {
					$module_r = 'daftar_artikel_terbaru' ;
					$module_R = 'Daftar Artikel Terbaru';
				}
					
								
				/* jika dalam kp_options ada module setting */
				if($isExistModuleSetting != NULL){
					
					$old_module_array = unserialize(base64_decode($isExistModuleSetting['option_value']));

					$countarraypost = count(@$old_module_array[$position]);
					
					/* is exist module */	
					$module_arraytodb = array(
												$position => array(
													$module_r	=> array(
														'sort' => $countarraypost,
														'module' => 'daftar_artikel_terbaru',
														'judul' => $post['judul'],
														'atribut' => array(
																			'jumlah' => @$post['jumlah'],
																			'thumbnail' => @$post['thumbnail'],
																			'intro' => @$post['intro'],
																			'tmbsize' => @$post['tmbsize']
																		)
														)
													)
												);		
					
					
					if(is_array($old_module_array)){
						$modulefinish = array_merge_recursive($old_module_array,$module_arraytodb);
						
					}
					
					else{
						$modulefinish = $module_arraytodb;
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

					/* is exist module */	
					$module_arraytodb = array(
												$position => array(
													$module_r	=> array(
														'sort' => 0,
														'module' => 'daftar_artikel_terbaru',
														'judul' => $post['judul'],
														'atribut' => array(
																			'jumlah' => $post['jumlah'],																			
																			'thumbnail' => $post['thumbnail'],
																			'intro' => @$post['intro'],
																			'tmbsize' => @$post['tmbsize']
																		)
														)
													)
												);		
					
					
					
					$modulefinish = $module_arraytodb;
						
					$insert_module = 	array(
												'option_name' => 'module_setting',
												'blog_id' => $blogid,
												'option_value' => base64_encode(serialize($modulefinish))
											);						
												
					$_this->Global_model->addNew($insert_module, 'kp_options');						
				}
				
				/* array for view */
				$array_modulex = array(
											'status' => TRUE,
											'front' => $module_R,
											'frontname' => $module_r,
											'module' => 'daftar_artikel_terbaru'
										);
				
				/* json post */
				$display =  json_encode($array_modulex);
			}
					
			return $display;
	}	
			
	function daftar_artikel_terbaru($content=NULL){
		$_this =& get_instance();		
		$_this->load->model('Post_model');
		$domainatr = $_this->main->allAtr ;
		$display = NULL;

		$display = '<div class="module widget daftar_artikel">';
		$getarticle = $_this->Post_model->get_post('post', 'publish', $domainatr->domain_id, $content['atribut']['jumlah']);				

		if(!empty($content['judul'])){
			$display .= '<h3 class="module">'.$content['judul'].'</h3>';
			$display .= '<div class="post-list"><ul class="artikel_terbaru">';

			if(!empty($getarticle)){
				foreach($getarticle as $record){
					$date = strtotime($record['post_date']);	
					$width = $content['atribut']['tmbsize'];
					$height = $content['atribut']['tmbsize'];
					$postcat = explode(',', $record['post_category']);					
					$display .= '<li>';
					
					if($content['atribut']['thumbnail'] == 'on'){
						$display .= '<img src="'. get_thumb(img_capture($record['post_content']),$width,$height,1).'" alt="image post" class="tmbpost" />';
					}

					$display .= '<a href="'.base_url().'artikel/'.$postcat[0].'/'.$record['post_name'].'">'.$record['post_title'].'</a>';
					$display .= '<em class="meta clock">'.mdate('%d/%m/%Y',$date).'</em>';
					$display .= '<br class="floating" /></li>';
				}
			}

			$display .= '</ul></div>';
		}
		
		
		$display .= '</div>';
		return $display;
	}													
?>