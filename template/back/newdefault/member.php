<!-- this is not displayed -->
<h1 class="headnote"><a href="#follower" class="headbread">Follower</a><em class="separator"></em><font>Semua</font></h1>
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
<a href="#" class="orangebutton" id="addblog">+ Tambah User</a>
<h2 class="headnote">Daftar User/Member</h2>

<div id="navpost">
	<div class="right">
		<div class="postwrap postsearch"><input  type="text" name="search" id="search" /></div>
		<div class="postwrap postpaging"><a href="" class="prev"></a><strong>1</strong><a class="forward" href=""></a></div>
	</div>

	<div class="postwrap wrapcheckall"><input type="checkbox" name="checkall" id="checkall"/></div>
	<div class="postwrap postaction"><em class="postaction">Aksi User</em></div>
</div>

<div id="tablepost">
	<table class="post">
	
		
	</table>
</div>