<!-- this is not displayed -->
<h1 class="headnote"><a href="#product" class="headbread">Produk</a><em class="separator"></em><font>Kategori Produk</font></h1>
<div class="wraphide">
	<h2 class="headpop">Buat Kategori Baru</h2>
	<form id="formaddcat" method="POST">
		<label for="title">Judul Kategori</label>
		<input type="text" autocomplete="off" id="title" name="title" /><br />
		<p class="notes catnotes">Silahkan masukkan judul kategori, contoh: <b>Buku Islami</b></p>
		<label for="description">Deskripsi</label>
		<textarea id="description" class="desc cat" name="desc"></textarea>
		<br />
		<label for="parentcat">Induk Kategori</label>
		<select name="parentcat" id="parentcat" class="select parentcat">
			<option value="0">-- induk kategori--</option>
		</select><br />

		<label for="gambar" id="label_imgcat">Gambar Kategori</label>
		<input type="file" class="imagefile" name="userfile" id="imgcat" />
		<input type="hidden" value="" id="hide_imgcat" name="imgcat" />
		<br />

		<input  type="hidden" id="blog_id" name="blog_id" />
		<input  type="hidden" id="slug" name="slug" />
		<input  type="hidden" id="term_id" name="term_id" />
		<input  type="hidden" id="category_type" name="category_type" value="category_product" />
		<input type="submit" name="add" class="disable catbtn" disabled="disable" id="addnewbtn" value="Buat Sekarang!" />
	</form>
</div>

<script type="text/javascript" src="<?php echo get_asset('js');?>jqsortable/jquery-sortable.js"></script>	
<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/ajaxupload.js"></script>
<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/cat_product.js"></script>


<a class="orangebutton" id="addcat">+ Buat Kategori Baru</a>
<h2 class="headnote">Kategori Produk</h2>
<p class="headnote">Silahkan melakukan perubahan posisi/urutan dengan melakukan klik dan drag pada kategori di bawah...</p>

<div class="cat_article">
<ol class="limited_drop_targets vertical"></ol>
<input type="hidden" id="serialize_output" />
</div>