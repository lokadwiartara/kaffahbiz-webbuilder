<h1 class="headnote"><a href="#web" class="headbread">Superadmin</a><em class="separator"></em><font>Daftar Semua Produk</font></h1>
<!-- this is not displayed -->

<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/refresh_article.js"></script>
<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/productlist.js"></script>

<h2 class="headnote">Daftar Semua Produk</h2>

<div id="navpostproduct">
	<div class="right">
		<div class="postwrap postsearch"><input  type="text" name="search" id="search" /></div>
		<div class="postwrap postpaging">
			<a href="" class="prevproduct"></a>
			<strong>
				<font id="currpage" class="crnt">1</font>
				<div id="numcountproduct"></div>
			</strong>
			<a class="forwardproduct" href=""></a>
			
		</div>
	</div>

	<div class="postwrap wrapcheckall"><input title="Check All" type="checkbox" name="checkall" id="checkall"/></div>
	<div class="postwrap postaction"><em class="postaction">Aksi Produk</em>
		<div id="postaction">
			<ul>				
				<li id="masspublish">Terbitkan</li>	
				<li id="massdraft">Jadikan Draft</li>
				<li id="massundir">Cabut dari /DIR</li>
				<li id="massdel">Hapus</li>	
			</ul>
		</div>
	</div>

	<div class="postwrap postfilter postsort">
		<em class="postfilter sortproduct">Sortir Produk</em>
		<input type="hidden" name="sortproduct" id="sortproduct" value="all" />
		<div id="productsort" class="postsortproduct">
			<ul>
				<li class=""><a class="catfilter" href="" name="all">Semua Produk</a></li>
				<li class=""><a class="catfilter" href="" name="warn">Bermasalah (warning)</a></li>
				<li class=""><a class="catfilter" href="" name="hot">Produk HOT!</a></li>
				<li class=""><a class="catfilter" href="" name="cart">Paling Laris</a></li>
				<li class=""><a class="catfilter" href="" name="see">Paling Banyak Dilihat</a></li>
				<li class=""><a class="catfilter" href="" name="comment">Paling Banyak Dikomen</a></li>
				<li class=""><a class="catfilter" href="" name="choice">Pilihan Kaffah</a></li>
			</ul>
		</div>
	</div>
	
	<div class="postwrap postfilter">
		<em class="postfilter filterproduct">Filter Kategori</em>
		<input type="hidden" name="filtercategory" id="filtercategory" value="" />
		<div id="productfilter" class="postfilterproduct">
			<ul class="productlistfilter">
				<li class=""><a class="catfilter" href="" name="">Semua Kategori</a></li>
				<li class=""><a class="catfilter" href="#" name="alat-alat_musik">Alat Musik</a></li>
				<li class=""><a class="catfilter" href="#" name="anak-anak_dan_bayi">Anak-anak & Bayi</a></li>
				<li class=""><a class="catfilter" href="#" name="buku">Buku</a></li>
				<li class=""><a class="catfilter" href="#" name="elektronik">Elektronik</a></li>
				<li class=""><a class="catfilter" href="#" name="fashion">Fashion</a></li>
				<li class=""><a class="catfilter" href="#" name="film">Film</a></li>
				<li class=""><a class="catfilter" href="#" name="fotografi">Fotografi</a></li>
				<li class=""><a class="catfilter" href="#" name="games_dan_consoles/">Games & Consoles</a></li>
				<li class=""><a class="catfilter" href="#" name="handicrafts">Handicrafts</a></li>
				<li class=""><a class="catfilter" href="#" name="handphone_aksesoris">Handphone Aksesoris</a></li>
				<li class=""><a class="catfilter" href="#" name="hewan_piaraan_dan_aksesoris">Hewan Piaraan</a></li>
				<li class=""><a class="catfilter" href="#" name="hp_dan_telekomunikasi/">HP & Telekomunikasi</a></li>
				<li class=""><a class="catfilter" href="#" name="jam_dan_perhiasan">Jam & Perhiasan</a></li>
				<li class=""><a class="catfilter" href="#" name="jasa">Jasa</a></li>
				<li class=""><a class="catfilter" href="#" name="kesehatan_dan_kecantikan">Kesehatan & Kecantikan</a></li>
				<li class=""><a class="catfilter" href="#" name="koleksi">Koleksi</a></li>
				<li class=""><a class="catfilter" href="#" name="konstruksi_dan_taman">Konstruksi & Taman</a></li>
				<li class=""><a class="catfilter" href="#" name="liburan_dan_bepergian">Liburan & Bepergian</a></li>
				<li class=""><a class="catfilter" href="#" name="makanan_dan_minuman">Makanan & Minuman</a></li>
				<li class=""><a class="catfilter" href="#" name="mobil_aksesoris">Mobil & aksesoris</a></li>
				<li class=""><a class="catfilter" href="#" name="mobil_onderdil/">Mobil onderdil</a></li>
				<li class=""><a class="catfilter" href="#" name="motor_dan_sekuter">Motor & Sekuter</a></li>
				<li class=""><a class="catfilter" href="#" name="musik">Musik</a></li>
				<li class=""><a class="catfilter" href="#" name="olahraga_dan_kesehatan">Olahraga & Kesehatan</a></li>
				<li class=""><a class="catfilter" href="#" name="perangkat_keras_komputer">Hardware Komputer</a></li>
				<li class=""><a class="catfilter" href="#" name="perangkat_lunak_komputer">Software Komputer</a></li>
				<li class=""><a class="catfilter" href="#" name="perlengkapan_rumah">Perlengkapan Rumah</a></li>
				<li class=""><a class="catfilter" href="#" name="properti">Properti</a></li>
				<li class=""><a class="catfilter" href="#" name="seni_dan_antik">Seni & Antik</a></li>
				<li class=""><a class="catfilter" href="#" name="sepeda_dan_aksesoris">Sepeda & Aksesoris</a></li>
				<li class=""><a class="catfilter" href="#" name="serba_serbi">Serba Serbi</a></li>

			</ul>
		</div>
	</div>

</div>

<div id="tablepost">
	<input type="hidden" id="blogid" name="blogid" />	
	<table class="post" id="productlist"><tbody></tbody></table>
</div>