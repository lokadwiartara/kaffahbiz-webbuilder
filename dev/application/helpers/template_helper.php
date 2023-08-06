<?php
		
	function replace_($text, $separator, $upper=NULL){
		
		$textfix = str_replace($separator, ' ', $text);
		if($upper == TRUE){
			$textfix = ucwords($textfix);
		}
		
		return $textfix;
	}
	
	function get_asset($type,$url=NULL){
		if(!empty($url)){
			return 'http://'.$url.'/assets/'.$type.'/';	
		}
		
		else{
			return base_url().'assets/'.$type.'/';	
		}
	}

	function getRandomWord($len = 10) {
	    $word = array_merge(range('a', 'z'), range('A', 'Z'), range(0, 9));
	    shuffle($word);
	    return substr(implode($word), 0, $len);
	}	

	function get_offset($atributoffset=NULL){
		global $SConfig;
		$_this =& get_instance();

		/* if front home get offset home */

		if(!empty($atributoffset) || $atributoffset != ''){
			
			$offset = (int) $atributoffset;

			/* perhitungan baku offset jika menggunakan penomoran */
			$_this->template->offset = $offset ; // (( $offset - 1 ) * $_this->template->limit) + 1;

			/* kembalikan offset */
			return $_this->template->offset;			
		}

		else if($_this->uri->total_segments() == 2 && $_this->uri->segment(1) == 'no'){
			/* ambil offset dan jadikan integer untuk segment nomor 2 */
			$offset = (int) $_this->uri->segment(2);

			/* perhitungan baku offset jika menggunakan penomoran */
			$_this->template->offset = $offset ; // (( $offset - 1 ) * $_this->template->limit) + 1;

			/* kembalikan offset */
			return $_this->template->offset;
		}
		else if($_this->uri->total_segments() == 4 && $_this->uri->segment(3) == 'no'){
			/* ambil offset dan jadikan integer untuk segment nomor 2 */
			$offset = (int) $_this->uri->segment(4);

			/* perhitungan baku offset jika menggunakan penomoran */
			$_this->template->offset = $offset ; // (( $offset - 1 ) * $_this->template->limit) + 1;

			/* kembalikan offset */
			return $_this->template->offset;
		}

		else if($_this->uri->total_segments() == 3 && $_this->uri->segment(2) == 'no'){
			/* ambil offset dan jadikan integer untuk segment nomor 2 */
			$offset = (int) $_this->uri->segment(3);

			/* perhitungan baku offset jika menggunakan penomoran */
			$_this->template->offset = $offset ; // (( $offset - 1 ) * $_this->template->limit) + 1;

			/* kembalikan offset */
			return $_this->template->offset;
		}
	}

	function get_limit_post($type=NULL,$atributlimit=NULL){
		global $SConfig;
		$_this =& get_instance();
		$templatesetting = $_this->template->templatesetting;
		$templatesettingunserialize = unserialize(base64_decode(@$templatesetting['option_value']));
		
		if(!empty($atributlimit) || $atributlimit != ''){
			return $atributlimit;
		}
		/* jika ada maka langsung di unserialize */
		else if(!empty($templatesettingunserialize)){
			if($_this->main->isCategory == TRUE){
				return $templatesettingunserialize['content_setting']['artikel_perkategori'];
			}
			else if($_this->main->isSearch == TRUE){
				return $templatesettingunserialize['content_setting']['post_persearch'];
			}
			else if($_this->main->isCategoryProduct == TRUE){
				return $templatesettingunserialize['content_setting']['produk_perkategori'];
			}
			else if($_this->main->isHome == TRUE){
				if($type=='product'){
					return $templatesettingunserialize['content_setting']['produk_index'];	
				}
				else{
					return $templatesettingunserialize['content_setting']['artikel_index'];		
				}
				
			}			
		}
	}
	
	function get_dir_($path, $type){
		global $SConfig;
	    $full_host = $_SERVER['HTTP_HOST'];
		$npath = str_replace('\\', '/', $path);
		$get_host_digit = strlen($SConfig->approot);
		$full_path = substr($npath, $get_host_digit);
		
		return "http://".$full_host.$full_path.'/'.$type;
	}
	
	function get_k_loop($text){
		$startloop = strpos($text, '<kaffah:loop>');	
		$endloop =  strrpos($text, '</kaffah:loop>');
		$length = $endloop - $startloop - 18 ;
		$whattoloop = substr($text, $startloop+14, $length);		
		
		return $whattoloop; 
	}
	
	function k_title(){
		$_this =& get_instance();
		$title = 'Halaman Depan';
		return $title;
	}
	
	function k_loop($text){
		$_this =& get_instance();
		$display = '';
		for($x=0;$x<3;$x++){
			// $display .= $text;
		}
		
		return $display;
	}
	
	function get_template($view,$front=NULL){
		$_this =& get_instance();
		if($front!=NULL){
			return $_this->main->view_themes($view,'','TRUE');	
		}
		
		else{
			return $_this->main->view_themes($view);	
		}
		
	}
	
	function get_css_style($path, $style=NULL){
		global $SConfig;
	    $full_host = $_SERVER['HTTP_HOST'];
		$npath = str_replace('\\', '/', $path);
		$get_host_digit = strlen($SConfig->approot);
		$full_path = substr($npath, $get_host_digit);

		$css_path = isset($style) ? '<link rel="stylesheet" href="http://'.$full_host.$full_path.'/style/'.$style.'.css" type="text/css" />' : 
					'<link rel="stylesheet" href="http://'.$full_host.$full_path.'/style/styles.css" type="text/css" />';
		
		return $css_path;
	}
	
	function set_form_error($array){
		$_this =& get_instance();
		$_this->session->set_flashdata($array);
	}
	
	function get_form_error($whatto=NULL){
		$_this =& get_instance();
		$whattoview = $_this->session->flashdata($whatto);
		($whattoview != NULL)?
			$val =  '<div class="error custom">'.$_this->session->flashdata($whatto).'</div>' 
			:
			$val = NULL;
			; 
		return $val;
	}	
	
	function get_thumb($file, $w, $h, $zc, $domain=NULL){
		if(!empty($file)){
			$oldpath = changeimageurl($file);

			if(file_exists($oldpath)){			
			}
			else{
				$file = base_url().'uploads/no_photo.jpg';
			}
						
			$get = resize_image($file, $w, $h);
			

		} 
		else{
			$get = base_url().'uploads/no_photo.jpg'  ;
		}

		return $get;
	}


	function kaffahPostMetaTime($var=array()){
		$_this =& get_instance();		

		return date($var[1], strtotime($_this->template->datetemp));	
	}
	
	function array_unique_multidimensional($input){
	    $serialized = array_map('serialize', $input);
	    $unique = array_unique($serialized);
	    return array_intersect_key($input, $unique);
	}
	
	
	function img_capture($record=NULL) {
		$first_img = NULL;
		
		$post_content = $record;		
		
		$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post_content, $matches);
		
		(!empty($matches [1][0])) ? $first_img = $matches [1][0] : $first_img = '';
		
		if(empty($first_img)){ //Defines a default image
			$fix_image = base_url().'uploads/no_photo.jpg';

		}
		else{
			$search_image = strpos(base_url(), $first_img);
			$fix_image = substr($first_img, $search_image);	  	
		}

		$oldpath = changeimageurl($fix_image);


		if(file_exists($oldpath)){			
		}
		else{
			$fix_image = base_url().'uploads/no_photo.jpg';
		}


		return $fix_image;
	}
	
	function changeimageurl($url){
		// http://www.lokadwiartara.web-id.co/uploads/2015/Jun/18/2/pdiamon1_192x192.jpg
		// http://www.tarifashionable.kaffah.biz/upload/user/anita_sari67@yahoo.com/18-Apr-2013/1331-SNSD-CROSSWORD_@37_kaos_LD_90-100cm.jpg
		global $SConfig;
		$image = str_replace('http://','',$url);
		$imgexp = explode('/',$image);
		$os = $SConfig->os;
		

		if($os == 'windows'){
			if(count($imgexp) > 5){
				@$oldfile = 'c:\\xampp\\htdocs\\kaffahbiz\\'.$imgexp[1].'\\'.$imgexp[2].'\\'.$imgexp[3].'\\'.$imgexp[4].'\\'.$imgexp[5].'\\'.$imgexp[6];
			}
			else{
				@$oldfile = 'c:\\xampp\\htdocs\\kaffahbiz\\'.$imgexp[1].'\\'.$imgexp[2].'\\'.$imgexp[3].'\\'.$imgexp[4].'\\'.$imgexp[5];
			}
		}
		else{
			if(count($imgexp) > 5){
				@$oldfile = '/var/www/kaffahbiz/'.$imgexp[1].'/'.$imgexp[2].'/'.$imgexp[3].'/'.$imgexp[4].'/'.$imgexp[5].'/'.$imgexp[6];
			}
			else{
				@$oldfile = '/var/www/kaffahbiz/'.$imgexp[1].'/'.$imgexp[2].'/'.$imgexp[3].'/'.$imgexp[4].'/'.$imgexp[5];
			}			
		}
				
		return $oldfile;
	}


	function resize_image($image=NULL, $width=NULL, $height=NULL, $type=NULL){
		global $SConfig;
		$_this =& get_instance();
		$_this->load->library('image_lib'); 
		
		/* definite globalvar */
		$docroot = $SConfig->docroot;
		$siteurl = $SConfig->siteurl;
		
		/* jika kosong maka jadikan nilai default */
		(!empty($width)) ? $width_image = $width : $width_image = 75;
		(!empty($height)) ? $height_image = $height : $height_image = 50;
		
		/* directory replace */
		$http_replace = str_replace('http://', '', $image);
		
		/* get domain name */
		$domain = substr($http_replace, 0, strpos($http_replace, '/'));

		/* search '/' */
		$slash_search = strpos($http_replace, '/');
		
		/* change path to directory */
		$directory = $docroot.substr($http_replace, $slash_search);
		
		/* change files name to new name */
		$get_latest_slash = strrpos($directory, '/');
		$file_name = substr($directory,	$get_latest_slash+1 );
		$extension = substr($file_name, strrpos($file_name, '.'));
		$file_name_without_ext = substr($directory,	$get_latest_slash+1, strrpos($file_name, '.') );
		$new_name = $file_name_without_ext.'_'.$width_image.'x'.$height_image.$extension;
		
		/* path baru */
		$new_path = str_replace($file_name, $new_name, $directory);
		
		/* new url */
		$new_url = str_replace($docroot,'http://'.$domain, $new_path);
		
		$file_is_exist = read_file($new_path);

        if($image == '0' || $image == 'ERROR!'){
                return base_url().'uploads/no_photo.jpg';
        }


		
		if($file_is_exist == TRUE){
			
			return $new_url;
		}
		
		/* else if(empty($file_is_exist)){
			if($type=='people'){
				return $SConfig->df_people;
			}
			else{
				return $SConfig->df_product;
			}
			
		}*/
		
		else{
			/* configuration */
			$config['image_library'] = 'gd2';
			$config['source_image']	= $directory;
			$config['create_thumb'] = TRUE;
			$config['thumb_marker'] = '';
			$config['maintain_ratio'] = TRUE;
			
			if(read_file($config['source_image'])){
				$img_size = getimagesize($config['source_image']);
				@$t_ratio = @$width/$height;
		      	$o_width = $img_size[0];
		      	$o_height = $img_size[1];
			
				if ((!empty($img_size)) && ($t_ratio > $o_width/$o_height)){
					$config['width'] = $width;
					$config['height'] = round( $width * ($o_height / $o_width));
					$y_axis = round(($config['height']/2) - ($height/2));
					$x_axis = 0;
				}
				else{
					$config['width'] = round( $height * ($o_width / $o_height));
					$config['height'] = $height;
					$y_axis = 0;
					$x_axis = round(($config['width']/2) - ($width/2));
				}				
			}
			
			else{
					$config['width'] = $width;
					$config['height'] = $height;
					$y_axis = 0;
					$x_axis = round(($config['width']/2) - ($width/2));				
			}

	  		
			$config['new_image'] = $new_path;
			
			/* load library image */
			$_this->image_lib->clear();
			$_this->image_lib->initialize($config);
			
			/* jika tidak ada masalah maka lakukan resize */
			$_this->image_lib->resize();
			
			$source_img01 = $config['new_image'];
			$config['image_library'] = 'gd2';
			$config['source_image'] = $source_img01;
			$config['create_thumb'] = false;
			$config['maintain_ratio'] = false;
			$config['width'] = $width;
			$config['height'] = $height;
			$config['y_axis'] = $y_axis ;
			$config['x_axis'] = $x_axis ;
			
			$_this->image_lib->clear();
			$_this->image_lib->initialize($config);
			$_this->image_lib->crop();
			
	
			/* return value */			
		}

		return $new_url;
	}


	/* aditional helper for kaffahbiz front public */
	function kaffah_init(){
		$display = "<div id=\"fb-root\"></div>
					<script>(function(d, s, id) {
					  var js, fjs = d.getElementsByTagName(s)[0];
					  if (d.getElementById(id)) return;
					  js = d.createElement(s); js.id = id;
					  js.src = \"//connect.facebook.net/en_US/all.js#xfbml=1\";
					  fjs.comment_parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));</script>";

		return $display;
		
	}


	function nofollow($content) {
	    return preg_replace_callback('/<a[^>]+/', 'nofollow_callback', $content);
	}

	function nofollow_callback($matches) {
	    $link = $matches[0];
	    $site_link = base_url();

	    if (strpos($link, 'rel') === false) {
	        $link = preg_replace("%(href=\S(?!$site_link))%i", 'rel="external nofollow" $1', $link);
	    } elseif (preg_match("%href=\S(?!$site_link)%i", $link)) {
	        $link = preg_replace('/rel=\S(?!nofollow)\S*/i', 'rel="external nofollow"', $link);
	    }
	    return $link;
	}


?>