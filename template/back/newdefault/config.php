<!-- this is not displayed -->
<h1 class="headnote"><a href="#config" class="headbread">Konfigurasi</a><em class="separator"></em><font>Umum</font></h1>
<!-- this is not displayed -->

<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/config.js"></script>

<a href="#" class="orangebutton" id="addblog">Simpan Konfigurasi</a>
<h2 class="headnote">Konfigurasi Umum</h2>

<div class="form">
	<form action="" id="config_general" method="POST">
	<label for="judul" class="form">Judul</label><input class="textlong mobile" type="text" name="judul" id="judul" /><br />
	
	<label for="tagline" class="form">Tagline</label><input type="text" class="textlong mobile" name="tagline" id="tagline" /><br />
	<p class="notes">Tag Line itu semacam moto dari Toko Anda</p>
	
	<label for="alamatweb" class="form">Domain Website</label><input type="text" class="textlong mobile" name="alamatweb" id="alamatweb" /><br />
	<p class="notes">Anda bisa mengganti alamat website, dengan nama domain Anda sendiri atau dengan sub domain website Anda. Butuh bantuan ? Silahkan hubungi <b>support@kaffahbiz.co.id</b></p>
	
	<label for="email" class="form">Email</label><input type="text" name="email" class="textlong mobile" id="email" /><br />
	<p class="notes">Digunakan sebagai pengirim pada pengiriman email kepada member, setiap ada user baru yang mendaftar, atau konfirmasi terkait update news dari website</p>
	</form>
</div>