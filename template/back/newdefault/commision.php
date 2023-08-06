<!-- this is not displayed -->
<h1 class="headnote"><a href="#commision" class="headbread">Komisi</a><em class="separator"></em><font>Semua</font></h1>
<!-- this is not displayed -->
<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/commision.js"></script>

<h2 class="headnote">Daftar Komisi</h2>
<p class="headnote">Berikut daftar komisi Anda, dari hasil transaksi, yang sudah fix transfer dan dikirim barangnya!</p>

<br />

<div id="navpost">
	<div class="right">
		<div class="postwrap postpaging">
			<a href="" class="prevorder"></a>
			<strong>
				<font id="currpageorder" class="crnt">1</font>
				<div id="numcountorder"></div>
			</strong>
			<a class="forwardorder" href=""></a>
			
		</div>
	</div>	

	<div class="postwrap postfilter">
		<em class="postfilter">Filter Komisi</em>
		<input type="hidden" name="filterstatus" id="filterstatus" value="" />
		<div id="postfilter">
			<ul>
				<li class=""><a class="statusfilter" href="#" name="">Semua Status</a></li>
				<li class=""><a class="statusfilter" href="#" name="sudah_dibayarkan">Sudah Dibayarkan</a></li>
				<li class=""><a class="statusfilter" href="#" name="belum_dibayar">Belum Dibayar</a></li>
			</ul>
		</div>
	</div>	
</div>


<div id="tablepost">
	<input type="hidden" id="blogid" name="blogid" />
	
	<table class="post" id="commisionlist">
		<thead>
			<tr><th width="5%" class="invoice">Invoice</th><th width="20%" class="nama">Konsumen</th><th width="25%" class="trx">Produk</th><th width="20%">Komisi</th><th width="25%">Status</th></tr>
		</thead>
		<tbody>
			
		</tbody>

	</table>
</div>