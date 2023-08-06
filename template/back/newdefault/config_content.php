<!-- this is not displayed -->
<h1 class="headnote"><a href="#config" class="headbread">Konfigurasi</a><em class="separator"></em><font>Konten</font></h1>
<!-- this is not displayed -->

<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/config_content.js"></script>

<a href="#" class="orangebutton" id="savecontent">Simpan Konfigurasi</a>
<h2 class="headnote">Konfigurasi Konten</h2>

<div class="form">
	<form action="" id="config_content" method="POST">
	<label for="artikel_index" class="form">Artikel Di Index</label>
	<input class="textlong mobile" type="text" name="artikel_index" id="artikel_index" /><br />
	<p class="notes">jumlah artikel yang akan dimunculkan disetiap halaman depan website</p>
	<label for="tagline" class="form">Artikel perKategori</label>
	
	<input type="text" class="textlong mobile" name="artikel_perkategori" id="artikel_perkategori" /><br />
	<p class="notes">jumlah artikel yang akan dimunculkan disetiap kategori website</p>
	
	<label for="tagline" class="form">Produk Di Index</label>
	<input type="text" class="textlong mobile" name="produk_index" id="produk_index" /><br />
	<p class="notes">jumlah produk yang akan dimunculkan disetiap kategori website</p>
	
	<label for="tagline" class="form">Produk perKategori</label>
	<input type="text" class="textlong mobile" name="produk_perkategori" id="produk_perkategori" /><br />
	<p class="notes">jumlah produk yang akan dimunculkan disetiap kategori website</p>	
	
	<label for="tagline" class="form">Tampil Pencarian</label>
	<input type="text" class="textlong mobile" name="post_persearch" id="post_persearch" /><br />
	<p class="notes">jumlah post yang dimunculkan pada hasil pencarian</p>
	</form>
</div>