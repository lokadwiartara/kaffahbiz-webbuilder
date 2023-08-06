<?php echo get_template('header',TRUE);?>


<!-- start  -->
<div class="slider_bg">
<div class="wrap">
	<div class="slider">
		<h2>Kaffah Blog</h2>
		<h3>Info Uptodate, mengenai kaffah, teknologi web, tips bisnis, tips internet marketing, kaffah berbagi...</h3>
	</div>
</div>
</div>
<!-- start main -->
<div class="main_bg inside">
<div class="wrap">
	<div class="main">
		<div class="content">
			<!-- start blog_left -->
			<div class="blog_left">	

		<?php if(k_have_post($type='post','','',array('bantuan', 'testimoni','tutorial_video'))): ?>
			<?php foreach(k_post($type) as $post) :?>
				<div class="blog_main">	
					<img src="<?php echo get_thumb(img_capture($post), 650, 260, 0);?>" alt=""/>
					
					<div class="b_right">
						<h4><a href="<?php echo k_permalink($type, $post);?>"><?php echo k_title($post); ?></a></h4>
						<div class="blog_list">
					      		<ul>
							  		<li><a href="#"> <i class="date"> </i><span><?php echo k_date($post, '%d %F %Y');?></span></a></li>
				  					<li><a href="#"> <i class="news"> </i><span>Kategori <?php echo $post->term_name;?></span></a></li>
				  					<li><a href="#"> <i class="views"> </i><span><?php echo $post->post_counter;?> views</span></a></li>
					    		</ul>
					    		<div class="clear"></div>
					      </div>
					    
					</div>
					<div class="clear"></div>
					  <p><?php echo get_intro_(k_content($post), 400); ?> [...]</p>
				 </div>
				
			<?php endforeach;?>

			<?php ($this->uri->segment(2) == NULL) ? $paging  = NULL : $paging = k_post_paging($type);?>
		
	
		<?php echo $paging ;?>
		
		
		<?php endif;?>

	
			</div>
			<!-- start blog_sidebar -->
			<?php echo get_template('sidebar', TRUE);?>
		<div class="clear"></div>
		</div>
	</div>
</div>
</div>

<?php echo get_template('footer',TRUE);?>
