<!-- this is not displayed -->
<h1 class="headnote"><a href="../../#page" class="headbread">Halaman</a><em class="separator"></em><font>Pengeditan</font></h1>

<script type="text/javascript" src="<?php echo get_asset('js');?>ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/edit_page.js"></script>
<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/ajaxupload.js"></script>
<script type="text/javascript" src="<?php echo get_dir_(dirname(__FILE__), 'js');?>/media.js"></script>



<a href="/site/admin/31/#article" class="greybutton pagebtn" id="tutupblog">Tutup [x]</a>
<a href="/site/admin/1/full/#save" class="greybutton pagebtn" id="simpanblog">Jadikan Draft</a>
<a href="/site/admin/1/full/#save" class="orangebutton pagebtn" id="addblogyes">Update Halaman</a>

<h2 class="headnote full">Pengeditan Halaman</h2>

<div class="newarticle">
	<input type="text" id="titlearticle" name="titlearticle" class="longfull" autocomplete="off" value="Tuliskan judul halaman di sini ..." onFocus="if(this.value=='Tuliskan judul halaman di sini ...')this.value=''" onBlur="if(this.value=='')this.value='Tuliskan judul halaman di sini ...'" />
	<input type="hidden" name="status" id="status" value="draft" />
	<input type="hidden" name="post_type" id="post_type" value="page" />
	<p class="warning">Warning ...</p>
	<div id="settingbar">
		<div class="addmedia"><a href="" name="addmedia" class="addmedia">Tambah File</a></div>
		<h3 class="categoryset headwrap first">Hirarki</h3>
		<h3 class="timepubset headwrap">Waktu</h3>
		<h3 class="seoset headwrap">SEO</h3>
		<h3 class="discussset headwrap">Diskusi</h3>
		
		<div class="categoryset wraptop">
			<div class="wrappset catlistli">
				<p class="note">Silahkan dipilih induk halaman. (Induk halaman ini adalah hirarki dari halaman, menghasilkan sub menu)</p>
				<div class="newcat">
					<div class="formnewcatwrap parent">
						<form id="formaddcat">
							<select name="parentpage" id="parentpage">
								<option value="0">-- induk halaman --</option>
							</select>
							<input  type="hidden" id="blog_id" name="blog_id" />
							<input  type="hidden" id="page_name" name="page_name" />
							<input  type="hidden" id="desc" name="desc" />
						</form>	
					</div>
				</div>
				
			</div>
		</div>
		<div class="timepubset wraptop">
			
			<div class="wrappset timewrap"> <em>Tanggal : dd/mm/yyyy</em>
				<input type="text" name="date" value="<?php echo date('d');?>" class="timewrapshort" /> / 
				
				<?php 	$this->load->helper('form');?>
				<?php 	$monthselect = date(m); 
						$month = array(
										'01' => 'Januari', '02' => 'Februari',
										'03' => 'Maret', '04' => 'April',
										'05' => 'Mei', '06' => 'Juni', '07' => 'Juli',
										'08' => 'Agustus', '09' => 'September',
										'10' => 'Oktober', '11' => 'November',
										'12' => 'Desember',
									);
										
						echo form_dropdown('month', $month, $monthselect, 'class="timewrapselect"');
				?>
				
				 / 
				
				<input type="text" name="year" class="timewrapshort" value="<?php echo date('Y');?>" />
				<em class="time">Pukul : hh:mm </em>
				<input type="text" name="hour" value="<?php echo date('H');?>" class="timewrapshort" /> : 
				<input type="text" name="minute" value="<?php echo date('i');?>" class="timewrapshort" />
				
				<p class="note">Note : Artikel ini akan terbit disesuaikan waktu yang Anda tetapkan ...</p>
			</div>
		</div>
		<div class="seoset wraptop">
			
			<div class="wrappset seo">
				<label>Title</label>
				<input type="text" name="metatitle" class="longtextinwrap" />
				<p class="note">Title ini adalah meta title yang berfungsi untuk SEO Maksimal, karena search engine sangat mengedepankan meta title ini</p>
				<br />
				<label>Meta Keyword</label>
				<textarea name="metakeyword"></textarea>
				<p class="note">Keyword (kata kunci) apa yang akan Anda bidik, agar website Anda muncul di search engine setiap kali orang mencari kata tersebut di Search Engine, </p>
				<br /><label>Meta Description</label>
				<textarea name="metadescription"></textarea>
				<p class="note">Silahkan isi, Meta Description agar artikel ini memiliki SEO Score yang bagus dalam Search Engine. Dan website Anda memiliki peringkat yang bagus dalam search Engine, seperti Google maupun Bing. </p>
				
			</div>
		</div>
		<div class="discussset wraptop">
			
			<div class="wrappset discuss">
				<label class="label">Komentar</label><input id="comment" name="comment" type="checkbox" /> <label for="comment" class="choice">Aktifkan Komentar pada Artikel ini</label> <br />
				<label class="label">Notifikasi</label><input id="notification" name="notification" type="checkbox" /> 
				<label class="choice" for="notification" >Email Notifikasi Untuk User dan Admin</label> 
				<p class="note">(notifikasi ini akan dikirimkan ke user yang telah berkomentar, diskusi terbaru dikomentar tersebut, dan juga dikirimkan ke admin)</p>
			</div>
		</div>

		
		
	</div>
	
	<div id="editorwrap">
	
	</div>

	<textarea style="display:none;" id="editor1" name="editor1" ></textarea>
	<p id="datanya"></p>



</div>
