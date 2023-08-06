<!--A Design by W3layouts
Author: W3layout
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE HTML>
<html>
<head>
<title>KaffahBiz Admin | Login</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600' rel='stylesheet' type='text/css'>
<link href="<?php echo get_dir_(dirname(__FILE__), 'css');?>/style.css" rel="stylesheet" type="text/css" media="all" />

<script src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/jquery.min.js"></script>
</head>
<body>
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
				<li id="accountmenu"><a id="accounta" href="http://www.kaffah.biz/login" class="sstrong">Login!</a>			
			</ul>	
			<script src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/classie.js"></script>
			<script src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/uisearch.js"></script>
			<script>
				new UISearch( document.getElementById( 'sb-search' ) );
			</script>
			<!-- start smart_nav * -->
	        <nav class="nav">
	            <ul class="nav-list">
	                <li class="nav-item"><a href="dashboard.html">Dashboard</a></li>
	                <li class="nav-item"><a href="artikel.html">Artikel</a></li>
	                <li class="nav-item"><a href="produk.html">Produk</a></li>
	                <li class="nav-item"><a href="halaman.html">Halaman</a></li>
	               <li class="nav-item"><a href="komentar.html">Komentar</a></li> 
	               <li class="nav-item"><a href="statistik.html">Statistik</a></li>
	               <li class="nav-item"><a href="follower.html">Follower</a></li>
	                <div class="clear"></div>
	            </ul>
	        </nav>
	        <script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/responsive.menu.js"></script>
			<!-- end smart_nav * -->
		</div>
		<div class="clear"></div>
	</div>
</div>
</div>
<!-- start slider -->
<div class="slider_bg">
<div class="wrap">
	<div class="slider">
		<div id="breadcrumb">
			<div class="container_24">
			  <div class="grid_24">
			  	<h2><a href="<?php echo base_url();?>site/member">Website</a></h2><em class="separator"></em><h2><a href="<?php echo base_url();?>">Login</a></h2>
			  	<input type="hidden" id="hideblogid" value="<?php echo get_domain_atr('domain_id');?>" />
				<input type="hidden" id="limit" name="limit" value="<?php echo getconfig('limit');?>" />
				<input type="hidden" id="page" name="page" value="<?php echo getoffset();?>" />
				<input type="hidden" id="statusarticle" name="statusarticle" value="-" />
			  </div>
			</div>
		</div>
	</div>
</div>
</div>
<!-- start main -->
<div class="main_bg inside">
<div class="wrap">
	<div class="main">
		<div class="content admnewkaffah">
			<!-- start blog_left -->
			<div class="blog_left menuadmin_left formlogin">		
				<div class="blog_main single">	
					<h2>Login</h2>
					<div class="form login">
						<?php echo get_form_error('error_msg');?>
						<form action="<?php echo base_url();?>req/login" id="loginreq" method="POST">
							<label for="email" class="form">Email</label><input value="<?php echo set_value('email');?>" class="textlong" type="text" name="email" id="email" /><?php echo form_error('email');?><br />
							<label for="password" class="form">Password</label><input type="password" class="textlong" name="password" id="password" /><?php echo form_error('password');?><br />
							<input type="checkbox" name="remember"  id="remember"/><label for="remember" class="remember">Ingat Password</label>
							<a href="<?php echo base_url().'forgot_pass';?>" class="forgot_password">Lupa password? Klik di sini</a>
							<br />
							<input type="submit" name="login" value="LogIn Sekarang!" class="bluesubmit" />
						</form>						
					</div>
				 </div>				
			</div>

			<div class="blog_right loginads">		
				<ul>
					<li><a title="Pasang Iklan Disini" href="<?php echo base_url().'halaman/pasang_iklan';?>"><img alt="pasang iklan disini" src="<?php echo get_dir_(dirname(__FILE__), 'images');?>/adsorange.png" /></a></li>
					<li><a title="Pasang Iklan Disini" href="<?php echo base_url().'halaman/pasang_iklan';?>"><img alt="pasang iklan disini" src="<?php echo get_dir_(dirname(__FILE__), 'images');?>/adsorange2.png" /></a></li>
					<div class="clear"></div>
					
				</ul>
			</div>

			<!-- start blog_sidebar -->
			
		<div class="clear"></div>
		</div>
	</div>
</div>
</div>
<div class="footer_bg">
<div class="wrap">
	<div class="footer">
		<div class="span_of_4">
			<div class="span1_of_4">
				<h4>Tentang Kami</h4>
				<p><a href="http://www.kaffah.biz">Kaffah.biz</a> adalah salah satu karya besar dari <a href="http://www.ilmuwebsite.com">Ilmuwebsite</a>, Kami tidak berpartner / berafiliasi dengan lembaga / instansi / sekolah / perusahaan manapun.</p>
				
				<span>Alamat Workshop:</span>
								
				<p class="top">Ilmuwebsite Courses Center <br />Jl. Durian Raya, No. 14, Bantarkemang (Balebinarum), Bogor, Jawa Barat</p>	
				
				<div class="f_icons">
						<ul>
							<li><a class="icon2" href="#"></a></li>
							<li><a class="icon1" href="#"></a></li>
							<li><a class="icon3" href="#"></a></li>
							<li><a class="icon4" href="#"></a></li>
							<li><a class="icon5" href="#"></a></li>
						</ul>	
				</div>
			</div>
			<div class="span1_of_4 newpost">
				<h4>Posting Terbaru</h4>
				<?php echo get_new_update();?>
			</div>
			<div class="span1_of_4 aboutus">
				<h4>Tentang Kami</h4><span><a href="http://www.kaffah.biz/halaman/kontak_kami">Kontak Kami</a></span><span><a href="http://www.kaffah.biz/artikel/news/syarat_dan_ketentuan">Syarat &amp; Ketentuan</a></span><span><a href="http://www.kaffah.biz/artikel/tutorial_video/video_tutorial_dasar_kaffah">Panduan Kaffah</a></span><span></span><h4>Partner Kaffah.biz</h4><span><a href="http://course.ilmuwebsite.com" rel="nofollow">IlmuWebsite Courses Center</a></span><p>Komunitas Berbagi Ilmu Website</p>
			</div>
			<div class="span1_of_4 newweb">
				<h4>Website Terbaru</h4>
				<?php echo get_new_domain();?>
			</div>
			<div class="clear"></div>
		</div>
		<div class="footer_top">
			<div class="f_nav1">
				<ul>
					<li><a href="index.html">home</a></li>
					<li><a href="#">fitur</a></li>
					<li><a href="#">paket berbayar</a></li>
					<li><a href="#">blog</a></li>
					<li><a href="#">direktori</a></li>
					<li><a href="#">Kontak</a></li>
				</ul>
			</div>
			<div class="copy">
				<p class="link"><span>© All rights reserved</span></p>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>
</div>
</body>
</html>
