<?php

	$displayfron = NULL;

	function getpage(){
		$_this =& get_instance();
		$_this->load->model('Post_model');
		$page = $_this->Post_model->get_post('page', 'publish', $_this->main->getUser('dom_id'));
		
		$display['-'] = 'Pilih halaman';

		foreach($page as $row){
			$display[$row['post_name']] = $row['post_title'];
		}



		return $display;
	}

	function recursiveFrontPage($array,$typemenu=NULL) {
		global $displayfron;

		/* typemenu = li_drop */
		if($typemenu == 'li'){	
			$displayfron .= '<ul class="page_menu dropdown-menu" role="menu">';			
		}
		else{
			$displayfron .= '<ul class="page_menu">';
		}


		
	    foreach ($array as $child) { 
	        
	        if($typemenu == 'li'){
		        if(!empty($child['children'])){
		        	$displayfron .= '<li class="'.$child['post_name'].' dropdown"><a href="'.base_url('halaman/'.$child['post_name']).'" class="dropdown-toggle" data-toggle="dropdown">'.$child['post_title'].'</a>';
		        }
		        else{
		        	$displayfron .= '<li class="'.$child['post_name'].'"><a href="'.base_url('halaman/'.$child['post_name']).'">'.$child['post_title'].'</a>';	
		        }
	        }
	        else{
		        if(!empty($child['children'])){
		        	$displayfron .= '<li class="'.$child['post_name'].'"><a href="'.base_url('halaman/'.$child['post_name']).'">'.$child['post_title'].'</a>';
		        }
		        else{
		        	$displayfron .= '<li class="'.$child['post_name'].'"><a href="'.base_url('halaman/'.$child['post_name']).'">'.$child['post_title'].'</a>';	
		        }
	        }
	        


	        recursiveFrontPage($child['children'],$typemenu);
	        $displayfron .= '</li>'."\n";
	    }
		
		$displayfron .= '</ul>';

		/* typemenu = li_drop */
		if($typemenu == 'li'){				
			$displayfix = str_replace('<ul class="page_menu dropdown-menu" role="menu"></ul>', '', $displayfron);	
			return substr($displayfix, 48,-5);	
		}
		else{
			$displayfix = str_replace('<ul class="page_menu"></ul>', '', $displayfron);
			return substr($displayfix, 22,-5);
		}		 
	}

	/*
	function recursiveFrontPage($array) {
		global $displayfron;
		$displayfron .= '<ul class="page_menu dropdown-menu" role="menu">';
	    foreach ($array as $child) { 
	        
	        
	        if(!empty($child['children'])){
	        	$displayfron .= '<li class="'.$child['post_name'].' dropdown"><a href="'.base_url('halaman/'.$child['post_name']).'" class="dropdown-toggle" data-toggle="dropdown">'.$child['post_title'].'</a>';
	        }
	        else{
	        	$displayfron .= '<li class="'.$child['post_name'].'"><a href="'.base_url('halaman/'.$child['post_name']).'">'.$child['post_title'].'</a>';	
	        }

	        recursiveFrontPage($child['children']);
	        $displayfron .= '</li>'."\n";
	    }
		
		$displayfron .= '</ul>';
		$displayfix = str_replace('<ul class="page_menu dropdown-menu" role="menu"></ul>', '', $displayfron);
		
		 return substr($displayfix, 48,-5);
	}
	*/
	
	function recursivePrintPage($array) {
		global $display;

		$draft = '';
		$display .= '<ol class="limited_drop_targets vertical">';
	    foreach ($array as $child) { 
			if($child['post_status'] == 'draft'){
				$draft = '<em class="draft">(draft)</em>';
			}
			else{
				$draft = '';
			}
	        $display .= '<li id="'.$child['post_name'].'_'.$child['ID'].'_'.$child['post_parent'].'" class="'.$child['post_name'].'"><a class="linktoedit" href="/site/member/'.$child['blog_id'].'/full/'.$child['post_name'].'/#editpage">'.$child['post_title'].' '.$draft.'</a>';
			$display .= '<div class="catedit">
				<a class="edit_page" name="'.$child['post_name'].'">Edit</a> | <a class="del_page" name="'.$child['ID'].'" title="'.$child['post_name'].'">Hapus</a>
			</div>';
	        recursivePrintPage($child['children']);
	        $display .= '</li>';
	    }
		
		$display .= '</ol>';
		$displayfix = str_replace('<ol class="limited_drop_targets vertical"></ol>', '', $display);
		$fix = str_replace(' class="limited_drop_targets vertical"','',$displayfix);
		
		return substr($fix, 4, -5);
	}
	
	function reListCatPage($page){
		/* echo '<pre>'; print_r($postcategory);	echo '</pre>'; */
		$max = count($page);
		$tree = array();
		$flat = array();
		
		for ($i = 0; $i < $max; $i++) {
	        $n = $page[$i];
	        $id = $n['ID'];
	        $flat[$id] = $n;
		}
		
		// Then check all those entries by reference
		foreach ($flat as $key => &$child) {
	        // Add a children array if not already existing
	        if (!isset($child['children'])){
				$child['children'] = array();
			}

	        $id = $child['ID'];
	        $pid = $child['post_parent'];

	        // If childs parent id is larger then zero
	        if ($pid > 0) {
	            // Append it by reference, which means it will reference
	            // the same object across different carriers
	            $flat[$pid]['children'][] = &$child;
	        } else {
	            // Otherwise it is zero level, which initiates the tree
	            $tree[$id] = &$child;
	        }
		}

		$tree = array_values($tree); // Indices fixed, there we go, use $tree further
		
		return $tree;
	}
?>