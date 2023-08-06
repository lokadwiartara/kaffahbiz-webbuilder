<!-- this is not displayed -->
<h1 class="headnote"><a href="#config" class="headbread">Konfigurasi</a><em class="separator"></em><font>Komentar</font></h1>
<!-- this is not displayed -->

<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/config_comment.js"></script>

<a href="#" class="orangebutton" id="savecomment">Simpan Konfigurasi</a>
<h2 class="headnote">Konfigurasi Komentar</h2>

<div class="form">
	<form action="" id="config_comment" method="POST">
		<label for="configurasi_comment" class="form mobile">Settingan Komentar</label>
		<input id="all_comment" value="yes" name="all_comment" type="checkbox"> 
		<label class="labelchoice mobile" for="all_comment">Semua orang boleh mengomentari baik itu artikel / halaman / produk</label><br>
		<label for="moderation" class="form">Moderasi Komentar</label>
		<input id="moderation" value="yes" name="moderation" type="checkbox"> 
		<label class="labelchoice mobile" for="moderation">Semua komentar harus di moderasi dahulu sebelum di tampilkan</label><br />
		<label for="sent_email" class="form">Kirim Email</label><input id="sent_email" value="yes" name="sent_email" type="checkbox"> 
		<label class="labelchoice mobile" for="sent_email">Jika ada komentar baru</label>
	</form>
</div>