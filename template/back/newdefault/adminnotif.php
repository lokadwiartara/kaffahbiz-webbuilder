<h1 class="headnote"><a href="#web" class="headbread">Superadmin</a><em class="separator"></em><font>Notifikasi dan Popup</font></h1>
<!-- this is not displayed -->

<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/adminnotif.js"></script>

<a href="#" class="orangebutton" id="savenotify">Simpan Notifikasi</a>
<h2 class="headnote">Notifikasi dan Popup</h2>

<div class="form fulladmin">
	<form action="" id="form_adminnotif" method="POST">
		<h3 class="note">Notifikasi Untuk Free Version</h3><br />
		<label for="judul_notif_for_free" class="form">Judul Notifikasi</label>
		<input class="textlong" type="text" name="judul_notif_for_free" id="judul_notif_for_free" />
		<br />
		<label for="" class="form">Deskripsi (HTML)</label>
		<textarea id="isi_html_for_free" name="isi_html_for_free" class="textarealong"></textarea><br>

		<label for="type_notif_for_free" class="form">Tipe Notifikasi</label>
		<select name="type_notif_for_free" class="formdropdown midlelong" id="type_notif_for_free">
		<option value="">Pilih Jenis Notifikasi</option>
		<option value="fixed">Fixed Bar Bottom</option>
		<option value="popup">Center Popup</option>
		</select><br /> 

		

		<label for="configurasi_comment" class="form">Status Notifikasi</label>
		<input id="status_notif_for_free" value="yes" name="status_notif_for_free" type="checkbox"> 
		<label class="labelchoice" for="status_notif_for_free">Aktifkan Notifikasi Untuk Paket Free</label><br><br>
		<label for="" class="form">Sidebar Admin (HTML)</label>
		<textarea id="sidebar_for_free" name="sidebar_for_free" class="textarealong"></textarea><br>

		<label for="" class="form">Dashboard (HTML)</label>
		<textarea id="dashboard_for_free" name="dashboard_for_free" class="textarealong"></textarea><br>

		<h3 class="note">Notifikasi Untuk Paid Version</h3><br />
		<label for="judul_notif_for_paid" class="form">Judul Notifikasi</label>
		<input class="textlong" type="text" name="judul_notif_for_paid" id="judul_notif_for_paid" />
		<br />
		<label for="" class="form">Deskripsi (HTML)</label>
		<textarea id="isi_html_for_paid" name="isi_html_for_paid" class="textarealong"></textarea><br>

		<label for="type_notif_for_paid" class="form">Tipe Notifikasi</label>
		<select name="type_notif_for_paid" class="formdropdown midlelong" id="type_notif_for_paid">
		<option value="">Pilih Jenis Notifikasi</option>
		<option value="fixed">Fixed Bar Bottom</option>
		<option value="popup">Center Popup</option>
		</select><br /> 

		<label for="configurasi_comment" class="form">Status Notifikasi</label>
		<input id="status_notif_for_paid" value="yes" name="status_notif_for_paid" type="checkbox"> 
		<label class="labelchoice" for="status_notif_for_paid">Aktifkan Notifikasi Untuk Paket Berbayar</label><br><br>

		<label for="" class="form">Sidebar Admin (HTML)</label>
		<textarea id="sidebar_for_paid" name="sidebar_for_paid" class="textarealong"></textarea><br>

		<label for="" class="form">Dashboard (HTML)</label>
		<textarea id="dashboard_for_paid" name="dashboard_for_paid" class="textarealong"></textarea><br>
	</form>
</div>