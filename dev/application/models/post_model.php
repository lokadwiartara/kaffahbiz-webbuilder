<?php

class Post_model extends CI_Model{

    function __construct(){
        parent::__construct();
    }		
	
	/* ambil semua kategori yang ada */
	function get_all_cat($type,$userid=NULL,$blogid,$slug=NULL){
		$this->db->select('*')
				 ->where('term_type',$type)
				 ->where('term_blog_id',$blogid)				 
				 ->from('kp_terms')
				 ->order_by('sort','ASC');

		if(!empty($userid)){
			$this->db->where('term_user_id',$userid);
		}		
		
		if(!empty($slug)){
			$this->db->where_not_in('slug',$slug);
		}

		$query = $this->db->get();
		
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $row){
				$data[] = $row;
			}
			
			$query->free_result();
			
		}
		else{
			$data = NULL;
		}
		
		return $data;		
	}
	
	/* bagian admin */
	function is_cat_exist($type,$slug,$blogid){
		/* 	model ini digunakan untuk melakukan 
			pengecekan apakah kategori yang di cari itu benar ada */
		
		$this->db->from('kp_terms');
		$this->db->where('slug',$slug);
		$this->db->where('term_type',$type);
		$this->db->where('term_user_id',$this->main->getUser('user_id'));
		$this->db->where('term_blog_id',$blogid);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return TRUE;
		}
		
		else{
			return FALSE;
		}
	}
	
	function get_cat($type,$slug,$blogid){
		/* dapatkan sesuai slug kategori yang di cari */
		$this->db->from('kp_terms');
		$this->db->where('slug',$slug);
		$this->db->where('term_type',$type);
		$this->db->where('term_user_id',$this->main->getUser('user_id'));
		$this->db->where('term_blog_id',$blogid);
		$query = $this->db->get();
		$row = $query->row_array();
		return $row;	
	}
	
	function articletitle_is_exist($title,$blogid){
		$this->db->where('post_title',$title);
		$this->db->where('blog_id',$blogid);
		$this->db->from('kp_posts');
		return $this->db->count_all_results();
	}

	function articleparent_is_exist($parent,$blogid){
		$this->db->where('post_parent',$parent);
		$this->db->where('blog_id',$blogid);
		$this->db->from('kp_posts');
		return $this->db->count_all_results();
	}


	function get_all_product_parent($blogid){
		$this->db->select('post_parent');
						  
		$this->db->from('kp_posts');		
		$this->db->where('kp_posts.blog_id',$blogid);
		$this->db->where('kp_posts.post_type','product');
		
		
		$query = $this->db->get();
		
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $row){
				$data[] = $row;
			}
			
			$query->free_result();
			
		}
		else{
			$data = NULL;
		}
		
		return $data;		
	}


	function get_all_article($type,$status,$blogid,$limit,$offset=NULL,$mediafilter=NULL,$contentfilter=NULL){
		$this->db->select('kp_users.name, kp_posts.post_code,kp_posts.ID, kp_posts.post_author, kp_posts.post_date,
						  kp_posts.post_title, kp_posts.post_parent, kp_posts.post_status, kp_posts.post_name,
						  kp_posts.post_category, kp_posts.post_price, kp_posts.post_image, kp_posts.post_counter, kp_posts.comment_count,
						  kp_posts.post_stock,kp_posts.post_reseller,kp_posts.post_basic,kp_posts.post_reseller_fee,,kp_posts.post_ks_fee,
						  kp_posts.blog_id');
						  
		$this->db->from('kp_users');
		$this->db->where('kp_posts.post_type',$type);
		$this->db->where('kp_posts.blog_id',$blogid);
		$this->db->where('kp_posts.post_author',$this->main->getUser('user_id'));
		$this->db->join('kp_posts','kp_posts.post_author = kp_users.id', 'LEFT');
		
		/* jika yang di akses itu bertipe artikel maka */
		if($type == 'post' || $type == 'product'){
			$this->db->order_by('kp_posts.ID', 'DESC');	
		}
		else if($type == 'page'){
			$this->db->order_by('kp_posts.menu_order', 'ASC');	
		}
		
		
		if(!empty($mediafilter)){
			if($mediafilter == 's'){
				$this->db->like('kp_posts.post_title',$contentfilter);
				$this->db->like('kp_posts.post_content',$contentfilter);

				if($type=='product'){
					$this->db->or_like('kp_posts.post_code',$contentfilter);			
				}
			}
			else if($mediafilter == 'f'){
				$this->db->like('kp_posts.post_category',$contentfilter);
			}
		}
		
		if(!empty($status) && $status != '-' ){
			$this->db->where('kp_posts.post_status',$status);
		}
		
		
		if(!empty($limit)){
			$this->db->limit($limit,$offset);
		}
		
		
		$query = $this->db->get();
		
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $row){
				$data[] = $row;
			}
			
			$query->free_result();
			
		}
		else{
			$data = NULL;
		}
		
		return $data;		
	}

	function get_all_article_sa($type,$status=NULL,$limit,$offset=NULL,$mediafilter=NULL,$contentfilter=NULL){

		$select = 'kp_users.name, kp_posts.ID, kp_posts.post_author, kp_posts.post_date,kp_posts.post_code,kp_posts.post_moderation,
						  kp_posts.post_title, kp_posts.post_parent, kp_posts.post_status, kp_posts.post_name, kp_posts.post_market_category,
						  kp_posts.post_category, kp_posts.post_price, kp_posts.post_image, kp_posts.post_counter, kp_posts.comment_count,
						  kp_posts.post_stock,kp_posts.post_market_attribute, kp_posts.post_reseller,kp_posts.post_basic,kp_posts.post_reseller_fee,
						  kp_posts.post_ks_fee,
						  kp_posts.blog_id';


		if($type=='product'){
			$select .= ', sum(kp_trans_detail.quantity) as total_beli';
		}

		$this->db->select($select);	
					
		$this->db->from('kp_users');
		
		$this->db->where('kp_posts.post_type',$type);
		


		$this->db->join('kp_posts','kp_posts.post_author = kp_users.id', 'LEFT');	

		if($type=='product'){
			$this->db->join('kp_trans_detail', '  kp_trans_detail.product_id = kp_posts.ID', 'LEFT');
		}
					
		if(!empty($mediafilter)){
			if($mediafilter == 's'){
				$this->db->like('kp_posts.post_title',$contentfilter);				
				if($type=='product'){
					$this->db->or_like('kp_posts.post_code',$contentfilter);			
				}
			}
			else if($mediafilter == 'sort'){
				switch($contentfilter){
					case 'hot' : $this->db->like('kp_posts.post_market_attribute',$contentfilter); break;
					case 'choice' : $this->db->like('kp_posts.post_market_attribute',$contentfilter);
					case 'cart' : $this->db->order_by('total_beli', 'DESC'); break;
					case 'see' : $this->db->order_by('post_counter', 'DESC'); break;
					case 'comment' : $this->db->order_by('comment_count', 'DESC'); break;
					default: 
						$this->db->order_by('kp_posts.post_date_modified', 'DESC');
						
						/* jika yang di akses itu bertipe artikel maka */
						if($type == 'post' || $type == 'product'){
							$this->db->order_by('kp_posts.ID', 'DESC');	
						}
						else if($type == 'page'){
							$this->db->order_by('kp_posts.menu_order', 'ASC');	
						}
						
					break;
				}
			}
			else if($mediafilter == 'f'){
				$this->db->like('kp_posts.post_market_category',$contentfilter);
			}
		}
		
		if(!empty($status) && $status != '-' && $status != 'null'){
			$this->db->where('kp_posts.post_status',$status);
		}
		
		
		if(!empty($limit)){
			$this->db->limit($limit,$offset);
		}

		if($type=='product'){
			$this->db->group_by('kp_posts.ID');
		}
		
		
		$query = $this->db->get();
		
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $row){
				$data[] = $row;
			}
			
			$query->free_result();
			
		}
		else{
			$data = NULL;
		}
		
		return $data;		
	}

	function get_all_article_reseller($type,$status,$blogid,$limit,$offset=NULL,$mediafilter=NULL,$contentfilter=NULL){
		$this->db->select('kp_users.name, kp_posts.post_code,kp_posts.ID, kp_posts.post_author, kp_posts.post_date,
						  kp_posts.post_title, kp_posts.post_parent, kp_posts.post_status, kp_posts.post_name,
						  kp_posts.post_category, kp_posts.post_price, kp_posts.post_image, kp_posts.post_counter, kp_posts.comment_count,
						  kp_posts.post_stock,kp_posts.post_reseller,kp_posts.post_basic,kp_posts.post_reseller_fee,kp_posts.post_ks_fee,
						  kp_posts.blog_id');
						  
		$this->db->from('kp_users');
		$this->db->where('kp_posts.post_type',$type);
		$this->db->where('kp_posts.post_stock >' ,0);
		$this->db->where('kp_posts.blog_id',$blogid);		
		$this->db->join('kp_posts','kp_posts.post_author = kp_users.id', 'LEFT');
		
		
		if(!empty($mediafilter)){
			if($mediafilter == 's'){
				$this->db->like('kp_posts.post_title',$contentfilter);				

				//if($type=='product'){
					//$this->db->or_like('kp_posts.post_code',$contentfilter);			
				//}
			}

			else if($mediafilter == 'sort'){
				
				switch($contentfilter){

					case 'fee' : $this->db->order_by('kp_posts.post_reseller_fee', 'DESC'); break;
					case 'cheap' : $this->db->order_by('kp_posts.post_price', 'ASC'); break;
					case 'expensive' : $this->db->order_by('kp_posts.post_price', 'DESC'); break;
					default: 
						$this->db->order_by('kp_posts.post_date_modified', 'DESC');
						
						/* jika yang di akses itu bertipe artikel maka */
						if($type == 'post' || $type == 'product'){
							$this->db->order_by('kp_posts.ID', 'DESC');	
						}
						else if($type == 'page'){
							$this->db->order_by('kp_posts.menu_order', 'ASC');	
						}
						
					break;
				}
			}

			else if($mediafilter == 'f'){
				$this->db->like('kp_posts.post_category',$contentfilter);
			}
		}

		/* jika yang di akses itu bertipe artikel maka */
		if($type == 'post' || $type == 'product'){
			$this->db->order_by('kp_posts.ID', 'DESC');	
		}
		else if($type == 'page'){
			$this->db->order_by('kp_posts.menu_order', 'ASC');	
		}
		

		if(!empty($status) && $status != '-' ){
			$this->db->where('kp_posts.post_status',$status);
		}
		
		
		if(!empty($limit)){
			$this->db->limit($limit,$offset);
		}
		
		
		$query = $this->db->get();
		
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $row){
				$data[] = $row;
			}
			
			$query->free_result();
			
		}
		else{
			$data = NULL;
		}
		
		return $data;		
	}


	function get_all_transaction($blogid=NULL,$userid=NULL){
		$this->db
			 ->select('*')
			 ->where('kp_transaction.blog_id', $blogid)
			 ->where('kp_shipping.user_id', $userid)
			 ->from('kp_transaction')
			 ->join('kp_shipping', 'kp_shipping.transaction_id = kp_transaction.transaction_id');
		
		$query = $this->db->get();
		
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $row){
				$data[] = $row;
			}
			
			$query->free_result();
			
		}
		else{
			$data = NULL;
		}
		
		return $data;			
	}


	function get_market_category($parent=NULL){
		$this->db
			 ->select('*')
			 ->from('kp_market');
		
		if(!empty($parent)){
			$this->db->where('market_parent', $parent);
		}

		$query = $this->db->get();
		
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $row){
				$data[] = $row;
			}
			
			$query->free_result();
			
		}
		else{
			$data = NULL;
		}
		
		return $data;				
	}

	function get_market_category_id($name){
		/* dapatkan sesuai slug kategori yang di cari */
		$this->db->from('kp_market');
		$this->db->where('market_name',$name);		
		$query = $this->db->get();
		$row = $query->row_array();
		return $row;	
	}	
	
	
	function mass_update_article($wherein=NULL,$blogid,$where){
		$this->db->where('blog_id',$blogid);
		$this->db->where('post_author',$this->main->getUser('user_id'));
		if($wherein != NULL){
			$this->db->where_in('ID', $wherein);	
			$this->db->update('kp_posts',$where); 
		}
		
		return TRUE;
	}

	function mass_del_articlesa($wherein=NULL){
		if($wherein != NULL){
			$this->db->where_in('ID', $wherein);	
			$this->db->delete('kp_posts'); 
		}
		return TRUE;
	}	

	function mass_del_article($wherein=NULL,$blogid){
		$this->db->where('blog_id',$blogid);
		$this->db->where('post_author',$this->main->getUser('user_id'));
		if($wherein != NULL){
			$this->db->where_in('ID', $wherein);	
			$this->db->delete('kp_posts'); 
		}
		return TRUE;
	}

	
	function get_article_sa($type,$id){
		/* dapatkan sesuai slug kategori yang di cari */
		$this->db->from('kp_posts');
		$this->db->where('kp_posts.post_type',$type);		
		$this->db->where('kp_posts.ID',$id);		
		$this->db->join('kp_domain', 'kp_domain.domain_id = kp_posts.blog_id' );
		$query = $this->db->get();
		$row = $query->row_array();
		return $row;	
	}		

	function get_article($type,$id,$blogid){
		/* dapatkan sesuai slug kategori yang di cari */
		$this->db->from('kp_posts');
		$this->db->where('post_type',$type);
		$this->db->where('post_author',$this->main->getUser('user_id'));
		$this->db->where('ID',$id);
		$this->db->where('blog_id',$blogid);
		$query = $this->db->get();
		$row = $query->row_array();
		return $row;	
	}	

	function get_page($type,$name,$blogid){
		/* dapatkan sesuai slug kategori yang di cari */
		$this->db->from('kp_posts');
		$this->db->where('post_type',$type);
		$this->db->where('post_author',$this->main->getUser('user_id'));
		$this->db->where('post_name',$name);
		$this->db->where('blog_id',$blogid);
		$query = $this->db->get();
		$row = $query->row_array();
		return $row;	
	}
				
	/* bagian user */
	function get_post($type,$status,$blogid,$limit=NULL,$offset=NULL,$mediafilter=NULL,$contentfilter=NULL){
		$this->db->select('kp_users.name, kp_posts.ID, kp_posts.post_author, kp_posts.post_date,
						  kp_posts.post_title, kp_posts.post_content, kp_posts.post_parent, kp_posts.post_status, kp_posts.post_name,
						  kp_posts.post_category, kp_posts.post_price, kp_posts.post_image, kp_posts.post_counter, kp_posts.comment_count,
						  kp_posts.post_type, kp_posts.post_stock, kp_posts.post_code,
						  kp_posts.blog_id');
		
		if($mediafilter=='ff') {$mediafilter='f';}
		else if($mediafilter=='ss'){$mediafilter='s';}

		if($type != 'search'){
			$this->db->where('kp_posts.post_type',$type);
		}
		else{
			/* -- */			
			$this->db->where_not_in('kp_posts.post_type', array('attachment','page')); // , 'attachment')			
		}

		$this->db->from('kp_users');		
		$this->db->where('kp_posts.blog_id',$blogid);
		$this->db->where('kp_posts.post_date <=',date('Y-m-d H:i:s'));
		$this->db->join('kp_posts','kp_posts.post_author = kp_users.id', 'LEFT');
		
		/* jika yang di akses itu bertipe artikel maka */
		if($type == 'post' || $type == 'product' || $type == 'search') {
			$this->db->order_by('kp_posts.ID', 'DESC');	
		}
		else if($type == 'page'){
			$this->db->order_by('kp_posts.menu_order', 'ASC');	
		}
		
		

		if(!empty($mediafilter)){
			if($mediafilter == 's'){
				$this->db->like('kp_posts.post_title',$contentfilter);
			}
			else if($mediafilter == 'f'){
				$this->db->like('kp_posts.post_category',$contentfilter);
			}
		}
		
		if(!empty($status) && $status != '-' ){
			$this->db->where('kp_posts.post_status',$status);
		}
		
		
		if(!empty($limit)){
			$this->db->limit($limit,$offset);
		}
		
		
		$query = $this->db->get();
		
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $row){
				$data[] = $row;
			}
			
			$query->free_result();
			
		}
		else{
			$data = NULL;
		}
		
		return $data;		
	}	
	
	function get_post_detail($type,$status,$name=NULL,$blogid,$id=NULL){
		$this->db->select('kp_users.name, kp_posts.ID, kp_posts.post_author, kp_posts.post_date,
						  kp_posts.post_title, kp_posts.post_content, kp_posts.post_parent, kp_posts.post_status, kp_posts.post_name,
						  kp_posts.post_category, kp_posts.post_code, kp_posts.post_price, kp_posts.post_image, kp_posts.post_counter, kp_posts.comment_count,
						  kp_posts.post_stock,kp_posts.post_type,kp_posts.post_attribute,
						  kp_posts.blog_id');
						  
		$this->db->from('kp_users');
		$this->db->where('kp_posts.post_type',$type);
		$this->db->where('kp_posts.blog_id',$blogid);
		$this->db->where('kp_posts.post_date <=',date('Y-m-d H:i:s'));
		if(!empty($id)){
			$this->db->where('kp_posts.ID',$id);
		}
		else{
			$this->db->where('kp_posts.post_name',$name);
		}
		
		$this->db->join('kp_posts','kp_posts.post_author = kp_users.id', 'LEFT');

		if(!empty($status) && $status != '-' ){
			$this->db->where('kp_posts.post_status',$status);
		}
		
		$query = $this->db->get();
		
		$row = $query->row_array();
		return $row;					
	}

	function getallconfirmation($blogid=NULL,$limit=NULL,$offset=NULL,$mediafilter=NULL,$contentfilter=NULL){
		/* select * from kp_transaction 
		LEFT JOIN kp_trans_detail ON kp_trans_detail.transaction_id = kp_transaction.transaction_id 
		LEFT JOIN kp_shipping ON kp_shipping.transaction_id = kp_transaction.transaction_id 
		where kp_transaction.blog_id = '31'  */

		$this->db->select('*');
		$this->db->from('kp_confirmation');

		if(!empty($blogid)){
			$this->db->where('kp_confirmation.blog_id',$blogid);
		}

		if(!empty($limit)){
			$this->db->limit($limit,$offset);
		}		

		if(!empty($mediafilter)){
			if($mediafilter == 's'){
				$this->db->like('kp_confirmation.name',$contentfilter);
				$this->db->or_like('kp_confirmation.transaction_id',$contentfilter);
			}
		}		

		$this->db->order_by('kp_confirmation.transaction_id', 'DESC');

		$query = $this->db->get();
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $row){
				$data[] = $row;
			}
			
			$query->free_result();
			
		}
		else{
			$data = NULL;
		}
		
		return $data;	
	}

	/* yang sebelah sini digunakan untuk mengambil semua order yang ada di blog yang sedang dibuka */
	function getallorder($blogid=NULL,$limit=NULL,$offset=NULL,$mediafilter=NULL,$contentfilter=NULL){
		/* select * from kp_transaction 
		LEFT JOIN kp_trans_detail ON kp_trans_detail.transaction_id = kp_transaction.transaction_id 
		LEFT JOIN kp_shipping ON kp_shipping.transaction_id = kp_transaction.transaction_id 
		where kp_transaction.blog_id = '31'  */

		$this->db->select('*');
		$this->db->from('kp_transaction');
		$this->db->where('kp_shipping.transaction_id > ', 0);

		if(!empty($blogid)){
			$this->db->where('kp_transaction.blog_id',$blogid);
		}

		if(!empty($limit)){
			$this->db->limit($limit,$offset);
		}		

		if(!empty($mediafilter)){
			if($mediafilter == 's'){
				$this->db->like('kp_shipping.nama_lengkap',$contentfilter);
				$this->db->or_like('kp_shipping.transaction_id',$contentfilter);
			}
			else if($mediafilter == 'f'){
				$this->db->like('kp_transaction.transaction_status',$contentfilter);
			}
		}		

		$this->db->join('kp_shipping','kp_shipping.transaction_id = kp_transaction.transaction_id','LEFT');
		$this->db->order_by('kp_transaction.transaction_id', 'DESC');

		$query = $this->db->get();
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $row){
				$data[] = $row;
			}
			
			$query->free_result();
			
		}
		else{
			$data = NULL;
		}
		
		return $data;	
	}

	function getallorder_byreseller_commision($blogid=NULL,$limit=NULL,$offset=NULL,$mediafilter=NULL,$contentfilter=NULL){
		$this->db->select('kp_transaction.transaction_id, kp_transaction.transaction_status, kp_transaction.transaction_session, 
							kp_transaction.transaction_parent, kp_transaction.transaction_temp_status, kp_transaction.transaction_time, 
							kp_transaction.domain_name_parent, kp_transaction.blog_id_parent, 
							kp_transaction.name_parent, kp_transaction.user_id_parent, kp_transaction.handphone_parent, 	
							kp_transaction.total, kp_transaction.random, kp_transaction.tax, kp_transaction.total_tax, 
							kp_transaction.all_total, kp_transaction.tax_type, kp_transaction.tax_city, 
							kp_transaction.transfer_destination, kp_transaction.tracking_number, kp_transaction.blog_id, 
							kp_shipping.shipping_id, kp_shipping.user_id, kp_shipping.nama_lengkap, kp_shipping.alamat, 
							kp_shipping.provinsi, kp_shipping.kota, kp_shipping.no_telepon, kp_shipping.no_handphone, kp_shipping.email,
							kp_trans_detail.name, kp_trans_detail.product_id, kp_trans_detail.quantity, kp_trans_detail.price, 
							kp_trans_detail.commision_status, kp_trans_detail.commision_reseller, kp_trans_detail.commision_ks, 
							kp_trans_detail.commision_date
						');

		$this->db->from('kp_transaction');		
		$this->db->where('kp_transaction.transaction_parent > ', 0);
		$this->db->where('kp_trans_detail.commision_reseller > ', 0);


		if(!empty($blogid)){
			$this->db->where('kp_transaction.blog_id',$blogid);
			$this->db->where('kp_shipping.blog_id',$blogid);
		}

		if(!empty($limit)){
			$this->db->limit($limit,$offset);
		}		

		if(!empty($mediafilter)){
			if($mediafilter == 's'){
				$this->db->like('kp_shipping.nama_lengkap',$contentfilter);
				$this->db->or_like('kp_shipping.transaction_parent',$contentfilter);
			}
			else if($mediafilter == 'f'){
				$this->db->like('kp_transaction.transaction_status',$contentfilter);
			}
		}		

		$this->db->join('kp_shipping','kp_shipping.transaction_parent = kp_transaction.transaction_parent','LEFT');
		$this->db->join('kp_trans_detail','kp_trans_detail.transaction_parent = kp_transaction.transaction_parent','LEFT');
		$this->db->order_by('kp_transaction.transaction_parent', 'DESC');

		$query = $this->db->get();
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $row){
				$data[] = $row;
			}
			
			$query->free_result();
			
		}
		else{
			$data = NULL;
		}
		
		return $data;
	}

	function getallorder_byreseller($blogid=NULL,$limit=NULL,$offset=NULL,$mediafilter=NULL,$contentfilter=NULL){
		/* select * from kp_transaction 
		LEFT JOIN kp_trans_detail ON kp_trans_detail.transaction_id = kp_transaction.transaction_id 
		LEFT JOIN kp_shipping ON kp_shipping.transaction_id = kp_transaction.transaction_id 
		where kp_transaction.blog_id = '31'  */

		$this->db->select('kp_transaction.transaction_id, kp_transaction.transaction_status, kp_transaction.transaction_session, 
							kp_transaction.transaction_parent, kp_transaction.transaction_temp_status, kp_transaction.transaction_time, 
							kp_transaction.domain_name_parent, kp_transaction.blog_id_parent, 
							kp_transaction.name_parent, kp_transaction.user_id_parent, kp_transaction.handphone_parent, 	
							kp_transaction.total, kp_transaction.random, kp_transaction.tax, kp_transaction.total_tax, 
							kp_transaction.all_total, kp_transaction.tax_type, kp_transaction.tax_city, 
							kp_transaction.transfer_destination, kp_transaction.tracking_number, kp_transaction.blog_id, 
							kp_shipping.shipping_id, kp_shipping.user_id, kp_shipping.nama_lengkap, kp_shipping.alamat, 
							kp_shipping.provinsi, kp_shipping.kota, kp_shipping.no_telepon, kp_shipping.no_handphone, kp_shipping.email');

		$this->db->from('kp_transaction');		
		$this->db->where('kp_transaction.transaction_parent > ', 0);


		if(!empty($blogid)){
			$this->db->where('kp_transaction.blog_id',$blogid);
			$this->db->where('kp_shipping.blog_id',$blogid);
		}

		if(!empty($limit)){
			$this->db->limit($limit,$offset);
		}		

		if(!empty($mediafilter)){
			if($mediafilter == 's'){
				$this->db->like('kp_shipping.nama_lengkap',$contentfilter);
				$this->db->or_like('kp_shipping.transaction_parent',$contentfilter);
			}
			else if($mediafilter == 'f'){
				$this->db->like('kp_transaction.transaction_status',$contentfilter);
			}
		}		

		$this->db->join('kp_shipping','kp_shipping.transaction_parent = kp_transaction.transaction_parent','LEFT');
		$this->db->order_by('kp_transaction.transaction_parent', 'DESC');

		$query = $this->db->get();
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $row){
				$data[] = $row;
			}
			
			$query->free_result();
			
		}
		else{
			$data = NULL;
		}
		
		return $data;	
	}


	function gettransactiondetail($blogid,$wherein){
		$this->db->select('*');
		$this->db->from('kp_transaction');

		if(!empty($blogid)){
			$this->db->where('kp_transaction.blog_id',$blogid);
		}

		$this->db->where_in('kp_transaction.transaction_id',$wherein);

		$this->db->join('kp_trans_detail','kp_trans_detail.transaction_id = kp_transaction.transaction_id','LEFT');
		$this->db->order_by('kp_transaction.transaction_id', 'DESC');

		$query = $this->db->get();
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $row){
				$data[] = $row;
			}
			
			$query->free_result();
			
		}
		else{
			$data = NULL;
		}
		
		return $data;	
	}


	function gettransactiondetail_byreseller($blogid,$wherein){
		$this->db->select('*');
		$this->db->from('kp_transaction');

		if(!empty($blogid)){
			$this->db->where('kp_transaction.blog_id',$blogid);
		}

		$this->db->where_in('kp_transaction.transaction_parent',$wherein);

		$this->db->join('kp_trans_detail','kp_trans_detail.transaction_parent = kp_transaction.transaction_parent','LEFT');
		$this->db->order_by('kp_transaction.transaction_parent', 'DESC');

		$query = $this->db->get();
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $row){
				$data[] = $row;
			}
			
			$query->free_result();
			
		}
		else{
			$data = NULL;
		}
		
		return $data;	
	}

	function getallorder_byself_commision($blogid=NULL,$limit=NULL,$offset=NULL,$mediafilter=NULL,$contentfilter=NULL){
		global $SConfig;
		$this->db->select('kp_transaction.transaction_id, kp_transaction.transaction_status, kp_transaction.transaction_session, 
							kp_transaction.transaction_parent, kp_transaction.transaction_temp_status, kp_transaction.transaction_time, 
							kp_transaction.domain_name_parent, kp_transaction.blog_id_parent, 
							kp_transaction.name_parent, kp_transaction.user_id_parent, kp_transaction.handphone_parent, 	
							kp_transaction.total, kp_transaction.random, kp_transaction.tax, kp_transaction.total_tax, 
							kp_transaction.all_total, kp_transaction.tax_type, kp_transaction.tax_city, 
							kp_transaction.transfer_destination, kp_transaction.tracking_number, kp_transaction.blog_id, 
							kp_shipping.shipping_id, kp_shipping.user_id, kp_shipping.nama_lengkap, kp_shipping.alamat, 
							kp_shipping.provinsi, kp_shipping.kota, kp_shipping.no_telepon, kp_shipping.no_handphone, kp_shipping.email,
							kp_trans_detail.name, kp_trans_detail.product_id, kp_trans_detail.quantity, kp_trans_detail.price, 
							kp_trans_detail.commision_status, kp_trans_detail.commision_reseller, kp_trans_detail.commision_ks, 
							kp_trans_detail.commision_date
						');

		$this->db->from('kp_transaction');		
		$this->db->where('kp_transaction.transaction_parent > ', 0);
		$this->db->where('kp_trans_detail.commision_reseller > ', 0);


		if(!empty($blogid)){
			$this->db->where('kp_transaction.blog_id_parent',$blogid);
			$this->db->where('kp_shipping.blog_id',$SConfig->storeID);
		}

		if(!empty($limit)){
			$this->db->limit($limit,$offset);
		}		

		if(!empty($mediafilter)){
			if($mediafilter == 's'){
				$this->db->like('kp_shipping.nama_lengkap',$contentfilter);
				$this->db->or_like('kp_shipping.transaction_parent',$contentfilter);
			}
			else if($mediafilter == 'f'){
				$this->db->like('kp_transaction.transaction_status',$contentfilter);
			}
		}		

		$this->db->join('kp_shipping','kp_shipping.transaction_parent = kp_transaction.transaction_parent','LEFT');
		$this->db->join('kp_trans_detail','kp_trans_detail.transaction_parent = kp_transaction.transaction_parent','LEFT');
		$this->db->order_by('kp_transaction.transaction_parent', 'DESC');

		$query = $this->db->get();
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $row){
				$data[] = $row;
			}
			
			$query->free_result();
			
		}
		else{
			$data = NULL;
		}
		
		return $data;
	}	
	
	function gettransactiondetail_byself($blogid,$wherein){
		$this->db->select('*');
		$this->db->from('kp_transaction');

		if(!empty($blogid)){
			$this->db->where('kp_transaction.blog_id_parent',$blogid);
		}

		$this->db->where_in('kp_transaction.transaction_parent',$wherein);

		$this->db->join('kp_trans_detail','kp_trans_detail.transaction_parent = kp_transaction.transaction_parent','LEFT');
		$this->db->order_by('kp_transaction.transaction_parent', 'DESC');

		$query = $this->db->get();
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $row){
				$data[] = $row;
			}
			
			$query->free_result();
			
		}
		else{
			$data = NULL;
		}
		
		return $data;	
	}

}