<?php echo get_template('header',TRUE);?>


<!-- start  -->
<div class="slider_bg">
<div class="wrap">
	<div class="slider">
		<h2><?php echo k_title();?></h2>
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
				<div class="blog_main">	
					<div class="details">
					<div class="det_text">	
						<?php echo k_content();?>
					</div>						
					</div>	
				</div>
				
				 <div class="blog_main">	
				 <!-- start slider-text -->
				
				<!-- end slider-text -->
				</div>
			</div>
			<!-- start blog_sidebar -->
			<?php echo get_template('sidebar', TRUE);?>
		<div class="clear"></div>
		</div>
	</div>
</div>
</div>

<?php echo get_template('footer',TRUE);?>