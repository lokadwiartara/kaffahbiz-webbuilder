<!-- this is not displayed -->
<h1 class="headnote"><a href="#comment" class="headbread">Komentar</a><em class="separator"></em><font>Pending</font></h1>
<!-- this is not displayed -->

<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/draft_comment.js"></script>
<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/comment.js"></script>

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

<h2 class="headnote">Daftar Komentar Pending</h2>
<br />
<br />
<div id="navpostcomment">
	<div class="right">
		<div class="postwrap postsearch"><input  type="text" name="search" id="search" /></div>
		<div class="postwrap postpaging">
			<a href="" class="prevcomment"></a>
			<strong>
				<font id="currpagecomment" class="crnt"></font>
				<div id="numcountcomment"></div>
			</strong>
			<a class="forwardcomment" href=""></a>
			
		</div>
	</div>

	<div class="postwrap wrapcheckall"><input title="Check All" type="checkbox" name="checkall" id="checkall"/></div>
	<div class="postwrap postaction"><em class="postaction comment">Aksi Komentar</em>
		<div id="postaction" class="comment">
			<ul>
				
				<li id="masspublish_comment">Pasang</li>	
				<li id="masspending_comment">Jadikan Pending</li>	
				<li id="massdel_comment">Hapus</li>	
			</ul>
		</div>
	</div>
</div>


<div id="tablepost">
	<input type="hidden" id="blogid" name="blogid" />
	<table class="post" id="commentlist"><tbody></tbody></table>
</div>