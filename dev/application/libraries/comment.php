<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Comment {

	function init_comment(){

		/* menentukan kondisi ketika REPLY */		
		$_this =& get_instance();
		$_this->load->library('user_agent');
		$_this->load->library('template');
		$_this->load->model('Global_model');
		$_this->load->model('Site_model');
		$_this->load->library('coredb');
		$post = $_this->input->post(NULL, TRUE);
		$serv = $_SERVER;		


		// First, delete old captchas
		$expiration = time()-7200; // Two hour limit
		$_this->db->query("DELETE FROM kp_captcha WHERE captcha_time < ".$expiration);	

		// Then see if a captcha exists:
		$sql = "SELECT COUNT(*) AS count FROM kp_captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?";
		$binds = array($post['captcha'], $_this->input->ip_address(), $expiration);
		$query = $_this->db->query($sql, $binds);
		$row = $query->row();

		if ($row->count == 0){
		    $captcha = FALSE;
		    $post['captcha'] = 'salah';
		}
		else{
			$captcha = TRUE;
		}


		if (($_this->session->userdata('logged_in')) || (!empty($post['name']) &&  !empty($post['email']) && !empty($post['comment']) && $captcha == TRUE)){			

			$date = date('Y-m-d H:i:s');			
			/* suatu saat nanti akan berguna ... */
			$domainatr = $_this->main->allAtr ;
			$_this->template->templatesetting = $_this->Site_model->getTemplateSetting($domainatr->domain_id);
			
			/* get comment status */
			$array_post_detail = array('ID' => $post['post_id'], 'post_type' => $post['post_type']);
			$array_get_post_detail = $_this->Global_model->select($array_post_detail,'kp_posts','ID','Desc');
			$post_comment_status = $array_get_post_detail[0]['comment_status'];

			/* get comment setting */
			$all_ts_array = $_this->template->templatesetting;
			$template_setting = unserialize(base64_decode($all_ts_array['option_value']));
			$comment_setting = $template_setting['comment_setting'];

			/* ketika status komentarnya itu on, artinya memperbolehkan komentar */
			if($post_comment_status == 'on') {
				$comment_approved = 'terpasang' ;	
			}  
			else{
				$comment_approved = 'tidak_terpasang';
			}

			/* jika disetting untuk kirim email ke admin  */
			if($comment_setting['sent_email'] == 'yes') {
				
			} 
			else{
				
			}

			/* semua komentar harus dimoderasi terlebih dahulu */
			if(array_key_exists('moderation', $comment_setting)) { 				
				$comment_approved = 'tidak_terpasang' ;
				/* disinilah update post comment */
			} 
			else{
				$comment_approved = 'terpasang' ;
			}

			if($_this->session->userdata('logged_in')){
				$post['name'] = $_this->session->userdata('name');
				$post['email'] = $_this->session->userdata('email');
				$post['website'] = '';
			}

			/* ketika semua orang bisa berkomentar  */
			if($comment_setting['all_comment'] == 'yes') {				
				$array_new_comment = array(
					'comment_post_ID' 		=> $post['post_id'], 
					'comment_author_name' 	=> $post['name'], 
					'comment_author_email' 	=> $post['email'], 
					'comment_author_url' 	=> $post['website'], 
					'comment_author_IP' 	=> $_SERVER['REMOTE_ADDR'],
					'comment_date' 			=> $date, 
					'comment_date_gmt' 		=> $date, 
					'comment_content' 		=> $post['comment'], 
					'comment_approved' 		=> $comment_approved, 
					'comment_agent' 		=> $_this->agent->agent_string(), 
					'comment_type' 			=> $post['post_type'], 
					'comment_blog_id' 		=> $domainatr->domain_id		
				);

				if($_this->session->userdata('logged_in')){
					$array_new_comment['comment_author'] = $_this->session->userdata('user_id');
				}

				/* jika komentar langsung dipasang maka langsung update saja komentar untuk post tersebut */				
				if($comment_approved == 'terpasang'){
					/* check comment */
					$total_comment = $_this->coredb->count_table_row(array('comment_post_ID' => $post['post_id'], 'comment_approved' => 'terpasang'),'kp_comments');
					/* update post */
					$_this->Global_model->update(array('ID' => $post['post_id']),array('comment_count' => $total_comment+1),'kp_posts');
				}

				/* insert comment to db */
				$_this->Global_model->add($array_new_comment,'kp_comments');
			} 				

			/* redirect ke halaman sebelumnya di mana komentar di kirimkan */
			redirect($serv['HTTP_REFERER'].'#daftar_komentar');					
		
		}
		/* if(empty($post['name']) || empty($post['email']) ||  empty($post['comment'])  )*/
		else {
			$_this->session->set_flashdata($post);
			$_this->session->set_userdata(array('comment' => TRUE));
			redirect($serv['HTTP_REFERER'].'#form_komentar');
		}		

	}
	
}