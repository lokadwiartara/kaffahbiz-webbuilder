<!-- this is not displayed -->
<h1 class="headnote"><a href="#article" class="headbread">Artikel</a><em class="separator"></em><font>Semua</font></h1>
<!-- this is not displayed -->


<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/refresh_article.js"></script>
<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/article.js"></script>

<a class="orangebutton" id="addblog">+ Tambah Artikel Baru</a>
<h2 class="headnote">Daftar Artikel</h2>

<br />
<br />

<div id="navpost">
	<div class="right">
		<div class="postwrap postsearch"><input  type="text" autocomplete="off" name="search" id="search" /></div>
		<div class="postwrap postpaging">
			<a href="" class="prev"></a>
			<strong>
				<font id="currpage" class="crnt">1</font>
				<div id="numcount"></div>
			</strong>
			<a class="forward" href=""></a>
			
		</div>
	</div>

	<div class="postwrap wrapcheckall"><input title="Check All" type="checkbox" name="checkall" id="checkall"/></div>
	<div class="postwrap postaction"><em class="postaction">Aksi Artikel</em>
		<div id="postaction">
			<ul>
				
				<li id="masspublish_article">Terbitkan</li>	
				<li id="massdraft_article">Jadikan Draft</li>	
				<li id="massdel_article">Hapus</li>	
			</ul>
		</div>
	</div>
	<div class="postwrap postfilter">
		<em class="postfilter">Filter Kategori</em>
		<input type="hidden" name="filtercategory" id="filtercategory" value="" />
		<div id="postfilter">
			<ul>
				<li class=""><a class="catfilter" href="" name="">Semua Kategori</a></li>
			</ul>
		</div>
	</div>
</div>

<div id="tablepost">
	<input type="hidden" id="blogid" name="blogid" />
	
	<table class="post" id="postlist"><tbody></tbody></table>
</div>