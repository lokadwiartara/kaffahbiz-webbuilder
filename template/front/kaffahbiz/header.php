<!--A Design by W3layouts
Author: W3layout
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE HTML>
<html>
<head>
<link expr:href="<?php echo current_url();?>" hreflang="x-default" rel="alternate"/>
<title><?php  echo title();?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="description" itemprop="description" content="cara membuat website dan toko online gratis selamanya? disini tempatnya, kurang dari 1 menit, cepat dan mudah" />
<meta name="keyword" itemprop="keyword" content="cara membuat website, website gratis, toko online gratis" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600' rel='stylesheet' type='text/css'>
<link href="<?php echo get_dir_(dirname(__FILE__), 'css');?>/style.css" rel="stylesheet" type="text/css" media="all" />
<link href="<?php echo get_dir_(dirname(__FILE__), 'css');?>/responsive.css" rel="stylesheet" type="text/css" media="all" />
<link href="<?php echo base_url().'template/front/kaffahbiz/dir/js/';?>social-buttons.css" rel="stylesheet" type="text/css" media="all" />
<!--  jquery plguin -->
<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/jquery.min.js"></script>
<!-- start gallery -->
	<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/jquery.easing.min.js"></script>	
	<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/jquery.mixitup.min.js"></script>
	<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/site.js"></script>
	<script type="text/javascript" src="<?php echo base_url().'template/front/kaffahbiz/dir/js/';?>social-buttons.js"></script>
<!-- Add fancyBox main JS and CSS files -->
<link href="<?php echo get_dir_(dirname(__FILE__), 'css');?>/magnific-popup.css" rel="stylesheet" type="text/css">
<script src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/jquery.magnific-popup.js" type="text/javascript"></script>
		<script>
			$(document).ready(function() {
				$('.popup-with-zoom-anim').magnificPopup({
					type: 'inline',
					fixedContentPos: false,
					fixedBgPos: true,
					overflowY: 'auto',
					closeBtnInside: true,
					preloader: false,
					midClick: true,
					removalDelay: 300,
					mainClass: 'my-mfp-zoom-in'
				});
				

				$('#accountmenu a#accounta').click(function(eve){
					eve.preventDefault();
					$('div.accountmenu').toggle(10);
					$('div.login').toggle(10);
					$(this).children('em').toggleClass('bottomnone');
				});


				$('#accountmenu ul.user li a').click(function(eve){			
					$('div.accountmenu').slideUp(10);
					$('div.login').slideUp(10);
					$('a#accounta em').removeClass('bottomnone');
					return true;
				});
				
			});
		</script>

</head>
<body>
<?php echo kaffah_init();?>

<div class="blackbg">
	<div class="loadupload"></div>
	<div class="popup">
		<a class="close" href="">x</a>
		<div class="wrappop">
		</div>
	</div>
</div>

<!-- start header -->
<div class="header_bg">
<div class="wrap">
	<div class="header">
		<div class="logo">
			<h1><a href="http://www.kaffah.biz"><img src="<?php echo get_dir_(dirname(__FILE__), 'images');?>/logo2.png" alt="cara membuat website toko online gratis"/></a></h1>
		</div>
		<div class="h_right">
			<ul class="menu">
				<li><a href="http://www.kaffah.biz/"><img src="<?php echo get_dir_(dirname(__FILE__), 'images');?>/home.png" alt="cara membuat website toko online gratis"/></a></li>
				<li><a href="http://www.kaffah.biz/fitur">Fitur</a></li>
				<li><a href="http://www.kaffah.biz/harga">Akun Berbayar</a></li>
				<li><a href="http://www.kaffah.biz/artikel">Blog</a></li>
				<li><a href="http://store.kaffahbiz.co.id/">Store</a></li>
				<li><a href="http://www.kaffah.biz/dir">Direktori</a></li>
				<li id="accountmenu"><?php echo k_reg_menu_login();?></li>
				
			</ul>
			<!--<div id="sb-search" class="sb-search">
				<form>
					<input class="sb-search-input" placeholder="Pencarian..." type="text" value="" name="search" id="search">
					<input class="sb-search-submit" type="submit" value="">
					<span class="sb-icon-search"></span>
				</form>
			</div>-->
			<script src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/classie.js"></script>
			<script src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/uisearch.js"></script>
			<script>
				new UISearch( document.getElementById( 'sb-search' ) );
			</script>
			<!-- start smart_nav * -->
	        <nav class="nav">
	            <ul class="nav-list">
					<li class="nav-item"><a href="http://www.kaffah.biz/fitur">Fitur</a></li>
					<li class="nav-item"><a href="http://www.kaffah.biz/harga">Akun Berbayar</a></li>
					<li class="nav-item"><a href="http://www.kaffah.biz/artikel">Blog</a></li>
					<li class="nav-item"><a href="http://store.kaffahbiz.co.id/">Store</a></li>
					<li class="nav-item"><a href="http://www.kaffah.biz/dir">Direktori</a></li>
					<li class="nav-item"><a href="http://www.kaffah.biz/login">Login</a></li>
	                <div class="clear"></div>
	            </ul>
	        </nav>
	        <script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/responsive.menu.js"></script>
			<!-- end smart_nav * -->
		</div>
		<div class="clear"></div>
	</div>
</div>

