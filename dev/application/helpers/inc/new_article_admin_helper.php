<?php
	/* helper ini digunakan untuk menyediakan kebutuhan article_admin */
	
	/* fungsi ini digunakan untuk menampilkan category */
	function listcat($type,$blogid){
		$_this =& get_instance();
				
		$_this->load->model('Post_model');
		$postcategory = reListCat($_this->Post_model->get_all_cat($type,NULL,$blogid));
		$tree = reListCat($postcategory);
		
		return recursivelistcat($tree);
	}
	
	/* fungsi ini digunakan untuk membuat daftar category dalam li */
	function recursivelistcat($array){
		global $display;
		$display .= '<ul style="margin:0 0 0 28px;" class="category">';
	    foreach ($array as $child) {
	        $display .= '<li class="'.$child['slug'].'">';
			$display .= '<input class="checkcat" type="checkbox" name="category[]" id="cat'.$child['term_id'].'" value="'.$child['slug'].'" /><label for="cat'.$child['term_id'].'">'.$child['name'].'<em class="hidden">,</em></label>';
	        recursivelistcat($child['children']);
	        $display .= '</li>';
	    }
		
		$display .= '</ul>';
		$displayfix = str_replace('<ul class="category"></ul>', '', $display);
		$fix = str_replace(' class="category"','',$displayfix);
		
		return substr($fix, 31, -5);		
	}
	
	/* fungsi ini digunakan untuk mengecekan keberadaan 
	   apakah ada title yang sama atau tidak */
	function checkarticle($judul,$blogid){
		$_this =& get_instance();
		$_this->load->model('Post_model');
		return $_this->Post_model->articletitle_is_exist($judul,$blogid);
	}
?>