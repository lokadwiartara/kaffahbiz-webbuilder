<!-- this is not displayed -->
<h1 class="headnote"><a href="../#product" class="headbread">Produk</a><em class="separator"></em><font>Daftar Komisi</font></h1>
<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/admin_commision.js"></script>

<h2 class="headnote">Daftar Komisi Reseller + KaffahStore</h2>
<p class="headnote">berikut daftar komisi Reseller dan komisi untuk kaffahstore, mohon teliti dalam melakukan perubahan!</p>

<br />
<br />

<div id="navpost">
	<div class="right">
		<div class="postwrap postsearch"><input  type="text" name="search" id="search" autocomplete="off"  /></div>
		<div class="postwrap postpaging">
			<a href="" class="prevorder"></a>
			<strong>
				<font id="currpageorder" class="crnt">1</font>
				<div id="numcountorder"></div>
			</strong>
			<a class="forwardorder" href=""></a>
			
		</div>
	</div>	

	<div class="postwrap wrapcheckall"><input title="Check All" type="checkbox" name="checkall" id="checkall"/></div>
	<div class="postwrap postaction"><em class="postaction">Aksi Komisi</em>
		<div id="postaction">
			<ul>							
				<li id="masspaid_commision">Sudah Dibayar</li>	
				<li id="masspending_commision">Belum Dibayar</li>				
				<li id="massdel_commision">Hapus</li>	
			</ul>
		</div>
	</div>
</div>

<div id="tablepost">
	<input type="hidden" id="blogid" name="blogid" />
	
	<table class="post" id="commisionlist">
		<thead>
			<tr><th width="2%" class="check"></th><th width="18%" class="reseller">Reseller</th><th width="12%" class="invoice">Invoice</th><th width="15%" class="nama">Konsumen</th><th width="15%" class="trx">Produk</th><th width="13%">Komisi</th><th width="12%">Komisi KS</th><th width="15%">Status</th></tr>
		</thead>
		<tbody>
			
		</tbody>

	</table>
</div>