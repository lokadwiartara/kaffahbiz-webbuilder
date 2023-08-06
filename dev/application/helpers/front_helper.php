<?php
	/*  helper ini digunakan untuk bantuan admin 
		baik template maupun yang lainnya
	*/
	
	/* masukkan kebutuhan file-file */
	require "inc/comment_front_helper.php";
	require "inc/front_reg_helper.php";


	function meta_description($content){
		$display = '<meta name="description" content="'.$content.'" />';
		return $display;
	}
	
	function meta_keyword($content){
		$display = '<meta name="keywords" content="'.$content.'" />';
		return $display;
	}

	function alexa_meta($content){
		$display = $content;
		return $display;
	}
		
	
	function google_webmaster($content){
		$display = $content;
		return $display;
	}
	
	
	function g_analytics($id, $domain){
	//	ga('create', 'UA-42257435-1', 'naonwa-e.com');
$display = "<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', '$id', '$domain');
  ga('send', 'pageview');

</script>";
		
		return $display ;
	}	


?>