<?php echo '<?xml version="1.0" encoding="UTF-8" ?>'; ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc><?php echo base_url();?></loc> 
        <priority>1.0</priority>
    </url>

    
    <?php foreach($sitemap as $url) { ?>
    <url>
		<?php 
		
			/* url type */			
			if($url['post_type'] == 'post'){
				$category = explode(',',$url['post_category']);
				$urltype = 'artikel/'.$category[0].'/'.$url['post_name'];	
			}
			else if($url['post_type'] == 'page'){
				$urltype = 'halaman'.'/'.$url['post_name'];
			}
			
			else if($url['post_type'] == 'product'){
				$category = explode(',',$url['post_category']);
				$urltype = 'produk/'.$category[0].'/'.$url['post_name'];	
			}
			else{
				
			}		
						
		?>
        <loc><?php echo base_url().$urltype.'/'; ?></loc>
        <priority>0.2</priority>
    </url>
    <?php } ?>

</urlset>