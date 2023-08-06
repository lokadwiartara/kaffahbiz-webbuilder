<?php

class Comment_model extends CI_Model{

    function __construct(){
        parent::__construct();
    }

    function get_comment_list($where){
    	$this->db->select('kp_comments.*')
				 ->where($where)
				 ->from('kp_comments')
				 ->join('kp_posts','kp_comments.comment_post_ID = kp_posts.ID', 'LEFT')
				 ->order_by('comment_ID','ASC');				

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

	function get_all_comment($blogid,$limit=NULL,$offset=NULL,$status=NULL,$mediafilter=NULL,$contentfilter=NULL){
		$this->db->select('kp_comments.*, kp_posts.ID, kp_posts.post_title, kp_posts.post_name, kp_posts.post_type, kp_posts.post_category')
				 ->where('kp_comments.comment_blog_id',$blogid)
				 ->from('kp_comments')
				 ->join('kp_posts','kp_comments.comment_post_ID = kp_posts.ID', 'LEFT')
				 ->order_by('comment_ID','DESC');				


		if(!empty($mediafilter)){
			if($mediafilter == 's'){

				$this->db->like('kp_comments.comment_content',$contentfilter);
			}
		}
		
		if(!empty($status) && $status != '-' ){
			$this->db->where('kp_comments.comment_approved',$status);
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

	function mass_publish_comment($wherein=NULL,$blogid,$data){
		$this->db->where('comment_blog_id',$blogid);
		if($wherein != NULL){
			$this->db->where_in('comment_ID', $wherein);	
			$this->db->update('kp_comments',$data); 
		}
		
		return TRUE;
	}	


	function mass_del_comment($wherein=NULL,$blogid){
		$this->db->where('comment_blog_id',$blogid);
		if($wherein != NULL){
			$this->db->where_in('comment_ID', $wherein);	
			$this->db->delete('kp_comments'); 
		}
		
		return TRUE;
	}
}