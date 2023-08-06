<!-- this is not displayed -->
<h1 class="headnote"><a href="../#product" class="headbread">Produk</a><em class="separator"></em><font>Tambah Baru</font></h1>

<script type="text/javascript" src="<?php echo get_asset('js');?>ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/jquery.price_format.2.0.min.js"></script>
<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/ajaxupload.js"></script>
<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/new_product.js"></script>
<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/media.js"></script>

<a href="/site/admin/31/#article" class="greybutton prodbtn" id="tutupblog">Tutup [x]</a>
<a href="/site/admin/1/full/#save" class="greybutton prodbtn" id="simpanblogproduct">Simpan Sebagai Draft</a>
<a href="/site/admin/1/full/#save" class="orangebutton prodbtn" id="addblogyesproduct">Terbitkan Produk Ini!</a>

<h2 class="headnote full">Tambah Produk Baru</h2>

<!--<input type="submit" onclick="tes()" />-->

<div class="newarticle">
	<input type="text" name="titlearticle" class="longfull" autocomplete="off" value="Tuliskan nama Produknya di sini ..." onFocus="if(this.value=='Tuliskan nama Produknya di sini ...')this.value=''" onBlur="if(this.value=='')this.value='Tuliskan nama Produknya di sini ...'" />
	<input type="hidden" name="status" id="status" value="draft" />
	<input type="hidden" name="post_type" id="post_type" value="product" />
	<p class="warning">Warning ...</p>
	<div id="settingbar">
		<div class="addmedia"><a href="" name="addmedia" class="addmedia">Tambah File</a></div>
		<h3 class="productset headwrap prod">Info Produk</h3>
		<h3 class="categoryset headwrap first prod">Kategori</h3>		
		<h3 class="timepubset headwrap prod">Waktu</h3>
		
		<h3 class="seoset headwrap prod">Setelan SEO</h3>
		<h3 class="discussset headwrap prod">Diskusi</h3>
		
		<h3 class="shareset headwrap last prod">Sharing To?</h3>
		
		<div class="categoryset wraptop">
			<div class="wrappset catlistli">
				<ul class="category"></ul>
				
				<p class="note">Silahkan dipilih kategorinya.Di engine terbaru ini, Anda dianjurkan hanya bisa memilih satu kategori, karena khawatir ada duplikasi berdampak buruk kepada SEO (Search Engine Optimization)</p>
				
				<div class="newcat">
					<a class="newcat" href="#">+ Tambah Kategori Baru</a>
					<div class="formnewcatwrap">
						<form id="formaddcat">
							<input type="text" autocomplete="off" name="title" id="title" value="Isi Kategori Baru" onFocus="if(this.value=='Isi Kategori Baru')this.value=''" onBlur="if(this.value=='')this.value='Isi Kategori Baru'" />
							<select name="parentcat" id="parentcat">
								<option value="0">-- induk kategori--</option>
							</select>
							<input  type="hidden" id="blog_id" name="blog_id" />
							<input  type="hidden" id="category_type" name="category_type" value="category_product" />
							<input  type="hidden" id="desc" name="desc" />
							<input type="submit" name="add" class="disable" disabled="disable" id="addnewbtn" value="Buat Sekarang!" />
						</form>	
					</div>
				</div>
			</div>
		</div>
		<div class="timepubset wraptop">
			
			<div class="wrappset timewrap"> <em>Tanggal : dd/mm/yyyy</em>
				<input type="text" name="date" value="<?php echo date('d');?>" class="timewrapshort" /> / 
				
				<?php 	$this->load->helper('form');?>
				<?php 	$monthselect = date(m); 
						$month = array(
										'01' => 'Januari', '02' => 'Februari',
										'03' => 'Maret', '04' => 'April',
										'05' => 'Mei', '06' => 'Juni', '07' => 'Juli',
										'08' => 'Agustus', '09' => 'September',
										'10' => 'Oktober', '11' => 'November',
										'12' => 'Desember',
									);
										
						echo form_dropdown('month', $month, $monthselect, 'class="timewrapselect"');
				?>
				
				 / 
				
				<input type="text" name="year" class="timewrapshort" value="<?php echo date('Y');?>" />
				<em class="time">Pukul : hh:mm </em>
				<input type="text" name="hour" value="<?php echo date('H');?>" class="timewrapshort" /> : 
				<input type="text" name="minute" value="<?php echo date('i');?>" class="timewrapshort" />
				
				<p class="note">Note : Artikel ini akan terbit disesuaikan waktu yang Anda tetapkan ...</p>
			</div>
		</div>
		<div class="seoset wraptop">
			
			<div class="wrappset seo">
				<label>Title</label>
				<input type="text" name="metatitle" class="longtextinwrap" />
				<p class="note">Title ini adalah meta title yang berfungsi untuk SEO Maksimal, karena search engine sangat mengedepankan meta title ini</p>
				<br />
				<label>Meta Keyword</label>
				<textarea name="metakeyword"></textarea>
				<p class="note">Keyword (kata kunci) apa yang akan Anda bidik, agar website Anda muncul di search engine setiap kali orang mencari kata tersebut di Search Engine, </p>
				<br />
				<label>Meta Description</label>
				<textarea name="metadescription"></textarea>
				<p class="note">Silahkan isi, Meta Description agar artikel ini memiliki SEO Score yang bagus dalam Search Engine. Dan website Anda memiliki peringkat yang bagus dalam search Engine, seperti Google maupun Bing. </p>
				
			</div>
		</div>
		<div class="discussset wraptop">
			
			<div class="wrappset discuss">
				<label class="label">Komentar</label><input id="comment" name="comment" type="checkbox" /> <label for="comment" class="choice">Aktifkan Komentar pada Artikel ini</label> <br />
				<label class="label">Notifikasi</label><input id="notification" name="notification" type="checkbox" /> 
				<label class="choice" for="notification" >Email Notifikasi Untuk User dan Admin</label> 
				<p class="note">(notifikasi ini akan dikirimkan ke user yang telah berkomentar, diskusi terbaru dikomentar tersebut, dan juga dikirimkan ke admin)</p>
			</div>
		</div>
		<div class="productset wraptop">
			<div class="wrappset writer">
				<div class="productdetail">
					<h3 class="note pic">Gambar/Photo Produk</h3>
					<div class="imguploadwrap"><br class="floating" /></div>
					
					<form method="POST" id="formuploadfileproduct" action="/reqpost/upload" enctype="multipart/form-data">
						<input id="massupload" type="file" class="jfilestyle" data-theme="blue" data-input="false" name="userfile" />
					</form>

					<!--<a href="" class="btnuploadimg">Upload Gambar!</a>--><br />
					<p class="note full">upload gambar/photo produk Anda dengan mengklik <b>tombol di atas!</b> (upload gambar bisa lebih dari satu)</p>

					<div class="info_product">
						<br /><br /><h3 class="note price">Harga dan Ketersediaan</h3><br />
						<label>Kode Produk</label><input type="text" class="singleinput" name="product_code" /><br />
						<p class="notehalf">Kode Produk digunakan untuk memudahkan konsumen dalam melakukan transaksi (contoh penulisan : <b>KI-0324</b>)</p><br />
						
						<label>Harga Lama</label><input type="text" class="singleinput" name="product_price_old" id="product_price_old" /><br />
						<p class="notehalf">Harga lama (harga ditampilkan dengan tanda coret / tidak ditampilkan sama sekali) contoh penulisan : <b>331.924</b></p><br />

						<label>Harga</label><input type="text" class="singleinput" name="product_price" id="product_price" /><br />
						<p class="notehalf">Ini harga yang akan ditampilkan contoh penulisan : <b>331.924</b></p><br />
						
						<label>Stok Barang</label><input type="text" class="singleinput" name="product_stock" /><br />
						<p class="notehalf">stok barang <b>tidak usah diisi jika stok selalu ada</b> , dan tuliskan <b>0</b> atau <b>-</b> jika barang kosong </p>						
						



						<?php global $SConfig; if($SConfig->storeID == $this->session->userdata('dom_id')):?>
							<!-- INFO PRODUK UNTUK RESELLER -->
							<br /><br /><h3 class="note">Info Harga Reseller</h3><br />
							<label for="" class="form">Produk Reseller</label>
							<input id="product_reseller" value="yes" name="product_reseller" type="checkbox">
							<label class="labelchoice" for="product_reseller">Jadikan sebagai produk yang di-reseller-kan</label><br />
							<label>Modal (Rp)</label><input type="text" class="singleinput" id="harga_modal" name="harga_modal" /><br />						
							<label>Reseller (Rp)</label><input type="text" class="singleinput" id="komisi_reseller" name="komisi_reseller" /><br />						
							<p class="notehalf">Berapa Rupiah yang diberikan KS (kaffahstore) untuk komisi reseller</p><br />						
							<label>Komisi KS (Rp)</label><input type="text" class="singleinput" name="komisi_ks" id="komisi_ks" /><br />
						<?php endif;?>

					</div>										

					<br />
					<br class="floating" />
					<div class="leftdetailproduct">
						<h3 class="note box">Atribut Berat, Tinggi, Panjang</h3><br />
						<label>Berat/Bobot</label><input type="text" name="berat" class="sort" />
						<select name="satuanberat"><option value="gram">gram</option></select><br />
						<label>Tinggi</label><input type="text" name="tinggi" class="sort" />
						<select name="satuantinggi"><option value="cm">cm</option></select><br />
						<label>Lebar</label><input type="text" name="lebar" class="sort" />
						<select name="satuanlebar"><option value="cm">cm</option></select><br />
						<label>Panjang</label><input type="text" name="panjang" class="sort" />
						<select name="satuanpanjang"><option value="cm">cm</option></select><br />
						<p class="notehalf">atribut-atribut ini digunakan dalam perhitungan <b>ongkos kirim</b></p><br />	
					</div>
					
					<div class="rightdetailproduct">
						<h3 class="note puz">Atribut Warna dan Ukuran</h3><br />
						<label>Warna</label><input type="text" name="product_colour" /><br />
						<p class="notehalf">masukkan warnanya, dipisahkan dengan koma<br />contoh: <b>kuning,hijau,merah</b></p><br />
						<label>Ukuran</label><input type="text" name="product_size" />
						<p class="notehalf">masukkan ukurannya, dipisahkan dengan koma<br />contoh: <b>S,M,L,XL</b></p><br />	
					</div>
					<br class="floating" />
				</div>
				
			</div>
		</div>
		
		
				<div class="shareset wraptop">
			<div class="wrappset share">
				<label class="label">Share to <b>Facebook</b></label><input id="tofacebook" name="tofacebook" type="checkbox" /><label for="tofacebook" class="choice">Aktifkan otomatis sharing ke facebook </label><br />
				<label class="label">Share to <b>Twitter</b></label><input id="totwitter" name="totwitter" type="checkbox" /><label for="totwitter" class="choice">Aktifkan otomatis sharing ke twitter </label><br />
				<label class="label">Share di <b class="direct">Kaffah Dir!</b></label><input id="todirect" name="todirect" type="checkbox" /><label for="todirect" class="choice">Aktifkan otomatis sharing ke <b class="direct">Dir!</b>!</label><br />
				<p class="note"><b class="direct">Dir!</b> adalah layanan <b class="kaffdir">Direktori Kaffah</b> beralamat di <a target="_blank" class="linkkaffdir" href="http://www.kaffah.biz/dir">www.kaffah.biz/dir</a>, Produk Anda akan dilihat oleh lebih banyak orang</p>
				<br />
				<label class="label">Broadcast Email</label><input name="broadcastemail" id="broadcastemail" type="checkbox" /><label for="broadcastemail" class="choice">Sebarkan artikel ini ke follower-follower</label>
				<p class="note">Broadcast email adalah fitur baru dari kaffah.biz yang membuat Anda bisa memarketingkan website Anda ke rekan, kerabat, kolega, keluarga dan lain-lain</p>
			</div>
		</div>
	</div>
	
	<textarea name="editor1" id="editor1" rows="100">Deskripsi produknya di sini ...</textarea>



</div>
