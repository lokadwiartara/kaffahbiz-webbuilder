<?php
	function slider_animation_backend($type=NULL,$post=NULL,$position=NULL,$modulekey=NULL,$blogid=NULL){
		$display = NULL;
		$_this =& get_instance();
		$_this->load->model('Site_model');
		$doc = new DOMDocument();

		/* ambil slider dari template */
		$kaffahsliderpattern = '#<\s*?kaffah_slider\b[^>]*>(.*?)</kaffah_slider\b[^>]*>#s';		
		$blogid = $_this->session->userdata('blogid');
		$templatesetting = $_this->main->getDomAtr(NULL,$blogid);
		$template = $templatesetting->domain_html;
		$templateRegex = preg_match_all($kaffahsliderpattern,$template,$result);	
		$screenwithtag = $result[0];	
	
		for($x=0;$x<count($screenwithtag);$x++){

			/* ambil fungsi untuk tag name kaffah e */
			libxml_use_internal_errors(true);
			$doc->loadHTML($screenwithtag[$x]);
			libxml_use_internal_errors(false);
			
			/* pengulangan ini didgunakan untuk mendapatkan satu demi kaffah_e */
			$foreachkaffahslider = $doc->getElementsByTagName('kaffah_slider');
			
			/* disini digunakan untuk menampilkan hasil dari kaffahe */
			foreach($foreachkaffahslider as $tag){	
			
		       $slider[$tag->getAttribute('name')] = $tag->getAttribute('atr');		       
				
			}		
			
		}


		if($type=='front'){
			$display = '';
		}
		
		else if($type=='desc'){
			$display = '<div class="modulbox">
						<h3>Slider Animation</h3>
						<img src="'.get_asset('images').'listpost.png" />
						<p>Modul ini digunakan untuk menampilkan slider sesuai template bawaan, dan sesuai dengan kebutuhkan</p>
						<a href="#" id="slider_animation_backend">+ Tambah Modul Ini</a>
						</div>';
		}								

		else if($type=='edit'){			

			$atribut = $post['atribut'];
			$slideratr = $atribut[$position];

			$atr = explode(',',$slideratr);

			for($x=0;$x<count($atr);$x++){
				$temp = explode('=',$atr[$x]);
				if($temp[1] != 'iteration'){
					$singleatr[$temp[0]] = $temp[1];
					$varsingle = count($atribut[$temp[0]]);			
				}				
			}


			$display .= form_open(base_url().'admin/tampilan/update_modul_pilihan', 'name="module_form" class="update" id="module_form"');
			$display .= '<h3>Slider Animation</h3>'."\n";
			$display .= '<div class="slider_animation_helper">'."\n";
			$display .= '<label class="forframe" for="judul">Judul Slider</label><input id="judul" name="judul" class="input_text" type="text" value="'.@$post['judul'].'">'."\n";
			$display .= '<p class="helpernote">(Silahkan masukkan Judul Slider-nya, misalnya : Facebook FansBox )</p>'."\n";					
			$display .= '<a href="" class="btnaddd slide" id="tambahslide">+ Tambah Slide</a><br />';
			$display .= '<div class="slide_wrapper">';
			
			for($y=0;$y<$varsingle;$y++){
				$z = $y+1;
				$display .= '<h4 class="head_'.$z.'">Slide ke '.$z.'</h4> <a href="" id="delbtn_'.$z.'" class="delbttn">(klik untuk hapus)</a>';
				$display .= '<div class="slide_module" id="slide_module'.$z.'">';

				foreach($singleatr as $key => $val){
					if($val == 'textarea'){
						$display .= '<br /><label class="forframe" for="judul">'.$key.'</label><textarea name="'.$key.'[]" class="desc">'.$atribut[$key][$y].'</textarea>';	
					}

					else if($val == 'text'){
						$display .= '<br /><label class="forframe" for="judul">'.$key.'</label><input id="judul" name="'.$key.'[]" class="input_text" type="text" value="'.$atribut[$key][$y].'">';	
					}

					else if($val == 'image'){
						$display .= '<br /><label for="label" class="forframe" id="label_'.$key.'_'.$val.'_'.$z.'">'.$key.'</label>';
						$display .= '<a href="'.$atribut[$key][$y].'" class="uploadimg" target="_blank" id="link_'.$key.'_'.$val.'_'.$z.'">'.$atribut[$key][$y].'</a> <a href="" class="delimg" id="del_'.$key.'_'.$val.'_'.$z.'">- Hapus</a>';
						$display .= '<input type="hidden" value="'.$atribut[$key][$y].'" id="hide_'.$key.'_'.$val.'_'.$z.'" name="'.$key.'[]" />';
					}
				}

				$display .= '</div>';
			}			

			

			$display .= '</div>';
			$display .= '</div>'."\n";
			$display .= '<input type="hidden" value="slider_animation" id="slider_animation_backend" name="modulename" class="framesubmit btnAct"  />'."\n";
			$display .= '<input type="hidden" value="'.$position.'" id="position" name="position" />'."\n";
			$display .= '<input type="hidden" value="'.$blogid.'" id="blogid" name="blogid" />'."\n";
			$display .= '<input type="hidden" value="'.$modulekey.'" id="key" name="modulekey" />'."\n";

			if(!empty($slider)){
				foreach($slider as $key => $val)
				$display .= '<input type="hidden" value="'.$val.'" id="'.$key.'" name="'.$key.'" />'."\n";
			}	

			$display .= '<input type="submit" value="Simpan Slider!" id="simpan_modul" class="submit"  />'."\n";
			$display .= form_close();
		}																																
										
		else if($type=='insert'){			
			$display .= form_open(base_url().'admin/tampilan/tambah_modul_pilihan', 'class="insert" name="module_form" id="module_form"');
			$display .= '<h3>Slider</h3>'."\n";
			$display .= '<div class="slider_helper">'."\n";
			$display .= '<label class="forframe" for="judul">Judul Slider</label><input id="judul" name="judul" class="input_text" type="text">'."\n";
			$display .= '<p class="helpernote">(Silahkan masukkan Judul Slider-nya, misalnya : Facebook FansBox )</p>'."\n";
			$display .= '<a href="" class="btnaddd slide" id="tambahslide">+ Tambah Slide</a><br />';
			$display .= '<div class="slide_wrapper"></div>';
			$display .= '</div>'."\n";
			$display .= '<input type="hidden" value="slider_animation" id="slider_animation_backend" name="modulename" class="framesubmit btnAct"  />'."\n";
			$display .= '<input type="hidden" value="" id="position" name="position" />'."\n";
			$display .= '<input type="hidden" value="" id="blogid" name="blogid" />'."\n";

			if(!empty($slider)){
				foreach($slider as $key => $val)
				$display .= '<input type="hidden" value="'.$val.'" id="'.$key.'" name="'.$key.'" />'."\n";
			}

			$display .= '<input type="submit" value="Simpan Slider!" id="simpan_modul" class="submit"  />'."\n";
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
													'module' => 'slider_animation',
													'judul' => $post['judul'],
													'atribut' => $post

												);		
			

			$where_update_module = 	array(
										'option_name' => 'module_setting',
										'blog_id' => $blogid
									);						
										
			$_this->Global_model->update($where_update_module, 
									array('option_value' => base64_encode(serialize($listmodule))),
									'kp_options');		

			
			$array_slider = array(
										'status' => TRUE,
									);
									
			$display = json_encode($array_slider);
					
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

			
			$pattern = "#\s*?slider_animation*#s";
			$grabmodule = preg_match_all($pattern, base64_decode($isExistModuleSetting['option_value']), $result);			

			$countmodule = count($result[0]);		
			$newmoduleexist = ($countmodule / 2) + 0.5 ;			

			if($countmodule > 0) { 
				$slider_r = 'slider_animation_'.$newmoduleexist; 
				$slider_R = 'Slider Animation '.$newmoduleexist; 
			} 
			else {
				$slider_r = 'slider_animation' ;
				$slider_R = 'Slider Animation';
			}
			

						
			/* jika dalam kp_options ada module setting */
			if($isExistModuleSetting != NULL){
				
				
				$old_slider_array = unserialize(base64_decode($isExistModuleSetting['option_value']));

				$countrowmodule = count(@$old_slider_array[$position]);
				
				/* is exist module */	
				$slider_arraytodb = array(
											$position => array(
												$slider_r	=> array(
													'sort' => $countrowmodule,
													'module' => 'slider_animation',
													'judul' => $post['judul'],
													'atribut' => $post
													)
												)
											);										

				if(is_array($old_slider_array)){
					$modulefinish = array_merge_recursive($old_slider_array,$slider_arraytodb);
					
				}
				
				else{
					$modulefinish = $slider_arraytodb;
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

				$old_slider_array = unserialize(base64_decode($isExistModuleSetting['option_value']));

				$countrowmodule = count($old_slider_array[$position]);

				/* is exist module */	
				$slider_arraytodb = array(
											$position => array(
												$slider_r	=> array(
													'sort' => $countrowmodule ,
													'module' => 'slider_animation',
													'judul' => $post['judul'],
													'atribut' => $post
													)
												)
											);		
				
				
				$modulefinish = $slider_arraytodb;
				
				$insert_module = 	array(
											'option_name' => 'module_setting',
											'blog_id' => $blogid,
											'option_value' => base64_encode(serialize($modulefinish))
										);						
				
				$_this->Global_model->addNew($insert_module, 'kp_options');
											
			}
			
			/* array for view */
			$array_slider = array(
										'status' => TRUE,
										'front' => $slider_R,
										'frontname' => $slider_r,
										'module' => 'slider_animation'
									);
			
			/* json post */
			$display =  json_encode($array_slider);
		}																				
	
		return $display;
	}
	
	function slider_animation($content=NULL){
		$_this =& get_instance();		
		$display = NULL;

		$display = '<div class="module widget slider">';
		
		if(!empty($content['judul'])){
			$display .= '<h3 class="module">'.$content['judul'].'</h3>';
			
		}
		
	
		
		$display .= '<p class="module">'.$content['deskripsi'].' </p>';		
		$display .= "</div>\n";
		return $display;		
	}
?>