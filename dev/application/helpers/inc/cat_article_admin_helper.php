<?php
	
	$displayproduct = NULL;
	$displayproductli = NULL;
	$displayli = NULL;
	$display = NULL;
	$arrayid = NULL;
	$arrayidli = NULL;

	function get_all_cat(){
		$_this =& get_instance();
		$blogIdfromSession = $_this->session->userdata('blogid');
	}
	
	
	function menuCategoryLi($array,$type=NULL,$module=NULL,$typemenu=NULL) {		
		global $arrayidli;

		if($module == TRUE){			
			$class = '_module';
		}
		else{
			$class = '';
		}



		if($type=='product'){
								

			/* typemenu = li_drop */
			if($typemenu == 'li'){	
				global $displayproductli;		
				$displayproductli .= '<ul class="category_menu'.$class.' dropdown-menu">';	
			}
			else{
				global $displayproduct;	
				$displayproduct .= '<ul class="category_menu'.$class.'">';
			}


			
		    foreach ($array as $child) { 
		    	if(@array_key_exists($child['term_id'], $arrayidli)){

		    	}

		    	else{		

					if($typemenu == 'li'){

						if(!empty($child['children'])){

							$displayproductli .= '<li class="'.$child['slug'].' dropdown"><a class="linkp dropdown-toggle" data-hover="dropdown" data-toggle="dropdown" href="'.base_url().'produk/'.$child['slug'].'">'.$child['name'].'</a>';
						

						}
						else{
							$displayproductli .= '<li class="'.$child['slug'].'"><a class="linkp" href="'.base_url().'produk/'.$child['slug'].'">'.$child['name'].'</a>';
						}

						
					}
					else{
						$displayproduct .= '<li class="'.$child['slug'].'"><a class="linkp" href="'.base_url().'produk/'.$child['slug'].'">'.$child['name'].'</a>';
					}
			        
			        menuCategoryLi($child['children'],$type,$module,$typemenu);

			        if($typemenu == 'li'){
			        	$displayproductli .= '</li>'."\n";
			        }

			        else{
			        	$displayproduct .= '</li>'."\n";	
			        }
			        
		    	}

		        $arrayidli[$child['term_id']] = $child['term_id'];		        
		    }
			
			if($typemenu == 'li'){	
				$displayproductli .= '</ul>';								
				$displayfix = str_replace('<ul class="category_menu'.$class.' dropdown-menu"></ul>', '', $displayproductli);
			}
			else{	
				$displayproduct .= '</ul>';			
				$displayfix = str_replace('<ul class="category_menu'.$class.'"></ul>', '', $displayproduct);
			}

		}

		else{

			

			

			if($typemenu == 'li'){		
				global $displayartikelli;

				$displayartikelli .= '<ul class="category_menu'.$class.' dropdown-menu">';	
			}
			else{
				global $displayartikel;
				$displayartikel .= '<ul class="category_menu'.$class.'">';
			}

			
		    foreach ($array as $child) { 
		    	if(@array_key_exists($child['term_id'], $arrayid)){

		    	}

					if($typemenu == 'li'){

						if(!empty($child['children'])){
							$displayartikelli .= '<li class="'.$child['slug'].' dropdown"><a class="linkp dropdown-toggle" data-hover="dropdown" data-toggle="dropdown" href="'.base_url().'artikel/'.$child['slug'].'">'.$child['name'].'</a>';
						}
						else{
							$displayartikelli .= '<li class="'.$child['slug'].'"><a class="linkp" href="'.base_url().'artikel/'.$child['slug'].'">'.$child['name'].'</a>';
						}

						
					}
					else{
						$displayartikel .= '<li class="'.$child['slug'].'"><a class="linkp" href="'.base_url().'artikel/'.$child['slug'].'">'.$child['name'].'</a>';
					}
			        
			        menuCategoryLi($child['children'],$type,$module,$typemenu);

			        if($typemenu == 'li'){
			        	$displayartikelli .= '</li>'."\n";
			        }

			        else{
			        	$displayartikel .= '</li>'."\n";	
			        }

		        $arrayid[$child['term_id']] = $child['term_id'];
		    }
			
			if($typemenu == 'li'){	
				$displayartikelli .= '</ul>';								
				$displayfix = str_replace('<ul class="category_menu'.$class.' dropdown-menu"></ul>', '', $displayartikelli);
				$displayfix = str_replace('<ul class="category_menu"></ul>', '', $displayartikelli);
			}
			else{	
				$displayartikel .= '</ul>';
				$displayfix = str_replace('<ul class="category_menu'.$class.'"></ul>', '', $displayartikel);
				$displayfix = str_replace('<ul class="category_menu"></ul>', '', $displayartikel);	
			}

			
			

			
		}
							
		return $displayfix;
	}

	function menuCategory($array,$type=NULL,$module=NULL) {		
		global $arrayid;

		if($module == TRUE){			
			$class = '_module';
		}
		else{
			$class = '';
		}

		

		if($type=='product'){
			global $displayproduct;			
			$displayproduct .= '<ul class="category_menu'.$class.'">';
		    foreach ($array as $child) { 
		    	if(@array_key_exists($child['term_id'], $arrayid)){

		    	}

		    	else{		    	
			        $displayproduct .= '<li class="'.$child['slug'].'"><a class="linkp" href="'.base_url().'produk/'.$child['slug'].'">'.$child['name'].'</a>';
			        menuCategory($child['children'],$type,$module);
			        $displayproduct .= '</li>'."\n";
		    	}

		        $arrayid[$child['term_id']] = $child['term_id'];		        
		    }
			
			$displayproduct .= '</ul>';			
			$displayfix = str_replace('<ul class="category_menu"></ul>', '', $displayproduct);
		}

		else{
			global $display;
			$display .= '<ul class="category_menu'.$class.'">';
		    foreach ($array as $child) { 
		    	if(@array_key_exists($child['term_id'], $arrayid)){

		    	}

		    	else{
	 				$display .= '<li class="'.$child['slug'].'"><a class="linkp" href="'.base_url().'artikel/'.$child['slug'].'">'.$child['name'].'</a>';
			        menuCategory($child['children'],$type,$module);
			        $display .= '</li>'."\n";		    		
		    	}

		        $arrayid[$child['term_id']] = $child['term_id'];
		    }
			
			$display .= '</ul>';
			$displayfix = str_replace('<ul class="category_menu"></ul>', '', $display);	
			$displayfix = str_replace('<ul class="category_menu_module"></ul>', '', $displayfix);


		}
							
		return $displayfix;
	}




	function recursivePrint($array,$type=NULL) {
		global $display;
		if($type=='category_product'){
			$class = 'edit_product';
		}
		else{
			$class = 'edit';
		}		

		$display .= '<ol class="limited_drop_targets vertical">';
	    foreach ($array as $child) { 
	        $display .= '<li id="'.@$child['slug'].'_'.@$child['term_id'].'_'.@$child['parent'].'" class="'.@$child['slug'].'">'.@$child['name'];
			$display .= '<div class="catedit">
				<a class="'.$class.'" name="'.@$child['slug'].'">Edit</a> | <a class="del" name="'.@$child['slug'].'">Hapus</a>
			</div>';
	        recursivePrint($child['children'],$type);
	        $display .= '</li>';
	    }
		
		$display .= '</ol>';
		$displayfix = str_replace('<ol class="limited_drop_targets vertical"></ol>', '', $display);
		$fix = str_replace(' class="limited_drop_targets vertical"','',$displayfix);
		
		return substr($fix, 4, -5);
	}
	
	function reListCat($postcategory){
		/* echo '<pre>'; print_r($postcategory);	echo '</pre>'; */
		$max = count($postcategory);
		$tree = array();
		$flat = array();
		
		for ($i = 0; $i < $max; $i++) {
	        $n = $postcategory[$i];
	        $id = $n['term_id'];
	        $flat[$id] = $n;
		}
		
		// Then check all those entries by reference
		foreach ($flat as $key => &$child) {
	        // Add a children array if not already existing
	        if (!isset($child['children'])){
				$child['children'] = array();
			}

	        $id = @$child['term_id'];
	        $pid = @$child['parent'];

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