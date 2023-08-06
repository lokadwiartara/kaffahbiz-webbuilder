<?php echo get_template('dir/header',TRUE);?>

	<!-- start main -->
	<div class="main_bg inside">
    	<div class="wrap">
        <div class="main">
            <div class="content">
                <!-- start blog_left -->
               
                    <div class="blog_main single  ">	
                        <div class="searchbigbox"><input type="input" name="" class="searchbig" placeholder="Pencarian..." /></div>
                        <div class="searchtab"><h2><a href="http://www.kaffah.biz/dir/artikel" class="active first">Artikel</a></h2><h2><a href="http://www.kaffah.biz/dir/produk">Produk</a></h2><h2><a href="#">Website</a></h2></div>                    
                                                                 

                        <div class="listdir">

                        	 <?php foreach(get_dir_post('post') as $record):?>
	                        	<div class="onedir">
									<img src="<?php echo get_thumb(img_capture($record->post_content), 180, 180, 3);?>" class="thumbdir" />
									<h2><a href="<?php echo base_url().'dir/artikel/id/'.$record->ID.'/'.$record->post_name;?>"><?php echo $record->post_title;?></a></h2>
									<em class="author"><?php echo str_replace('www.','',$record->domain_name);?></em> <em class="date"><?php echo date("d/m/Y", strtotime($record->post_date));?></em> <em class="view"><?php echo $record->post_counter;?>x dilihat</em> <em class="comment"><?php echo $record->comment_count;?> komentar</em> 
									<p><?php echo character_limiter(strip_tags($record->post_content), 250);?></p>

									<div class="social-sharing" data-permalink="<?php echo base_url().'dir/artikel/id/'.$record->ID.'/'.$record->post_name;?>">

									    <!-- https://developers.facebook.com/docs/plugins/share-button/ -->
									    <a target="_blank" href="http://www.facebook.com/sharer.php?u=<?php echo base_url().'dir/artikel/id/'.$record->ID.'/'.$record->post_name;?>" class="share-facebook">
									      <span class="icon icon-facebook" aria-hidden="true"></span>
									      <span class="share-title">Share</span>
									      <span class="share-count">0</span>
									    </a>

									    <!-- https://dev.twitter.com/docs/intents -->
									    <a target="_blank" href="http://twitter.com/share?url=<?php echo base_url().'dir/artikel/id/'.$record->ID.'/'.$record->post_name;?>&amp;text=<?php echo $record->post_title;?>&amp;" class="share-twitter">
									      <span class="icon icon-twitter" aria-hidden="true"></span>
									      <span class="share-title">Tweet</span>
									      <span class="share-count">0</span>
									    </a>

									    <!--
									      https://developers.pinterest.com/pin_it/
									      Pinterest get data from the same Open Graph meta tags Facebook uses
									    -->
									    <a target="_blank" href="http://pinterest.com/pin/create/button/?url=<?php echo base_url().'dir/artikel/id/'.$record->ID.'/'.$record->post_name;?>&amp;media=<?php echo get_thumb(img_capture($record->post_content), 180, 180, 3);?>&amp;description=<?php echo $record->post_title;?>" class="share-pinterest">
									      <span class="icon icon-pinterest" aria-hidden="true"></span>
									      <span class="share-title">Pin it</span>
									      <span class="share-count">0</span>
									    </a>

									    <!-- https://developers.google.com/+/web/share/ -->
									    <a target="_blank" href="http://plus.google.com/share?url=<?php echo base_url().'dir/artikel/id/'.$record->ID.'/'.$record->post_name;?>" class="share-google">
									      <!-- Cannot get Google+ share count with JS yet -->
									      <span class="icon icon-google" aria-hidden="true"></span>
									      <span class="share-count">+1</span>
									    </a>

									    
									</div>

	                        		
	            

	                        		<div class="clear"></div>
	                        	</div>
	                        <?php endforeach;?>

                        </div>

                        <?php echo paging_dir('post');?>

                    </div>
			                    
                
               
    
            <div class="clear"></div>
            </div>
        </div>
    </div>
    </div>    


<?php echo get_template('dir/footer',TRUE);?>