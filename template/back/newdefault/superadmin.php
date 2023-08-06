<!--A Design by W3layouts
Author: W3layout
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE HTML>
<html>
<head>
<title>Kaffah Member Area > Website List</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600' rel='stylesheet' type='text/css'>
<link href="<?php echo get_dir_(dirname(__FILE__), 'css');?>/style.css" rel="stylesheet" type="text/css" media="all" />
<link href="<?php echo get_dir_(dirname(__FILE__), 'css');?>/admin-template.css" rel="stylesheet" type="text/css" media="all" />
<link href="<?php echo get_dir_(dirname(__FILE__), 'css');?>/responsive.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/jquery.ba-hashchange.js"></script>
<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/jquery.ba-bbq.js"></script>
<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/sitesa.js"></script>
<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/custom.js"></script>

</head>
<body>
<div class="blackbg">
	<div class="loadupload">
	</div>
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
				<li><a href="http://www.kaffah.biz/site/member"><img src="<?php echo get_dir_(dirname(__FILE__), 'images');?>/home.png" alt="cara membuat website toko online gratis"/></a></li>
				<li><a href="http://www.kaffah.biz/fitur">Fitur</a></li>
				<li><a href="http://www.kaffah.biz/harga">Akun Berbayar</a></li>
				<li><a href="http://www.kaffah.biz/artikel">Blog</a></li>
				<li><a href="http://store.kaffahbiz.co.id/">Store</a></li>
				<li><a href="http://www.kaffah.biz/dir">Direktori</a></li>
				<li id="accountmenu"><a id="accounta" href="dashboard.html"><em class="picpro"></em></a>
					<div class="accountmenu">
						<ul class="user">
							<li><a title="Dashboard Admin" href="<?php echo base_url();?>site/member/<?php echo $this->session->userdata('dom_id');?>/#dashboard">Dashboard Admin</a></li>
							<li><a title="Setting & Verifikasi" href="<?php echo base_url();?>site/member/<?php echo $this->session->userdata('dom_id');?>/#setting_verify">Setting Akun & Verifikasi</a></li>
							<li><a title="Klik Untuk LogOut!" href="<?php echo base_url();?>logout">LogOut!</a></li>
						</ul>
					</div>
				</li>
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
			  	<h2 class="current"><a href="#">...</a></h2>
			  	<input type="hidden" id="hideblogid" value="<?php echo get_domain_atr('domain_id');?>" />
				<input type="hidden" id="limit" name="limit" value="<?php echo getconfig('limit');?>" />
				<input type="hidden" id="page" name="page" value="" />
				<input type="hidden" id="statusarticle" name="statusarticle" value="-" />

				<a href="#" target="_blank" class="sa sabtn"><em>#Superadmin</em></a>
					
						<ul class="samenu">
							<li><a href="/site/member/#web">#web</a></li>
							<li><a href="/site/member/#productlist">#productlist</a></li>
							<li><a href="/site/member/#articlelist">#articlelist</a></li>
							<li><a href="/site/member/#transactionlist">#transactionlist</a></li>
							<li><a href="/site/member/#memberlist">#memberlist</a></li>
							<li><a href="/site/member/#adminnotif">#adminnotif</a></li>
						</ul>
					
				
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
			

			<div id="bodywrap"></div>
					
			
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
				
  
				
				<p class="top">Jl Raya Cifor, Rt.03 Rw. 08 Bubulak</p>
				<p>(Depan Perum Griya Melati)</p>
				<p>Bogor, Jawa Barat</p>
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
				<p class="link"><span>© All rights reserved</span> </p>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>
</div>
</body>
</html>