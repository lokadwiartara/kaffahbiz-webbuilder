<?php
$display_comment = NULL;

	function display_commentlist($array,$depth){
		global $display_comment ;

		$display_comment .= '<ul class="children">';
	    foreach ($array as $child) { 
	        $display_comment .= '<li class="depth-'.$depth.'">';
			$display_comment .= '<div class="comment-info">
									<img alt="" src="http://www.kaffah.biz/template/front/coolblue/images/gravatar.jpg" class="avatar" height="42" width="42" />
									<cite>
										<a href="'.$child['comment_author_url'].' class="author">'.$child['comment_author_name'].'</a> <kaffah_e name="says" type="text" value="Mengatakan" />: <br />
										<span class="comment-data">'.mdate('%d/%m/%Y - %h:%i', strtotime($child['comment_date'])).'</span>
									</cite>
								</div>

								<div class="comment-text">
									<p>'.$child['comment_content'].'</p>

									<!--<div class="reply">
										<a rel="nofollow" class="comment-reply-link" href="index.html"><kaffah_e name="reply" type="text" value="Balas" /></a>
			 						</div>-->
								</div>';

	        display_commentlist($child['children'],$depth+1);

	        $display_comment .= '</li>';
	    }
		
		$display_comment .= '</ul>';
		$display_comment = str_replace('<ul class="children"></ul>', '', $display_comment);		
		
		return $display_comment;
	}

	function sort_array_comment($arraycomment){
		/* echo '<pre>'; print_r($arraycomment);	echo '</pre>'; */
		$max = count($arraycomment);
		$tree = array();
		$flat = array();
		
		for ($i = 0; $i < $max; $i++) {
	        $n = $arraycomment[$i];
	        $id = $n['comment_ID'];
	        $flat[$id] = $n;
		}
		
		// Then check all those entries by reference
		foreach ($flat as $key => &$child) {
	        // Add a children array if not already existing
	        if (!isset($child['children'])){
				$child['children'] = array();
			}

	        $id = $child['comment_ID'];
	        $pid = $child['comment_parent'];

	        // If childs comment_parent id is larger then zero
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