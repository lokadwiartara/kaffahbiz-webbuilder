<?php

	class Global_model extends CI_Model{

	    function __construct(){
	        parent::__construct();
	    }	
		
		/* 	ini adalah fungsi yang di gunakan untuk menginsertkan berbagai 
			macam data ke berbagai macam table */

		function add($data, $table){	
			$this->db->insert($table, $data);	
			return TRUE;
		}


		function addBatch($data, $table){	
			$this->db->insert_batch($table, $data);	
			return TRUE;
		}		

		function addNew($data, $table){
			if(($this->main->getUser('user_id')) > 0){
				$this->db->insert($table, $data);	
			}
			return TRUE;
		}
		
		function select($where,$table,$order,$by){
			$this->db->select('*')->where($where)->from($table)->order_by($order,$by);
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


		function selectwherein($wherein,$table,$select=NULL){
			if($select == NULL){
				$this->db->select('*')->where_in('ID',$wherein)->from($table);	
			}
			else{
				$this->db->select($select)->where_in('ID',$wherein)->from($table);	
			}
			
			$query = $this->db->get();
			
			if($query->num_rows() > 0){
				foreach ($query->result_array() as $row){
					$data[$row['ID']] = $row;
				}
				
				$query->free_result();
				
			}
			else{
				$data = NULL;
			}
			
			return $data;					
		}		

		function selectwherein_what($wherein,$table,$what){
			$this->db->select('*')->where_in($what,$wherein)->from($table);
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


		function select_single($where,$table,$join=FALSE,$tablejoin=FALSE,$whattojoin=FALSE){
			$this->db->where($where)->from($table)->limit(1);
			$query = $this->db->get();

			if($join == TRUE){
				$this->db->join($tablejoin,$whattojoin,'LEFT');
			}
			
			if($query->num_rows() > 0){				
				$data = $query->row_array();;								
				$query->free_result();
				
			}
			else{
				$data = NULL;
			}
		
			return $data;					
		}		
		
		
		/* fungsi untuk melakukan delete satuan record pada table */
		function del($where,$table,$in=FALSE,$arrwherein=null,$whatwherein=null){
			$this->db->where($where);
			if($in==TRUE){
				$this->db->where_in($whatwherein,$arrwherein);
			}			
			$this->db->delete($table);
			return TRUE;	
		}
		
		function update($where,$data,$table,$in=FALSE,$arrwherein=null,$whatwherein=null){
			
			$this->db->where($where);
			if($in==TRUE){
				$this->db->where_in($whatwherein,$arrwherein);
			}
			$this->db->update($table,$data);
			return TRUE;	
		}
		
		function numrows($where,$table,$mediafilter=NULL,$contentfilter=NULL,$byself=FALSE){
			$this->db->where($where);			
			/* for post only */
			
			if(($byself == TRUE) && ($table == 'kp_trans_detail') ){
				$this->db->join('kp_transaction','kp_trans_detail.transaction_parent = kp_transaction.transaction_parent', 'LEFT');
			}

			else if(!empty($mediafilter) && ($table == 'kp_posts') ){
				if($mediafilter == 's'){
					$this->db->like('kp_posts.post_title',$contentfilter);
					$this->db->or_like('kp_posts.post_content',$contentfilter);
				}
				else if($mediafilter == 'f'){
					$this->db->like('kp_posts.post_category',$contentfilter);
				}
			}

			else if(!empty($mediafilter) && ($table == 'kp_domain') ){
				if($mediafilter == 's'){
					$this->db->like('kp_domain.domain_name',$contentfilter);
					$this->db->or_like('kp_domain.domain_group',$contentfilter);
				}
				else if($mediafilter == 'f'){
					if($contentfilter == 'active'){
						$this->db->where('kp_domain.domain_status', 1);
						$this->db->where('kp_domain.packet_id <>', 1);
					}
					else if($contentfilter == 'unactive'){
						$this->db->where('kp_domain.domain_status', 0);
						$this->db->where('kp_domain.packet_id <>', 1);
					}
					else if($contentfilter == 'reseller'){
						$this->db->where('kp_domain.domain_reseller <>', '');
					}
					else{
						
						$this->db->like('kp_packet.packet_name',$contentfilter);				
					}

					$this->db->join('kp_packet','kp_packet.packet_id = kp_domain.packet_id', 'LEFT');
					
				}
			}

			else if(!empty($mediafilter) && ($table == 'kp_shipping') ){
				if($mediafilter == 's'){
					$this->db->like('kp_shipping.nama_lengkap',$contentfilter);
					$this->db->or_like('kp_shipping.transaction_id',$contentfilter);
				}
				else if($mediafilter == 'f'){
					$this->db->join('kp_transaction','kp_shipping.transaction_id = kp_transaction.transaction_id', 'LEFT');
					$this->db->like('kp_transaction.transaction_status',$contentfilter);
				}
			}			

			else if(!empty($mediafilter) && ($table == 'kp_comments') ){
				if($mediafilter == 's'){
					$this->db->like('kp_comments.comment_content',$contentfilter);					
				}
			}

			else if(!empty($mediafilter) && ($table == 'kp_confirmation') ){
				
				if($mediafilter == 's'){
					$this->db->like('kp_confirmation.name',$contentfilter);
					$this->db->or_like('kp_confirmation.transaction_id',$contentfilter);					
				}
			}


			
			$this->db->from($table);
			return $this->db->count_all_results();
		}			
		
	}