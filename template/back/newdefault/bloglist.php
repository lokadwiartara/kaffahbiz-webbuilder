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
<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/jquery.ba-hashchange.js"></script>
<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/jquery.ba-bbq.js"></script>
<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/site.js"></script>
<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/custom.js"></script>
<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/bloglist.js"></script>

</head>
<body>

<div class="blackbg">
	<div class="popup">
	<a class="close" href="">x</a>
	<div class="wrappop">

		<h2 class="headpop">Buat Website Baru</h2>
		<form id="formaddblog" method="POST">
			<label for="title">Judul</label><input type="text" id="title" name="title" /><br />

			<label for="description">Deskripsi</label>
			<textarea id="description" class="desc" name="desc"></textarea>
			<label for="domain">Alamat Website</label><input type="text" class="shorttext domain" autocomplete="off" id="newdomain" name="domain" />
			<select name="domaintld" id="domaintld" class="select">
				<option value=".web-id.co">.web-id.co</option>
				<option value=".webz.my.id">.webz.my.id</option>
			</select>
			<input type="hidden" name="hiddenval" id="hiddenval" value="1" />
			<p class="notes">Silahkan masukkan alamat website, contoh: <b>demo.kaffah.biz</b></p>
			<label for="template">Pilih Tampilan</label>

			<div class="template">
				<div class="single" id="1" style="background:url(http://www.lokal.co/template/front/coolblue/ss-thumb.png)"></div>
				<input type="hidden" id="templatechoose" name="templatechoose" value="1" />
			</div>

			<input type="submit" name="add" class="disable" disabled="disable" id="addnewbtn" value="Buat Sekarang!" />
		</form>
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
				<li><a href="http://www.kaffah.biz/dir">Direktori</a></li>
				<li id="accountmenu"><a id="accounta" href="dashboard.html"><em class="picpro"></em></a>
					<div class="accountmenu">
						<ul class="user">
							<li><a title="Lihat Profil" href="<?php echo base_url();?>dir/lokadwiartara">Lihat Profil</a></li>
							<li><a title="Konfigurasi" href="<?php echo base_url();?>site/member/#konfigurasi">Konfigurasi</a></li>
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
		<h3>Home / Member Area / Semua Website</h3>
	</div>
</div>
</div>
<!-- start main -->
<div class="main_bg inside">
<div class="wrap">
	<div class="main">
		<div class="content admnewkaffah">
			<!-- start blog_left -->
			<div class="blog_left menuadmin_left">		
				<div class="blog_main single">	
					<h2>Daftar Website</h2>
					
				 </div>
				
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
				<p><a href="http://www.kaffah.biz">Kaffah.biz</a> adalah salah satu karya besar dari <a href="http://www.cbs-bogor.net">Cyber Business School</a>, bermanfaat untuk UKM yang sedang mencari media promosi online yang murah bahkan gratis.</p>
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
				<span><a href="#">Hidup Dengan Passion Tanpa Visi & Misi = Orang Berjalan Tapi Pincang</a></span>
				<p>10 September 2014</p>
				<span><a href="#">Teknik Marketing Jitu Jualan Produk dan Jasa Di Zaman Serba IT</a></span>
				<p>17 July 2014</p>
				<span><a href="#">Ramadhan Bulan Penuh Keberkahan. Ada yang spesial dari KaffahBiz</a></span>
				<p>29 June 2014</p>
			</div>
			<div class="span1_of_4 aboutus">
				<h4>Tentang Kami</h4><span><a href="http://www.kaffah.biz/kontak_kami">Kontak Kami</a></span><span><a href="http://www.kaffah.biz/artikel/news/syarat_dan_ketentuan">Syarat &amp; Ketentuan</a></span><span><a href="http://www.kaffah.biz/artikel/tutorial_video/video_tutorial_dasar_kaffah">Panduan Kaffah</a></span><span></span><h4>Partner Kaffah.biz</h4><span><a href="http://www.cbs-bogor.net" rel="nofollow">Cyber Business School</a></span><p>Sekolahnya Para TeknoPreneur</p><span><a href="http://www.ilmuwebsite.com" rel="nofollow">Ilmu Website</a></span><p>Komunitas Berbagi Ilmu Website</p>
			</div>
			<div class="span1_of_4 newweb">
				<h4>Website Terbaru</h4><span><a href="http://www.makeups.kaffah.biz" target="_blank" class="sitename">Website www.makeups.kaffah.biz</a></span><p>makeups.kaffah.biz</p><span><a href="http://www.fajarclothing.webz.my.id" target="_blank" class="sitename">Website www.fajarclothing.webz.my.id</a></span><p>fajarclothing.webz.my.id</p><span><a href="http://www.auliabusana.ol-shop.net" target="_blank" class="sitename">Website www.auliabusana.ol-shop.net</a></span><p>auliabusana.ol-shop.net</p>
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