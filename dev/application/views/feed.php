<?php echo '<?xml version="1.0" encoding="utf-8"?>' . "\n"; ?>
<rss version="2.0"
xmlns:dc="http://purl.org/dc/elements/1.1/"
xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
xmlns:admin="http://webns.net/mvcb/"
xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
xmlns:media="http://search.yahoo.com/mrss/"
xmlns:content="http://purl.org/rss/1.0/modules/content/">
 
	<channel>
		<title><?php echo $template_setting['judul'];?></title>
		<link><?php echo base_url();?>feed/</link>
		<description><?php echo $template_setting['tagline'];?></description>
		<dc:language>en-ca</dc:language>;
		<dc:creator><?php echo $template_setting['email'];?></dc:creator>
		<image></image>

		<dc:rights>Copyright <?php echo date('y');?></dc:rights>
		<admin:generatorAgent rdf:resource="http://www.kaffah.biz/" />

		<?php 
			$record = $this->Post_model->get_post('post','publish',$domainatr->domain_id, 20,NULL,NULL,NULL);									

		?>
		
			<?php if(!empty($record)): foreach($record as $post) :?>
				<item>
					<title><?php echo $post['post_title']; ?></title>
					<link><?php echo $this->template->kaffahPostPermalink($post, 'post');?></link>
					<guid><?php echo $this->template->kaffahPostPermalink($post, 'post');?></guid>
					<pubDate>
						<?php echo mdate('%l, %F %d, %Y %h:%i %A', strtotime($post['post_date'])); ?>
					</pubDate>
					<description>
						<![CDATA[
							<?php echo character_limiter(strip_tags($post['post_content']), 300); ?>
						]]>
					</description>

					
				</item>			
				
			<?php endforeach; endif;?>
						
		
	</channel>
</rss>