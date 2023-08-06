<!-- this is not displayed -->
<h1 class="headnote"><a href="../#template" class="headbread">Tampilan</a><em class="separator"></em><font>Edit HTML</font></h1>
<!-- this is not displayed -->

<div class="mobilenotif"><p class="note">Hanya bisa diakses pada mode desktop/pc/laptop</p></div>

<div class="formobile">

	<link rel="stylesheet" href="<?php echo get_asset('js');?>codemirror/lib/codemirror.css" />
	<link rel="stylesheet" href="<?php echo get_asset('js');?>codemirror/addon/display/fullscreen.css" />
	<link rel="stylesheet" href="<?php echo get_asset('js');?>codemirror/theme/pastel-on-dark.css" />
	<script src="<?php echo get_asset('js');?>codemirror/lib/codemirror.js"></script>
	<script src="<?php echo get_asset('js');?>codemirror/mode/javascript/javascript.js"></script>
	<script src="<?php echo get_asset('js');?>codemirror/mode/xml/xml.js"></script> 	
	<script src="<?php echo get_asset('js');?>codemirror/mode/css/css.js"></script>
	<script src="<?php echo get_asset('js');?>codemirror/mode/htmlmixed/htmlmixed.js"></script>
	<link rel="stylesheet" href="<?php echo get_asset('js');?>codemirror/addon/fold/foldgutter.css" />
	<script src="<?php echo get_asset('js');?>codemirror/addon/fold/foldcode.js"></script>
	<script src="<?php echo get_asset('js');?>codemirror/addon/fold/foldgutter.js"></script>
	<script src="<?php echo get_asset('js');?>codemirror/addon/fold/brace-fold.js"></script>
	<script src="<?php echo get_asset('js');?>codemirror/addon/fold/xml-fold.js"></script>
	<script src="<?php echo get_asset('js');?>codemirror/addon/fold/comment-fold.js"></script>
	  
	<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/edit_template.js"></script>

	<a href="#" class="greybutton" id="reset">Settingan Awal</a>
	<a href="#" class="greybutton" id="preview">Preview</a>
	<a href="#" class="orangebutton" id="savetemplate">Simpan Template</a>
	<a href="../#template" class="greybutton" id="back">Kembali</a>


	<div id="fixedbar">
		<a href="../#template" class="btnfixed backbtn" title="Kembali" id="backbtn"></a>
		<a href="#" class="btnfixed savebtntpl" title="Simpan template ... "  id="savebtntpl"></a>
		<a href="#" class="btnfixed prevbtn" title="Jalankan / Run" id="prevbtn"></a>
		<a href="#" class="btnfixed firstsetting" title="Kembali Ke Settingan Awal" id="firstsetting"></a>
	</div>

	<h2 class="headnote">Edit HTML Tampilan</h2>
	<p class="headnote">Klik terlebih dahulu pada textarea untuk melakukan pengeditan. <br /> lalu tekan <b>F11 untuk mode full screen</b> & <b>ESC kembali ke normal</b></p>
	<br />
	<br />

	<div id="wrapcode">
		<textarea id="code"></textarea>
		<input type="hidden" name="firsttemplate" id="firstemplate" />
		<input type="hidden" name="domain" id="domain" />
	</div>

</div>

