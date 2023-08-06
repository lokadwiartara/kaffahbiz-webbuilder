<?php

	require("phpwhois/whois.main.php");

	function str_contains($haystack, $needle, $ignoreCase = false) {
	    if ($ignoreCase) {
	        $haystack = strtolower($haystack);
	        $needle   = strtolower($needle);
	    }
	    $needlePos = strpos($haystack, $needle);
	    return ($needlePos === false ? false : ($needlePos+1));
	}

	function k_reg_menu_login(){
		global $SConfig;
		$_this =& get_instance();
		$display = NULL;
		
		if($_this->main->allAtr->domain_name == $SConfig->domain){
			if($_this->main->getUser('logged_in')){

				$display = '<a id="accounta" href="dashboard.html"><em class="picpro"></em></a>
						<div class="login">
							<ul class="user">
								<li><a title="Dashboard Admin" href="'.base_url().'site/member/'.$_this->session->userdata('dom_id').'/#dashboard">Dashboard Admin</a></li>
								<li><a title="Setting & Verifikasi" href="'.base_url().'site/member/'.$_this->session->userdata('dom_id').'/#setting_verify">Setting Akun & Verifikasi</a></li>
								<li><a title="Klik Untuk LogOut!" href="'.base_url().'logout">LogOut!</a></li>
							</ul>
						</div>';

			}
			else{
				$display = '<a href="'.base_url().'login" class="sstrong">Login!</a>';
			}
		}							

		return $display;
	}

	function validateRegex($input){
		if (preg_match('/[a-z]+[0-9]+/', $input)){
			return true; 
		}
		else{
			return false;
		}
	}

	function validatePhone($input){
		if(preg_match('/^([0][0-9]*)$/', $input)){
			return true; 
		}
		else{
			return false;
		}
	}

	function validateAlphaNum($input){		
		if(preg_match('/^(([a-zA-Z]{1})|([a-zA-Z]{1}[a-zA-Z]{1})|([a-zA-Z]{1}[0-9]{1})|([0-9]{1}[a-zA-Z]{1})|([a-zA-Z0-9][a-zA-Z0-9-_]{1,61}[a-zA-Z0-9]))\.([a-zA-Z]{2,6}|[a-zA-Z0-9-]{2,30}\.[a-zA-Z]{2,3})$/', $input)){
			return true; 
		}
		else{
			return false;
		}
	}

	function reg_member_list(){
		$_this =& get_instance();
		$_this->load->library('Coredb');
		return $_this->coredb->count_table_row(array() ,'kp_domain');
	}

	function reg_akun_list(){
		$_this =& get_instance();
		$_this->load->library('Coredb');
		return $_this->coredb->count_table_row(array() ,'kp_users');		
	}

	function post_count(){
		$_this =& get_instance();
		$_this->load->library('Coredb');
		return $_this->coredb->count_table_row(array() ,'kp_posts');		
	}	

	function get_new_product($limit=NULL){
		$_this =& get_instance();		
		(!empty($limit)) ? $limitfix = $limit : $limitfix = 5;
		$date = date('Y-m-d');

		$newproduct = $_this->db->query("SELECT * 
											FROM kp_posts
											WHERE kp_posts.post_title NOT LIKE '%nama produk%' AND
											  kp_posts.post_status = 'publish' AND DATE(kp_posts.post_date) < '$date'
											  	AND kp_posts.post_price <> '0' AND kp_posts.post_image <> ''
												AND kp_posts.post_moderation = '1'	AND kp_posts.post_market_category <> ''
										GROUP BY
											  kp_posts.ID
											  ORDER BY  kp_posts.ID DESC
										LIMIT 0,".$limitfix."");
		
		foreach($newproduct->result() as $row){
			$record[] = $row;
		}
		
		$newproduct->free_result();
		return $record;		
	}

	function k_money_format($currency=NULL, $value=NULL){
		@(!empty($value)) ? $value = @number_format($value, 0,0,'.' ) : $value = '';
		@(!empty($currency)) ? $dispmoney = $currency." ".$value : $dispmoney = $value;
		return $dispmoney;
	}	

	function get_new_update($limit=NULL){
		global $SConfig;
		$_this =& get_instance();


	
		
		if(($_this->main->allAtr->domain_name == $SConfig->domain) || ($_this->main->allAtr->domain_name == $SConfig->store)){

			$blog_id = $_this->main->allAtr->domain_id;
			$_this->load->helper('date');
			$display = NULL;
			(!empty($limit)) ? $limitfix = $limit : $limitfix = 3;
			$newupdate = $_this->db->query("SELECT post_name,post_title,post_date,post_category
										FROM kp_posts
										WHERE kp_posts.post_type = 'post' AND kp_posts.blog_id = '$blog_id'
										AND kp_posts.post_status = 'publish'
										  ORDER BY  kp_posts.ID DESC
									LIMIT 0,".$limitfix."");
			
			foreach($newupdate->result() as $row){
				$record[] = $row;
			}

			if(!empty($record)){
				foreach($record as $row){
					$cat = explode(',',$row->post_category);			
					$display .= '<span><a href="'.base_url().'artikel/'.$cat[0].'/'.$row->post_name.'">'.$row->post_title.'</a></span>
					<p>'.mdate('%d/%M/%Y',strtotime($row->post_date)).'</p>';
						
				}
			}


			return $display;
		}				
	}

	function get_new_domain($limit=NULL){
		global $SConfig;
		$_this =& get_instance();
		$display = NULL;
		if(($_this->main->allAtr->domain_name == $SConfig->domain) || ($_this->main->allAtr->domain_name == $SConfig->store)){
			$blog_id = $_this->main->allAtr->domain_id;
			(!empty($limit)) ? $limitfix = $limit : $limitfix = 3;
			$newdomain = $_this->db->query("SELECT domain_name,domain_title
										FROM kp_domain
										WHERE kp_domain.domain_verify = '1' AND kp_domain.domain_status = '1'
										  ORDER BY  kp_domain.domain_id DESC
									LIMIT 0,".$limitfix."");
			
			
			

			foreach($newdomain->result() as $row){
				$record[] = $row;
			}

			foreach($record as $row){
				$display .= '<span><a href="http://'.$row->domain_name.'" target="_blank" class="sitename">'.str_replace('www.','',$row->domain_name).'</a></span><p>'.$row->domain_title.'</p>';
			}
		}
		

		return $display;
	}

	function title(){
		global $SConfig;
		$_this =& get_instance();
		$display = NULL;

		if($_this->main->allAtr->domain_name == $SConfig->domain){
			$display = $_this->template->kaffahTitle();
		}

		return $display;
	}

	/* DIRECTORY */
	function get_dir_post($type,$limit=NULL,$offset=NULL){
		global $SConfig;
		$_this =& get_instance();		
		(!empty($limit)) ? $limitfix = $limit : $limitfix = $SConfig->limit_dir;
		$date = date('Y-m-d');

		if(($_this->uri->segment(3) == 'h') && ($_this->uri->total_segments() > 3)){
			$offset = (int) $_this->uri->segment(4);
		}
		else{
			$offset = 0;
		}

		$newpost = $_this->db->query("SELECT `kp_posts`.`post_content`,`kp_posts`.`ID`,`kp_posts`.`post_name`,`kp_posts`.`post_title`,`kp_posts`.`post_image`,`kp_posts`.`post_price`,
										`kp_posts`.`post_date`,`kp_posts`.`post_counter`,`kp_posts`.`comment_count`,`kp_domain`.`domain_name`
											FROM `kp_posts`
											LEFT JOIN `kp_domain` ON `kp_posts`.`blog_id` = `kp_domain`.`domain_id`
											WHERE `kp_posts`.`post_type` = '$type' and `kp_posts`.`post_status` = 'publish' and char_length(`kp_posts`.`post_content`) > 50
											ORDER BY  `kp_posts`.`ID` DESC
										LIMIT $offset,".$limitfix);				

		if($newpost->num_rows() > 0){
			foreach($newpost->result() as $row){
				$record[] = $row;
			}
		}
		else{
			$record = null;
		}

		
		$newpost->free_result();
		return $record;		
	}

	function get_dir_related_post($type,$category,$except){
		global $SConfig;
		$_this =& get_instance();	

		$expcategory = explode(',',$category);
		if(count($expcategory) > 1){
			$cat = $expcategory[0];
		}
		else{
			$cat = $category;
		}

		$newpost = $_this->db->query("SELECT `kp_posts`.`post_content`,`kp_posts`.`ID`,`kp_posts`.`post_name`,`kp_posts`.`post_title`,`kp_posts`.`post_image`,`kp_posts`.`post_price`,
										`kp_posts`.`post_date`,`kp_posts`.`post_counter`,`kp_posts`.`comment_count`,`kp_domain`.`domain_name`
											FROM `kp_posts`
											LEFT JOIN `kp_domain` ON `kp_posts`.`blog_id` = `kp_domain`.`domain_id`
											WHERE `kp_posts`.`post_type` = '$type' and `kp_posts`.`post_status` = 'publish' and char_length(`kp_posts`.`post_content`) > 50
											and `kp_posts`.`post_category` LIKE '%$cat%' and `kp_posts`.`ID` <> '$except'
											ORDER BY  `kp_posts`.`ID` DESC
										LIMIT 0,4");	
		if($newpost->num_rows() > 0){
			foreach($newpost->result() as $row){
				$record[] = $row;
			}
		}
		else{
			$record = null;
		}

		
		$newpost->free_result();
		return $record;		
	}

	function paging_dir($type){
		global $SConfig;
		$_this =& get_instance();		
		$_this->load->library('pagination');
		$_this->load->library('coredb');



		/* ambil total record dari table post row */				
		$totalrow = $_this->coredb->count_table_row(array('kp_posts.post_type' => $type, 'char_length(kp_posts.post_content) >' => 50, 'kp_posts.post_status' => 'publish' ),'kp_posts');								
		
		if($type == 'post'){
			$page = 'artikel';
		}
		else if($type == 'product'){
			$page = 'produk';
		}

		$config = array(
					'base_url' => base_url().'dir/'.$page.'/h',
					'total_rows' => $totalrow,
					'per_page' => $SConfig->limit_dir,
					'uri_segment' => 4,
					'use_page_numbers' => false,
					'full_tag_open' => '<div class="pagination">',
					'full_tag_close' => '</div>'
				);			

		$_this->pagination->initialize($config); 
		return $_this->pagination->create_links();		
	}

	function get_detail_post(){
		global $SConfig;
		$_this =& get_instance();		

		$id = 0;

		if($_this->uri->segment(3) == 'id'){
			$id = (int) $_this->uri->segment(4);
		}

		$query = $_this->db->query("SELECT `kp_posts`.`post_category`,`kp_posts`.`post_content`,`kp_posts`.`ID`,`kp_posts`.`post_name`,`kp_posts`.`post_title`,`kp_posts`.`post_image`,`kp_posts`.`post_price`,
										`kp_posts`.`post_date`,`kp_posts`.`post_counter`,`kp_posts`.`comment_count`,`kp_domain`.`domain_name`,`kp_domain`.`domain_desc`
											FROM `kp_posts`
											LEFT JOIN `kp_domain` ON `kp_posts`.`blog_id` = `kp_domain`.`domain_id`
											WHERE `kp_posts`.`ID` = '$id' ");				

		$row = $query->row();

		$_this->template->title =  $SConfig->dirname . ' Kentut';
		return $row;			
	}

?>
