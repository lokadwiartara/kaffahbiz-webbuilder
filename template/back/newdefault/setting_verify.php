<!-- this is not displayed -->
<h1 class="headnote"><a href="#setting_verify" class="headbread">Setting & Verifikasi</a></h1>
<!-- this is not displayed -->
<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/ajaxupload.js"></script>
<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/verify.js"></script>

<a href="#" class="orangebutton" id="savesettingakun">Simpan Setting dan Verifikasi!</a>
<h2 class="headnote">Setting dan Verifikasi Akun</h2>

<div class="form">
	<form action="" id="formverifikasi" method="POST">

		<input type="hidden" name="notpopupverify" id="notpopupverify" value="1" />

		<h3 class="note">Biodata Pemilik Akun / Website / Toko Online</h3>
		<p class="headnote">Mohon diisi dengan data yang riil, karena kami akan memvalidasinya secara fisik!</p><br />

		<label class="form" for="nama_lengkap">Nama Lengkap</label>
		<input type="text" class="textlong"  name="nama_lengkap" id="nama_lengkap" value="">
		<br>

		<label class="form" for="facebook">Alamat Facebook</label>
		<input type="text" class="textlong" name="facebook_id" id="facebook_id" value="">
		<p class="notes">Contoh : http://www.facebook.com/loka.dwiartara</p><br />			

		<label class="form" for="jenis_kelamin">Jenis Kelamin</label><input type="radio" name="jenis_kelamin" id="pria" value="pria" /><label for="pria" class="insideinput">Pria</label> <input type="radio" name="jenis_kelamin" id="wanita" /><label for="wanita" value="wanita" class="insideinput">Wanita</label><br />			

		<label class="form" for="tempat_lahir">Tempat Lahir</label>
		<input type="text" class="textlong" name="tempat_lahir" id="tempat_lahir" value="">
		<br>

		<label class="form" for="tanggal_lahir">Tgl.Lahir (d/m/y)</label><input type="text"  id="day" name="day" class="textshort dates">
		<select name="month" id="month" class="formdropdown midlelong">
		<option value="01">Januari</option>
		<option value="02">Februari</option>
		<option value="03">Maret</option>
		<option value="04">April</option>
		<option value="05">Mei</option>
		<option value="06">Juni</option>
		<option value="07">Juli</option>
		<option value="08">Agustus</option>
		<option value="09">September</option>
		<option value="10">Oktober</option>
		<option value="11">November</option>
		<option value="12">Desember</option>
		</select><input type="text" name="year" id="year" name="year" class="textshort  dates years"><br>					

		<label class="form" for="no_handphone">No. HP</label>
		<input type="text" class="textlong" name="no_handphone" id="no_handphone" value="">
		<br>

		<label class="form" for="no_telepon">No. Telp</label>
		<input type="text" class="textlong" name="no_telepon" id="no_telepon" value="">
		<p class="notes">Jika Anda tidak memiliki telepon maka isi saja dengan "-" (tanpa tanda kutip)</p>		

		<label class="form" for="provinsi">Provinsi</label>
		<select name="provinsi" id="provinsi" class="formdropdown midlelong">	
		<option value="">Pilih Provinsi</option>
		<option value="aceh">Aceh</option>
		<option value="bali">Bali</option>
		<option value="banten">Banten</option>
		<option value="bengkulu">Bengkulu</option>
		<option value="gorontalo">Gorontalo</option>
		<option value="jakarta">Jakarta</option>
		<option value="jambi">Jambi</option>
		<option value="jawa_barat">Jawa Barat</option>
		<option value="jawa_tengah">Jawa Tengah</option>
		<option value="jawa_timur">Jawa Timur</option>
		<option value="kalimantan_barat">Kalimantan Barat</option>
		<option value="kalimantan_selatan">Kalimantan Selatan</option>
		<option value="kalimantan_tengah">Kalimantan Tengah</option>
		<option value="kalimantan_timur">Kalimantan Timur</option>
		<option value="kalimantan_utara">Kalimantan Utara</option>
		<option value="kepulauan_bangka_belitung">Kepulauan Bangka Belitung</option>
		<option value="kepulauan_riau">Kepulauan Riau</option>
		<option value="lampung">Lampung</option>
		<option value="maluku">Maluku</option>
		<option value="maluku_utara">Maluku Utara</option>
		<option value="nusa_tenggara_barat">Nusa Tenggara Barat</option>
		<option value="nusa_tenggara_timur">Nusa Tenggara Timur</option>
		<option value="papua">Papua</option>
		<option value="papua_barat">Papua Barat</option>
		<option value="riau">Riau</option>
		<option value="sulawesi_selatan">Sulawesi Selatan</option>
		<option value="sulawesi_tengah">Sulawesi Tengah</option>
		<option value="sulawesi_tenggara">Sulawesi Tenggara</option>
		<option value="sulawesi_utara">Sulawesi Utara</option>
		<option value="sumatera_barat">Sumatera Barat</option>
		<option value="sumatera_selatan">Sumatera Selatan</option>
		<option value="sumatera_utara">Sumatera Utara</option>
		<option value="yogyakarta">Yogyakarta</option>							
		</select>
		<br>

		<label class="form" for="provinsi_kota">Kota</label>
		<select name="provinsi_kota" id="provinsi_kota" class="formdropdown midlelong action edit place">				
		</select>
		<br>	

		<label class="form" for="alamat">Alamat</label>
		<textarea class="textarealong" name="alamat" id="alamat"></textarea>
		<br>		

		<label class="form" for="no_ktp">No. KTP</label>
		<input type="text" class="textlong" name="no_ktp" id="no_ktp" value="">
		<br>

		<label class="form"  id="label_img_ktp" for="img_ktp">Scan KTP</label>
		<input type="file"  class="imagefile imagefilektp" name="userfile" id="img_ktp" />
	  	<input type="hidden" value="" id="hide_img_ktp" name="img_ktp" /><br />
		<label class="form"  id="label_img_id" for="img_id">Scan Pembayaran Listrik/Telepon Terakhir</label>
		<input type="file" class="imagefile imagefileid" name="userfile" id="img_id" />
	  	<input type="hidden" value="" id="hide_img_id" name="img_id" /><br />

	  	<?php $dom_reseller = $this->session->userdata('dom_reseller'); if(!empty($dom_reseller)): ?>
		  	<br />
		  	<h3 class="note">Info Rekening Bank</h3>
		  	<p class="headnote">Bagian ini hanya muncul jika Anda adalah reseller kaffahstore, mohon diisi untuk kemudahan pembayaran komisi Anda</p><br />

		  	<label class="form" for="bank">Bank</label>
			<input type="text" class="textlong"  name="bank" id="bank" value=""><br />

			<label class="form" for="no_rek">No.Rekening</label>
			<input type="text" class="textlong"  name="no_rek" id="no_rek" value=""><br />

			<label class="form" for="atas_nama">Atas Nama</label>
			<input type="text" class="textlong"  name="atas_nama" id="atas_nama" value="">
	  	<?php endif;?>

	  	<br />
	  	<h3 class="note">Info Akun Login</h3>
		<p class="headnote">Kosongkan password jika tidak ingin mengganti password</p><br />
		
		<label for="" class="form">Email</label>
		<input class="textshort disable" type="input"  disabled="disabled" value="<?php echo $this->main->getUser('email');?>" /><br />

		<label for="" class="form">Password Baru</label>
		<input class="textshort" type="password" name="password" id="password" /><br />

		<label for="" class="form">Konfirmasi Password</label>
		<input class="textshort" type="password" name="cfmpassword" id="cfmpassword" />
		<p class="notes">Password diisi sama dengan password baru</p>

	</form>
</div>
