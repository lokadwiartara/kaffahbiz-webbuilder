<!-- this is not displayed -->
<h1 class="headnote"><a href="#product" class="headbread">Produk</a><em class="separator"></em><font>Semua</font></h1>
<!-- this is not displayed -->


<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/refresh_article.js"></script>
<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/product.js"></script>

<a class="orangebutton" id="addblog">+ Tambah Produk Baru</a>
<h2 class="headnote">Daftar Produk</h2>

<br />
<br />

<div id="navpostproduct">
	<div class="right">
		<div class="postwrap postsearch"><input  type="text" name="search" autocomplete="off" id="search" /></div>
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
				
				<li id="masspublish_product">Terbitkan</li>	
				<li id="massdraft_product">Jadikan Draft</li>	
				<li id="massdel_product">Hapus</li>	
			</ul>
		</div>
	</div>
	<div class="postwrap postfilter">
		<em class="postfilter">Filter Kategori</em>
		<input type="hidden" name="filtercategory" id="filtercategory" value="" />
		<div id="productfilter" class="postfilterproduct">
			<ul>
				<li class=""><a class="catfilter" href="" name="">Semua Kategori</a></li>
			</ul>
		</div>
	</div>
</div>

<div id="tablepost">
	<input type="hidden" id="blogid" name="blogid" />
	
	<table class="post" id="productlist"><tbody></tbody></table>
</div>