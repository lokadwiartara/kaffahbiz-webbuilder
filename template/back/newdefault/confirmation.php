<!-- this is not displayed -->
<h1 class="headnote"><a href="#product" class="headbread">Produk</a><em class="separator"></em><font>Daftar Konfirmasi</font></h1>
<!-- this is not displayed -->
<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/confirmation.js"></script>
<h2 class="headnote">Daftar Konfirmasi</h2>
<br />
<br />


<div id="navpost">

	<div class="right">
		<div class="postwrap postsearch"><input  type="text" name="search" id="search" autocomplete="off" /></div>
		<div class="postwrap postpaging">
			<a href="" class="prevconfirmation"></a>
			<strong>
				<font id="currpageconfirmation" class="crnt">1</font>
				<div id="numcountconfirmation"></div>
			</strong>
			<a class="forwardconfirmation" href=""></a>
			
		</div>
	</div>
	<div class="postwrap wrapcheckall"><input title="Check All" type="checkbox" name="checkall" id="checkall"/></div>
	<div class="postwrap postaction"><em class="postaction">Aksi Konfirmasi</em>
		<div id="postaction">
			<ul>											
				<li id="masstransfer_confirmation">Sudah Transfer</li>		
				<li id="masspending_confirmation">Pending</li>		
				<li id="massfailed_confirmation">Gagal</li>
				<li id="massdel_confirmation">Hapus</li>	
			</ul>
		</div>
	</div>
</div>


<div id="tablepost">
	<input type="hidden" id="blogid" name="blogid" />
	
	<table class="post" id="confirmationlist">
		<thead>
			<tr><th></th><th class="nama">Nama</th><th class="invoice">No.Invoice</th><th>Email/HP</th><th class="total">Total</th><th class="destination">Transfer Ke</th><th>Dari Rek.</th><th>Status</th></tr>
		</thead>
		<tbody></tbody>

	</table>
</div>