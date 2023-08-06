<!-- this is not displayed -->
<h1 class="headnote"><font>Statistik</font></h1>
<!-- this is not displayed -->

<script type="text/javascript" src="<?php echo get_asset('js');?>jqplot/jquery.jqplot.js"></script>
<script type="text/javascript" src="<?php echo get_asset('js');?>jqplot/plugins/jqplot.highlighter.min.js"></script>
<script type="text/javascript" src="<?php echo get_asset('js');?>jqplot/plugins/jqplot.cursor.min.js"></script>
<script type="text/javascript" src="<?php echo get_asset('js');?>jqplot/plugins/jqplot.dateAxisRenderer.min.js"></script>	
<link rel="stylesheet" type="text/css" href="<?php echo get_asset('js');?>jqplot/jquery.jqplot.css" />
<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/statistic30.js"></script>


<h2 class="headnote">Statistik Website</h2>
<p class="headnote"><b>website statistik</b> berguna sebagai acuan perbaikan dari segi konten dan visitor</p>

<div id="chart1" class="fullchart"></div>

<div class="statistic">
	<div class="fullstatistic">
		
			<h3 class="note fullstat">Statistik Visitor - 30 data terakhir ...</h3>
		<table class="fullstats" id="recentthird">
			<thead>
				<tr>
					<th class="date">Date - Time</th>
					<th class="visitor">Visitor</th>
					<th class="info">System</th>
					<th class="referrer">Trafic Source</th>
				</tr>
			</thead>	
			<tbody>				
			</tbody>
		</table>
	</div>	
	
	<div class="rightstatistic">
		<h3 class="note day">Statistik Visitor Perhari (Unique)</h3>
		<table class="fullstats" id="bydate">
				<thead>
					<tr>
						<th class="time">Date/Time</th>
						<th class="sum">Visitor</th>
						<th class="infovisit">Graph Statistic</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			
			</table>	
	</div>
	
	<div class="leftstatistic">
		<h3 class="note clockb">Statistik Visitor Perjam</h3>
		<table class="fullstats" id="byhour">
				<thead>
					<tr>
						<th class="time">Date/Time</th>
						<th class="sum">Visitor</th>
						<th class="infovisit">Graph Statistic</th>
					</tr>
				</thead>
				<tbody>					
				</tbody>
			
			</table>
	</div>

	<br class="floating" />
</div>
