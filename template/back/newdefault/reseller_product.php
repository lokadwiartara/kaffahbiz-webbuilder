<!-- this is not displayed -->
<h1 class="headnote"><a href="#reseller_product" class="headbread">Produk Supplier</a><em class="separator"></em><font>Semua</font></h1>
<!-- this is not displayed -->

<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/reseller_product.js"></script>

<h2 class="headnote">Daftar Produk Supplier</h2>
<p class="headnote">Berikut adalah daftar produk yang bisa Anda jual kembali, Anda mendapatkan komisi setiap ada transaksi, dan pembeli telah fix transfer. Jika masuk belum jelas silahkan <a href="">klik informasi lengkapnya</a></p>


<div id="navpostproductr">
	<div class="right">
		<div class="postwrap postsearch"><input  type="text" name="search" id="search" /></div>
		<div class="postwrap postpaging">
			<a href="" class="prevproductr"></a>
			<strong>
				<font id="currpage" class="crnt">1</font>
				<div id="numcountproductr"></div>
			</strong>
			<a class="forwardproductr" href=""></a>
			
		</div>
	</div>	
	
	<div class="postwrap postfilter postsort">
		<em class="postfilter sortproduct">Sortir Produk</em>
		<input type="hidden" name="sortproduct" id="sortproduct" value="all" />
		<div id="productsort" class="postsortproductr">
			<ul>
				<li class=""><a class="catfilter" href="" name="all">Semua Produk</a></li>
				<li class=""><a class="catfilter" href="" name="fee">Komisi Paling Besar</a></li>
				<li class=""><a class="catfilter" href="" name="cheap">Murah ke Mahal</a></li>
				<li class=""><a class="catfilter" href="" name="expensive">Mahal ke Murah</a></li>
				<li class=""><a class="catfilter" href="" name="new">Terbaru</a></li>				

			</ul>
		</div>
	</div>
	
	<div class="postwrap postfilter">
		<em class="postfilter filterproduct">Filter Kategori</em>
		<input type="hidden" name="filtercategory" id="filtercategory" value="" />
		<div id="productfilter" class="postfilterproductr">
			<ul>
				<li class=""><a class="catfilter" href="" name="">Semua Kategori</a></li>
			</ul>
		</div>
	</div>

</div>

<div id="tablepost">
	<input type="hidden" id="blogid" name="blogid" />	
	<table class="post" id="productlist">
		<thead><tr><th>Produk Reseller</th><th>Harga Jual</th><th>Komisi Reseller</th><th class="action">Aksi Produk</th></tr></thead>
		<tbody></tbody>
	</table>
</div>