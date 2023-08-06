<!-- this is not displayed -->
<h1 class="headnote"><a href="#config" class="headbread">Konfigurasi</a><em class="separator"></em><font>SEO</font></h1>
<!-- this is not displayed -->

<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/config_seo.js"></script>

<a href="#" class="orangebutton" id="saveseo">Simpan Konfigurasi</a>
<h2 class="headnote">Konfigurasi SEO</h2>
<p class="headnote">SEO digunakan agar website Anda muncul secara maksimal<br /> dalam pencarian Google, dan Search Engine Lainnya</p>

<div class="form">
<form action="" id="config_seo" method="POST">
	<h3 class="note">Anti Konten Ganda (Duplicate Content)</h3>
	<br />
	<label for="" class="form">URL Canonical</label>
	<input id="url_canonical" value="yes" name="url_canonical" type="checkbox"> 
	<label class="labelchoice" for="url_canonical">Aktifkan URL Canonical (Mengatasi Duplicate Content)</label>

	<br /><br />
	<h3 class="note">SEO Untuk Halaman Home / Index / Beranda</h3><br />
	<label for="" class="form">Home Title</label>
	<textarea id="home_title" name="home_title" class="textarealong"></textarea><br>
	<label for="" class="form">Home Meta Keyword</label>
	<textarea id="home_keyword" name="home_keyword" class="textarealong"></textarea><br>

	<label for="home_description" class="form">Home Meta Description</label>
	<textarea id="home_description" name="home_description" class="textarealong"></textarea><br>

	<br /><br />
	<h3 class="note">SEO Untuk Kategori</h3><br />
	<p class="headnote">Jika diaktifkan maka akan dibuat secara otomatis meta keyword dan meta description untuk setiap kategori, 
	jika tidak diaktifkan maka Anda akan mengisinya secara manual ketika penambahan kategori artikel / kategori produk</p><br />

	<label for="" class="form">Keyword & Desc</label>
	<input id="keyword_description_kategori" value="yes" name="keyword_description_kategori" type="checkbox"> 
	<label class="labelchoice" for="keyword_description_kategori">Aktifkan<b> pembuatan otomatis Meta Keyword dan Meta Description</b> Kategori</label><br>

	<br /><br />
	<h3 class="note">SEO Untuk Halaman dan Artikel</h3><br />
	<p class="headnote">Jika diaktifkan maka akan dibuat secara otomatis meta keyword dan meta description untuk setiap artikel, halaman, maupun produk, 
	jika tidak diaktifkan maka Anda akan mengisinya secara manual ketika penambahan halaman / artikel / produk</p><br />

	<label for="" class="form">Keyword & Desc</label><input id="keyword_description_single" value="yes" name="keyword_description_single" type="checkbox"> 
	<label class="labelchoice" for="keyword_description_single">Aktifkan otomatis Meta Keyword dan Meta Description Tiap Halaman, Artikel, dan Produk</label>


	<br /><br />
	<h3 class="note">SEO Menggunakan Pihak Ke Tiga</h3><br />
	<label for="" class="form">Google Webmaster (Meta)</label>
	<textarea id="webmaster_meta" name="webmaster_meta" class="textarealong"></textarea>
	<p class="notes">Silahkan Daftar di webmaster google kemudian lakukan verifikasi, dan ambil metode alternatif menggunakan meta</p>

	<label for="" class="form">Google Analytic ID</label><input type="text" class="textlong" name="google_analytic" id="google_analytic" value="UA-XXXXXXXX-X"> <br>
	<label for="" class="form">Alexa Verification (Meta)</label>
	<textarea id="alexa_meta" class="textarealong" name="alexa_meta"></textarea><br><br><br>
</form>
</div>