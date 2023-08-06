<!-- this is not displayed -->
<h1 class="headnote"><font>Dashboard</font></h1>
<!-- this is not displayed -->

<script type="text/javascript" src="<?php echo get_asset('js');?>jqplot/jquery.jqplot.js"></script>
<script type="text/javascript" src="<?php echo get_asset('js');?>jqplot/plugins/jqplot.highlighter.min.js"></script>
<script type="text/javascript" src="<?php echo get_asset('js');?>jqplot/plugins/jqplot.cursor.min.js"></script>
<script type="text/javascript" src="<?php echo get_asset('js');?>jqplot/plugins/jqplot.dateAxisRenderer.min.js"></script>	

<link rel="stylesheet" type="text/css" href="<?php echo get_asset('js');?>jqplot/jquery.jqplot.css" />

<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/dashboard.js"></script>
	

<h2 class="headnote">Dashboard</h2>


<h3 class="note">Info Singkat</h3>
<p class="headnote">Komentar, order, dan Konfirmasi...</p><br />
<div id="tablepost">
	<table class="post" id="dashlist">
		<tbody>
			<tr><td class="label">Komentar Pending</td><td class="content"><font id="pendingcomment">0</font> <a href="<?php echo base_url();?>site/member/<?php echo $this->session->userdata('dom_id');?>/#comment">lihat detil komentar</a></td></tr>
			<tr><td class="label">Order Pending</td><td class="content"><font id="pendingorder">0</font> <a href="<?php echo base_url();?>site/member/<?php echo $this->session->userdata('dom_id');?>/#order">lihat detil order</a></td></tr>
			<tr><td class="label">Konfirmasi</td><td class="content"><font id="pendingconfirmation">0</font> <a href="<?php echo base_url();?>site/member/<?php echo $this->session->userdata('dom_id');?>/#confirmation">lihat detil konfirmasi</a></td></tr>
		</tbody>
	</table>
</div>

<h3 class="note">Info Statistik Website</h3>
<div id="chart1"><div class="load"><img src="<?php echo get_asset('images');?>ajax-loader.gif" /></div></div>

<h3 class="note">Info Bermanfaat dari Admin@Kaffah</h3>
<p class="headnote">Berikut adalah Update dari kami...</p><br />

<div id="dashboard_id"></div>

</div>
