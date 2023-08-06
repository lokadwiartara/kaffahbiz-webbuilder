<?php echo get_template('dir/header',TRUE);?>

<div class="main_bg inside">
<div class="wrap">
	<div class="main">
		<div class="content">
			<!-- start blog_left -->
			<div class="blog_left">		
				
				

				<div class="blog_main single  det_text">	
					<div id="breadcrumbdir"><a href="http://www.kaffah.biz/">KaffahBiz</a> / <a href="http://www.kaffah.biz/dir/">Direktori</a> / <a href="http://www.kaffah.biz/dir/artikel/">Artikel</a></div>
                    <h2><?php echo $this->template->dir->post_title ;?></h2> 
                                                                   
                    <?php echo nofollow($this->template->dir->post_content) ;?>

					<div class="social-sharing is-large" data-permalink="<?php echo base_url().'dir/artikel/id/'.$this->template->dir->ID.'/'.$this->template->dir->post_name;?>">

					    <!-- https://developers.facebook.com/docs/plugins/share-button/ -->
					    <a target="_blank" href="http://www.facebook.com/sharer.php?u=<?php echo base_url().'dir/artikel/id/'.$this->template->dir->ID.'/'.$this->template->dir->post_name;?>" class="share-facebook">
					      <span class="icon icon-facebook" aria-hidden="true"></span>
					      <span class="share-title">Share</span>
					      <span class="share-count">0</span>
					    </a>

					    <!-- https://dev.twitter.com/docs/intents -->
					    <a target="_blank" href="http://twitter.com/share?url=<?php echo base_url().'dir/artikel/id/'.$this->template->dir->ID.'/'.$this->template->dir->post_name;?>&amp;text=<?php echo $this->template->dir->post_title;?>&amp;" class="share-twitter">
					      <span class="icon icon-twitter" aria-hidden="true"></span>
					      <span class="share-title">Tweet</span>
					      <span class="share-count">0</span>
					    </a>

					    <!--
					      https://developers.pinterest.com/pin_it/
					      Pinterest get data from the same Open Graph meta tags Facebook uses
					    -->
					    <a target="_blank" href="http://pinterest.com/pin/create/button/?url=<?php echo base_url().'dir/artikel/id/'.$this->template->dir->ID.'/'.$this->template->dir->post_name;?>&amp;media=<?php echo get_thumb(img_capture($this->template->dir->post_content), 180, 180, 3);?>&amp;description=<?php echo $this->template->dir->post_title;?>" class="share-pinterest">
					      <span class="icon icon-pinterest" aria-hidden="true"></span>
					      <span class="share-title">Pin it</span>
					      <span class="share-count">0</span>
					    </a>

					    <!-- https://developers.google.com/+/web/share/ -->
					    <a target="_blank" href="http://plus.google.com/share?url=<?php echo base_url().'dir/artikel/id/'.$this->template->dir->ID.'/'.$this->template->dir->post_name;?>" class="share-google">
					      <!-- Cannot get Google+ share count with JS yet -->
					      <span class="icon icon-google" aria-hidden="true"></span>
					      <span class="share-count">+1</span>
					    </a>

					</div>
					                
	                <div class="recentarticle">	
						<h2>Artikel Terkait : </h2>    
						<ul class="recentarticle">

							<?php $recent = get_dir_related_post('post',$this->template->dir->post_category,$this->template->dir->ID);?>
							<?php if(!empty($recent)): foreach($recent as $rec):?>
								<li><img src="<?php echo get_thumb(img_capture($rec->post_content), 165, 125, 3);?>" class="recentthumb" /><h5><a href="<?php echo base_url().'dir/artikel/id/'.$rec->ID.'/'.$rec->post_name;?>"><?php echo $rec->post_title;?></a></h5></li>							
							<?php endforeach;endif;?>

						</ul>
						<div class="clear"></div>
					</div>

                </div>
				

				
			</div>
			<!-- start blog_sidebar -->
			<?php echo get_template('dir/sidebar', TRUE);?>
		<div class="clear"></div>

	

		</div>


	</div>
</div>
</div>

<?php echo get_template('dir/footer',TRUE);?>
