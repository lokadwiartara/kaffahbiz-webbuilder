<!-- this is not displayed -->
<h1 class="headnote"><a href="#config" class="headbread">Konfigurasi</a><em class="separator"></em><font>Toko Online</font></h1>
<!-- this is not displayed -->


<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/config_estore.js"></script>


<a href="#" class="orangebutton" id="savestore">Simpan Konfigurasi</a>
<h2 class="headnote">Konfigurasi Toko Online</h2>

<div class="form">
	<form action="" id="config_store" method="POST">
		<h3 class="note">Alamat Asli Toko</h3>
		<p class="headnote">Dapat menggunakan alamat rumah jika tidak Ada (Mohon diisi selengkap mungkin, agar toko/website Anda terdaftar di market place dan ngaffah!)</p>
		<br />

		<label for="" class="form">Provinsi</label>
		<?php echo provinsi_dropdown();?>
		<br />
		<label for="" class="form">Kota/Kabupaten</label>
		<?php echo provinsi_kota_dropdown();?><br />

		<label for="" class="form">Telepon</label>
		<input class="textshort" type="text" name="telepon" id="telepon" /><br />

		<label for="" class="form">Handphone</label>
		<input class="textshort" type="text" name="handphone" id="handphone" /><br />

		<label for="" class="form">Alamat Lengkap</label>
		<textarea id="alamat_lengkap" class="textarealong" name="alamat_lengkap"></textarea><br><br>

		<h3 class="note">Daftar No.Rekening / Media Pembayaran</h3>
		<p class="headnote">No.rekening berfungsi sebagai alat pembayaran pada transaksi di toko online milik Anda. <b>Klik +tambah rekening</b> untuk menambahkan daftar rekening ANda</p><br />

		<div id="reknumber">
			<div class="setrek"> 
				<h4 class="headrekening">No. Rekening Ke 1</h4> <a href="" class="delbttn">(klik untuk hapus)</a><br />
				<label for="" class="form">Jenis Rekening</label>
				<select name="jenis_rekening[]" class="formdropdown midlelong" id="jenis_rekening"><option value="0">Pilih Rekening</option><option value="BCA">BCA</option><option value="BNI">BNI</option><option value="Mandiri">Bank Mandiri</option><option value="BRI">BRI</option><option value="Syariah_Mandiri">Bank Syariah Mandiri</option><option value="BRI_Syariah">BRI Syariah</option><option value="BNI_Syariah">BNI Syariah</option></select><br /> 
				<label for="" class="form">No. Rekening</label><input class="textshort" type="text" name="no_rek[]" id="no_rek" /><br />
				<label for="" class="form">Atas Nama</label><input class="textshort" type="text" name="atasnama[]" id="atasnama" /><br />
			</div> 				
		</div>
		<a href="" class="btnaddd" id="addrekening">+ Tambah Rekening</a><br /><br />

		<!--<h3 class="note">Global Positioning System (GPS)</h3><br />
		<p class="headnote">GPS ini berfungsi untuk memudahkan orang mencari dimana posisi peta toko/tempat Anda</p><br />
		<label for="" class="form">Latitude/Longitude</label>
		<input class="textshort" type="text" name="lat" id="lat" /> / <input class="textshort" type="text" name="lon" id="lon" /> <input type="submit" id="ambilgps" value="+Ambil"  class="btnform">
		<br /><br />-->

		<h3 class="note">Pemeriksaan / Checkout</h3><br />
		<label for="konfigurasi_umum" class="form">Modul OngKir JNE</label><input id="jne_modul" value="yes" name="jne_modul" type="checkbox"><label class="labelchoice" for="jne_modul">Aktifkan Modul JNE untuk ongkos kirim.</label>
		<p class="notes">(Khusus wilayah JaBoDeTaBek, Bandung, Yogyakarta, Semarang, Surabaya). Jika kota Anda tidak terdaftar namun Anda memiliki databasenya berupa file EXCEL silahkan hubungi Admin.</p>
		<label for="konfigurasi_umum" class="form">Kota</label>
		<select class="formdropdown midlelong" name="kota_ongkir" id="kota_ongkir">
			<option value="-">Pilih Kota UNtuk Ongkir JNE</option>
			<option value="jabodetabek">JaBoDeTaBek</option>
			<option value="bandung">Bandung</option>
			<option value="yogyakarta">Yogyakarta</option>
			<option value="semarang">Semarang</option>
			<option value="surabaya">Surabaya</option>					
		</select>
		

		<br />
		<h3 class="note">Email Pembelian</h3><br />
		<label for="" class="form">Template Email</label>
		<textarea id="template_email" class="textarealong" name="template_email"></textarea><br><br>
		<h3 class="note">Konfirmasi Pembayaran</h3><br />
		<label for="" class="form">Halaman Konfirmasi</label>
		<?php echo form_dropdown('hal_pil_konfirmasi', getpage(), '', 'class="formdropdown midlelong" id="hal_pil_konfirmasi"');?><br />
		<p class="notes">Jika tidak Ada di bagian pilih halaman, silahkan menuliskannya di bawah ini (halaman lain)</p>

		<label for="" class="form">Halaman lain</label>
		<input class="textshort" type="text" name="title_halaman" id="title_halaman" /><br />

		<label for="" class="form">Isi Hal. Konfirmasi</label>
		<textarea id="hal_konfirmasi" class="textarealong" name="hal_konfirmasi"></textarea>
		<br /><br /><br />
	</form>
</div>