<h1 class="headnote"><a href="#web" class="headbread">Superadmin</a><em class="separator"></em><font>Daftar Semua Website</font></h1>
<!-- this is not displayed -->

<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/bloglist.js"></script>


<h2 class="headnote">Daftar Semua Website</h2>

<div id="navpost">
	<div class="right">
		<div class="postwrap postsearch"><input  type="text" name="search" id="search" /></div>
		<div class="postwrap postpaging">
			<a href="" class="prevbloglist"></a>
			<strong>
				<font id="currpagebloglist" class="crnt">1</font>
				<div id="numcountbloglist"></div>
			</strong>
			<a class="forwardbloglist" href=""></a>
			
		</div>

	</div>

	
	
	<div class="postwrap postfilter">
		<em class="postfilter">Filter Website</em>
		<input type="hidden" name="filterwebsite" id="filterwebsite" value="" />
		<div id="postfilter">
			<ul>
				<li class=""><a class="catfilter" href="" name="">Semua Website</a></li>
				<li class=""><a class="catfilter" href="" name="free">Free (.kaffah.biz)</a></li>
				<li class=""><a class="catfilter" href="" name="basic">Basic (*.id / *.net)</a></li>
				<li class=""><a class="catfilter" href="" name="silver">Silver (.com)</a></li>
				<li class=""><a class="catfilter" href="" name="active">Aktif (Berbayar)</a></li>
				<li class=""><a class="catfilter" href="" name="unactive">Tidak Aktif (Berbayar)</a></li>
				<li class=""><a class="catfilter" href="" name="reseller">Reseller</a></li>
			</ul>
		</div>
	</div>
	<div class="postwrap postfilter postsortir">
		<em class="postsortir">Sortir Website</em>
		<input type="hidden" name="sortirwebsite" id="sortirwebsite" value="all" />
		<div id="postsortir">
			<!--<ul>
				<li class=""><a class="catsort" href="" name="post">Teraktif (Posting)</a></li>
				<li class=""><a class="catsort" href="" name="visitor">Terpadat (Visitor)</a></li>
				<li class=""><a class="catsort" href="" name="comment">Teramai (Komentar)</a></li>
				<li class=""><a class="catsort" href="" name="cart">Terkaya (Transaksi)</a></li>
			</ul>
			-->
		</div>
	</div>	
</div>

<table id="bloglist"><tbody></tbody></table>