<!-- this is not displayed -->
<h1 class="headnote"><a href="#media" class="headbread">File</a><em class="separator"></em><font>Semua</font></h1>
<!-- this is not displayed -->

<script type="text/javascript">
	$(document).ready(function(){
		$('table.post tr td.title').hover(function(){
			$(this).children('div.edit').show();
		},
			function(){
				$(this).children('div.edit').hide();
			}
		
		);
	});
</script>

<a href="#" class="orangebutton" id="addblog">+ Tambah File</a>
<h2 class="headnote">Daftar File</h2>

<div id="navpost">
	<div class="right">
		<div class="postwrap postsearch"><input  type="text" name="search" id="search" /></div>
		<div class="postwrap postpaging"><a href="" class="prev"></a><strong>3</strong><a class="forward" href=""></a></div>
	</div>

	<div class="postwrap wrapcheckall"><input type="checkbox" name="checkall" id="checkall"/></div>
	<div class="postwrap postaction"><em class="postaction">Aksi File</em></div>
</div>

<div id="tablepost">
	<table class="post">
			
		<tr>
			<td><input type="checkbox" /></td>
			<td class="title"><a href="">Carilah kebenaran Bukan Pembenaran</a>
			<div class="edit"><a href="">Edit</a> | <a href="">Hapus</a> | <a href="">Lihat</a>  </div>
			</td>
			<td class="author"><em class="author">Fikrul</em></td>
			<td><em class="cmt">2</em></td>
			<td><em class="see">32</em></td>
			<td><em class="clock">22-09-2013</em></td>
		</tr>
	</table>
</div>