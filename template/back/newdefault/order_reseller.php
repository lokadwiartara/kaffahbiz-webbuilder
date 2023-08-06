<!-- this is not displayed -->
<h1 class="headnote"><a href="#order_reseller" class="headbread">Pesanan Produk Suplier</a><em class="separator"></em><font>Semua</font></h1>
<!-- this is not displayed -->

<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/order.js"></script>

<h2 class="headnote">Daftar Transaksi/Pemesanan Produk Suplier</h2>

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
	<div class="postwrap postaction"><em class="postaction">Aksi Transaksi</em>
		<div id="postaction">
			<ul>							
				<li id="massprint_order">Cetak Alamat</li>	
				<li id="masspending_order">Pending</li>
				<li id="masstransfer_order">Sudah Transfer</li>
				<li id="masssend_order">Sudah Dikirim</li>
				<li id="massfailed_order">Gagal</li>
				<li id="massdel_order">Hapus</li>	
			</ul>
		</div>
	</div>
	<div class="postwrap postfilter">
		<em class="postfilter">Filter Status</em>
		<input type="hidden" name="filterstatus" id="filterstatus" value="" />
		<div id="postfilter">
			<ul>
				<li class=""><a class="statusfilter" href="" name="">Semua Status</a></li>
				<li class=""><a class="statusfilter" href="" name="pending">Pending</a></li>
				<li class=""><a class="statusfilter" href="" name="sudah_transfer">Sudah Transfer</a></li>
				<li class=""><a class="statusfilter" href="" name="sudah_dikirim">Sudah Dikirim</a></li>
				<li class=""><a class="statusfilter" href="" name="gagal">Gagal</a></li>
			</ul>
		</div>
	</div>	
</div>


<div id="tablepost">
	<input type="hidden" id="blogid" name="blogid" />
	
	<table class="post" id="orderlist">
		<thead>
			<tr><th width="5%" class="check"></th><th width="5%" class="invoice">Invoice</th><th width="25%" class="nama">Nama</th><th width="20%">Alamat</th><th width="30%" class="trx">Transaksi</th><th>Total</th><th>Status</th></tr>
		</thead>
		<tbody>
			
		</tbody>

	</table>
</div>