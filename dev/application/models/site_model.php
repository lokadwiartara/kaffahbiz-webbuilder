<?php

class Site_model extends CI_Model{

    function __construct(){
        parent::__construct();
    }	
	
	function login($email){
		$this->db->where('kp_users.email', $email)->where('active', '1')->from('kp_users')->join('kp_domain', 'kp_domain.user_id = kp_users.id', 'LEFT');
		$query = $this->db->get();
		$login = $query->row();
		return $login;
	}

	function checkuser($where){
		$this->db->where($where)->from('kp_users')->join('kp_domain', 'kp_domain.user_id = kp_users.id', 'LEFT');
		$query = $this->db->get();
		$login = $query->row();
		return $login;		
	}

	function checkuserdetail($where){
		$this->db->where($where)->from('kp_users')->join('kp_user_detail', 'kp_user_detail.user_id = kp_users.id', 'LEFT');
		$query = $this->db->get();
		$login = $query->row();
		return $login;		
	}

	function checkuserfase2($where){		 
		$this->db->where($where)->from('kp_users')->join('kp_multi_sitelogin', 'kp_multi_sitelogin.user_id = kp_users.id');
		$query = $this->db->get();
		$login = $query->row();
		return $login;
	}
	
	function checkdomain($domain){
		$this->db->where('domain_name',$domain)->from('kp_domain');
		$query = $this->db->get();
		$login = $query->row();
		return array($login, $query->num_rows());
	}
	
	function getDomainAtr($id,$userid){
		$this->db->where('domain_id',$id)->where('user_id',$userid)->from('kp_domain');
		$query = $this->db->get();
		$row = $query->row_array();
		return $row;		
	}
	
	function getalltemplate(){
		/* tes */
		$this->db->select('template_id,template_url,template_author,template_name,template_price,template_desc,template_thumb,template_image')
				 ->from('kp_template');
		
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

	function getdomaindetail($blogid){
		$this->db->select('kp_domain.domain_name, kp_domain.domain_id, kp_domain.domain_status, kp_domain.domain_verify, kp_domain.domain_activated, kp_domain.domain_title, kp_domain.packet_id, kp_user_detail.*, kp_packet.*, kp_users.name as name, kp_users.email as email, kp_domain.domain_expired as domain_expire')				 
				 ->from('kp_domain')
				 ->order_by('domain_id','DESC')
				 ->join('kp_users','kp_users.id = kp_domain.user_id','LEFT')
				 ->join('kp_user_detail','kp_user_detail.user_id = kp_users.id','LEFT')
				 ->join('kp_packet','kp_packet.packet_id = kp_domain.packet_id','LEFT')				 
				 ->where('kp_domain.domain_id',$blogid)
				 ->group_by('kp_domain.domain_id');
		
		$query = $this->db->get();
		$row = $query->row_array();
		return $row;
	}
	
	function getalldomain($limit=NULL,$offset=NULL,$mediafilter=NULL,$contentfilter=NULL){
		$select = 'kp_domain.domain_id,kp_domain.domain_reseller,kp_domain.domain_name,kp_domain.domain_group,kp_domain.domain_title,kp_domain.domain_desc,kp_domain.domain_status,kp_domain.domain_atribute,kp_domain.domain_template,kp_domain.domain_modul,kp_domain.domain_activated,kp_domain.domain_verify,kp_domain.domain_expired,kp_domain.domain_address,kp_domain.domain_city,kp_domain.domain_regional,kp_domain.domain_latitude,kp_domain.domain_longitude,kp_domain.user_id,kp_domain.market_id,kp_domain.packet_id, kp_users.email as email, kp_domain.domain_expired as domain_expire';
		// kp_packet.packet_name as packet_name, 
		// , Count(case kp_posts.post_type when "post" then 1 else null end) as post_total, Count(case kp_posts.post_type when "product" then 1 else null end) as product_total,  Count(case kp_posts.post_type when "attachment" then 1 else null end) as image_total, Sum(kp_posts.comment_count) as comment_total
		$this->db->from('kp_domain')				 
				 ->join('kp_users','kp_users.id = kp_domain.user_id','LEFT')
				 //->join('kp_packet','kp_packet.packet_id = kp_domain.packet_id','LEFT')
				 //->join('kp_posts','kp_posts.blog_id = kp_domain.domain_id','LEFT')				 
				 ->group_by('kp_domain.domain_id');

		if(!empty($limit)){
			$this->db->limit($limit,$offset);
		}		

		if(!empty($mediafilter)){
			if($mediafilter == 's'){
				$this->db->like('kp_domain.domain_name',$contentfilter);
				$this->db->or_like('kp_domain.domain_group',$contentfilter);
			}

			else if($mediafilter == 'f'){
				if($contentfilter == 'silver'){
					$packet_name = 2;
					$this->db->like('kp_domain.packet_id',$packet_name);
					$this->db->order_by('kp_domain.domain_id','DESC');	
				}
				
				else if($contentfilter == 'basic'){
					$packet_name = 3;	
					$this->db->like('kp_domain.packet_id',$packet_name);
					$this->db->order_by('kp_domain.domain_id','DESC');	
				}

				else if($contentfilter == 'free'){
					$packet_name = 1;
					$this->db->like('kp_domain.packet_id',$packet_name);
					$this->db->order_by('kp_domain.domain_id','DESC');	
				}
				
				else if($contentfilter == 'active'){
					$packet_name = 1;
					$this->db->where('kp_domain.domain_status', 1);
					$this->db->where('kp_domain.packet_id <>', 1);
					$this->db->order_by('kp_domain.domain_id','DESC');	
				}

				else if($contentfilter == 'unactive'){
					$packet_name = 1;
					$this->db->where('kp_domain.domain_status', 0);
					$this->db->where('kp_domain.packet_id <>', 1);
					$this->db->order_by('kp_domain.domain_id','DESC');	
				}
				
				else if($contentfilter == 'reseller'){
					$packet_name = 1;
					$this->db->where('kp_domain.domain_reseller <>', '');
					$this->db->order_by('kp_domain.domain_id','DESC');	
				}

								
			}

			else if($mediafilter == 'sort'){
				switch($contentfilter){
					case 'post': 						
						$this->db->order_by('post_total','DESC'); 
						break;	
					case 'comment': 						
						$this->db->order_by('comment_total','DESC'); 
						break;							
					case 'visitor': 
						$select .= ',sum(kp_posts.post_counter) as jumlah_kunjungan';
						$this->db->order_by('sum(kp_posts.post_counter)','DESC'); 
						break;										
					case 'cart':						 		 				
						$this->db->join('kp_transaction','kp_domain.domain_id = kp_transaction.blog_id', 'LEFT');
						$this->db->order_by('sum(kp_transaction.total)','DESC');
						break;
					default: $this->db->order_by('kp_domain.domain_id','DESC'); break;
				}

			}

			else{
				$this->db->order_by('kp_domain.domain_id','DESC');
			}
				
		}	

		$this->db->select($select);

				
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

	function getalltransactioncount($wherein){
		$this->db->select('kp_transaction.*, Count(transaction_id) as total_transaction')				 
				 ->from('kp_transaction')
				 ->order_by('blog_id','DESC')	
				 ->where_in('blog_id',$wherein)			 
				 ->group_by('blog_id');
		
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
	
	function getTemplate($id){
		$this->db->where('template_id',$id)->from('kp_template');
		$query = $this->db->get();
		$row = $query->row_array();
		return $row;			
	}			

	function getTemplateName($name){
		$this->db->where('template_name',$name)->from('kp_template');
		$query = $this->db->get();
		$row = $query->row_array();
		return $row;			
	}
	


	
	
	function getStatistic($blogid,$unique=NULL){
		$this->db
		->select("
			  Count(kp_visitor_logs.VisBlog_id) AS total_blog,
			  Date_Format(kp_visitor_logs.VisDate, '%m-%d-%Y') AS date",FALSE)
			  
		->where('kp_visitor_logs.VisBlog_id',$blogid)->from('kp_visitor_logs')		
		->group_by('date')
		->limit(10,0)
		->order_by('kp_visitor_logs.VisID', 'DESC');
		
		
		$this->db->group_by('kp_visitor_logs.VisBlog_id');
		
		
		$query = $this->db->get();
		if($query->num_rows() > 0){
			foreach($query->result_array() as $row){
				$data[] = $row;
			}
			$query->free_result();
		}
		else{
			$data = NULL;
		}
		
		return $data;
	}

	function get_recent_30($blogid){
		$this->db
		->select("*",FALSE)			  
		->where('kp_visitor_logs.VisBlog_id',$blogid)->from('kp_visitor_logs')		
		->limit(15,0)
		->order_by('kp_visitor_logs.VisID', 'DESC');		
		
		$query = $this->db->get();
		if($query->num_rows() > 0){
			foreach($query->result_array() as $row){
				$data[] = $row;
			}
			$query->free_result();
		}
		else{
			$data = NULL;
		}
		
		return $data;
	}


	function get_by_hour($blogid){
		/*
			SELECT CONCAT(HOUR(VisDate), ':00-', HOUR(VisDate)+1, ':00') AS Hours, count(*) FROM 
				kp_visitor_logs WHERE VisDate BETWEEN '2015-04-01' AND NOW()
					GROUP BY HOUR(VisDate)
		*/		

		
		$date = date('Y-m-d');			

		$query = $this->db->query("SELECT CONCAT(HOUR(VisDate), ':00-', CONCAT( HOUR(VisDate)+1, ':00' ) ) AS Hours, count(*) as jumlah FROM 
				kp_visitor_logs WHERE VisDate >= subdate(current_date, 1)  AND VisBlog_id = '$blogid'
					GROUP BY HOUR(VisDate)");

		if($query->num_rows() > 0){
			foreach($query->result_array() as $row){
				$data[] = $row;
			}
			$query->free_result();
		}
		else{
			$data = NULL;
		}
		
		return $data;

	}


	function get_by_hour_total($blogid){
		$this->db->select('VisDate');
		$this->db->from('kp_visitor_logs');
		$this->db->where("VisDate BETWEEN SUBDATE(current_date, 1)  AND NOW() AND VisBlog_id = '$blogid'");
		return $this->db->count_all_results();		
	}
	
	function get_by_date($blogid){
		$query = $this->db->query("SELECT count(*) as visitor, DATE(VisDate) as date FROM kp_visitor_logs 
					WHERE VisDate BETWEEN SUBDATE(current_date, 18) AND NOW() AND VisBlog_id = '$blogid' GROUP BY date order by date desc limit 0,15");

		if($query->num_rows() > 0){
			foreach($query->result_array() as $row){
				$data[] = $row;
			}
			$query->free_result();
		}
		else{
			$data = NULL;
		}
		
		return $data;
	}

	function get_by_date_total($blogid){
		$this->db->from('kp_visitor_logs');		
		$this->db->where("VisDate BETWEEN SUBDATE(current_date, 18) AND NOW() AND VisBlog_id = '$blogid'");		
		$this->db->order_by('DATE(VisDate)', 'DESC');		
		$this->db->limit(15);
		return $this->db->count_all_results();	
	}

	/* ini digunakan untuk mengambil semua atribut domain */
	function get_domain_detail($blogid){
		$this->db->where('kp_domain.domain_id',$blogid)->from('kp_domain');
		$this->db->join('kp_template', 'kp_template.template_name = kp_domain.domain_template', 'LEFT');
		
		$query = $this->db->get();
		$row = $query->row_array();
		$row['domain_html'] = base64_decode(open_template($row['domain_id']));
		return $row;			
	}
		

	function is_modulexist($where,$table){
		$this->db->where($where);
		$this->db->from($table);
		$query = $this->db->get();
						
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $row){
				$data['option_id'] = $row['option_id'];
				$data['option_name'] = $row['option_name'];
				$data['blog_id'] = $row['blog_id'];
				$data['option_value'] = $row['option_value'];
			}
			
			$query->free_result();
			
		}
		else{
			$data = NULL;
		}
		
		return $data;
		
	}

	/* ambil semua module yang ada dalam database blog anu */
	function getActiveModule($blogid){
		$this->db->where('blog_id',$blogid)->where('option_name','module_setting')->from('kp_options');
		$query = $this->db->get();
		$row = $query->row_array();
		return $row;			
	}	
	
	function getTemplateSetting($blogid){
		$this->db->where('blog_id',$blogid)->where('option_name','template_setting')->from('kp_options');
		$query = $this->db->get();
		$row = $query->row_array();
		return $row;		
	}

}
