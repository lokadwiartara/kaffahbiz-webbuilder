<?php echo get_template('header',TRUE);?>




	<div class="main_header">
		<div class="wrap">
			<div class="header_btm">
																	
			</div>
		</div>
	</div>
</div>


<!-- start main -->
	<div class="main_bg inside">
		<div class="wrap">
			<div class="main">
				<div class="content admnewkaffah">
					<!-- start blog_left -->
					<div class="menuadmin_left">		
						<div class="blog_main single reg">	
								
								<h2 id="head_form_reg" class="headin">Bikin Website Sekarang Juga!</h2>
								<p class="headreg headin" id="head_note_reg">Dengan mengisi formulir ini website Anda sudah langsung online:</p>

								<div id="form_reg" class="form">
									
									<form action="<?php echo base_url();?>reg/new" id="regform" method="POST">
										<label for="emai_reg " class="form">Email</label>
										<input value="" autocomplete="off" class="textlong" type="text" name="email_reg" id="email_reg" />
										<p id="email_reg_status" class="status "></p>

										<label for="password_reg" class="form">Password</label>
										<input value="" autocomplete="off" type="password" class="textlong" name="password_reg" id="password_reg" />																													
										<p id="password_reg_status" class="status "></p>

										<label for="no_hp_reg" class="form">No.Handphone</label>
										<input value="" autocomplete="off" class="textlong" type="text" name="no_hp_reg" id="no_hp_reg" />
										<p id="no_hp_reg_status" class="status"></p>

										<label for="alamat_web" class="form">Alamat Website</label>
										<input value="" autocomplete="off" class="textlong domain" type="text" name="alamat_web" id="alamat_web" />
										<select name="tld" id="tld">
											<option value=".kaffah.biz">.kaffah.biz (0/tahun)</option>
											<option selected value=".shopp.id">.shopp.id (99rb/tahun)</option>
											<option value=".ol-shop.net">.ol-shop.net (99rb/tahun)</option>
											<option value=".onweb.id">.onweb.id (99rb/tahun)</option>
											<option value=".com">.com (555rb/tahun)</option>
											<option value=".net">.net (555rb/tahun)</option>
											<option value=".biz">.biz (555rb/tahun)</option>
											<option value=".org">.org (555rb/tahun)</option>
										</select><br class="floating" />
										<p id="alamat_web_status" class="status"></p>

										
										<input type="checkbox" name="syarat" id="syarat" value="yes">
										<label for="syarat" class="inblock">Saya sudah membaca dan menyetujui <a href="http://www.kaffah.biz/artikel/news/syarat_dan_ketentuan">Syarat dan Ketentuan</a></label><br class="floating" />
										<input type="submit" name="daftar" id="daftar" disabled="disabled" value="Daftar Sekarang!" class="reg_submit disabled" />
									</form>
									
								</div>
								
						</div>
						
					</div>

				<div class="clear"></div>
				</div>
			</div>
		</div>
	</div>
	
<?php echo get_template('footer',TRUE);?>
