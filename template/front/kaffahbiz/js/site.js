$(document).ready(function(){

	window.emailstatus = false;
	window.passwordstatus = false;
	window.no_hpstatus = false;
	window.alamat_webstatus = false;
	window.syarat = false;

	/* registration */
	$('#email_reg').keyup(function(){
		clearTimeout($.data(this, 'timer'));
		var wait = setTimeout(checkemail, 500);
		$(this).data('timer', wait);	
	});

	$('#password_reg').keyup(function(){
		clearTimeout($.data(this, 'timer'));
		var wait = setTimeout(checkpassw, 500);
		$(this).data('timer', wait);	
	});

	$('#no_hp_reg').keyup(function(){
		clearTimeout($.data(this, 'timer'));
		var wait = setTimeout(checkhp, 500);
		$(this).data('timer', wait);	
	});

	$('#alamat_web').keyup(function(){
		clearTimeout($.data(this, 'timer'));
		var wait = setTimeout(checkdom, 500);
		$(this).data('timer', wait);	
	});

	$('#tld').change(function(){		
		checkdom();
	});

	$('#syarat').change(function(){
		if(this.checked){
			val = $(this).val();
			if(val == 'yes'){
				window.syarat = true;
				btnregactivate();
			}
			else{
				window.syarat = false;
				$('#daftar').attr('disabled', 'disabled').addClass('disabled');
				val = '';
			}
		}
		else{
			window.syarat = false;
			$('#daftar').attr('disabled', 'disabled').addClass('disabled');
			val = '';			
		}
	});

	$('#regform').submit(function(eve){
		eve.preventDefault();
		if(window.emailstatus == true && window.passwordstatus == true && window.no_hpstatus == true && window.alamat_webstatus == true && window.syarat == true){
			var email = $('#email_reg').val();
			var password = $('#password_reg').val();
			var no_hp = $('#no_hp_reg').val();
			var alamat = $('#alamat_web').val();
			var tld = $('#tld').val();

			$.ajax({
				url: "/signup/finishing/",
				type: "POST",
				dataType: "json",
				data: {email:email, password:password,no_hp:no_hp,alamat:alamat, tld:tld},	
				beforeSend: function(){
					$('div.loadupload').html();
					$('div.blackbg').show();	
					$('div.loadupload').show();	
					$('#form_reg').slideUp().remove();						
				},
				
				success: function(data){									
					if(data.status == true){
						
						$('div.blackbg').hide();	
						$('div.loadupload').hide();		
						$('#head_form_reg').addClass('finish').html('Terima Kasih. Selamat Bergabung.');
						$('#head_note_reg').html('Kami telah mengirim email berisi informasi mengenai detil selanjutnya, <b>silahkan cek email Anda</b>. (Jika tidak ada di inbox, mohon lihat dibagian bulk/spam).Jika masih tidak masuk silahkan menunggu 1x24 Jam, silahkan mengecek SPAM atau bisa langsung  masuk ke area admin ex: <strong><a href="http://www.kaffah.biz/login">www.kaffah.biz/login</a></strong>. <br /><br />Tim KaffahBiz');
					}
					else{

						$('div.blackbg').hide();	
						$('div.loadupload').hide();	
						$('#head_form_reg').addClass('finish').html('Registrasi Gagal.');
						$('#head_note_reg').html('Mohon maaf, Anda belum beruntung. Mohon dicoba kembali dengan me-refresh halaman ini, atau klik link ini <a href="http://www.kaffah.biz/reg">www.kaffah.biz/reg</a> untuk registrasi ulang.<br /><br />Tim KaffahBiz');
 
					}
				}
			});		
		}
	});

});

function checkemail(){	
	var emailval = $('#email_reg').val();
	if(emailval == '' || emailval == null){
		$('p#email_reg_status').removeClass('error').slideUp('fast');
		$('#email_reg').removeClass('true');
		window.emailstatus = false;
		$('#daftar').attr('disabled', 'disabled').addClass('disabled');
	}
	else{
		$.ajax({
			url: "/signup/check_email/",
			type: "POST",
			dataType: "json",
			data: {emailval : emailval},	
			beforeSend: function(){
			},
			
			success: function(data){				
				if(data.status == 'UNVALID'){
					$('p#email_reg_status').html('mohon memasukkan alamat email yang benar!').addClass('error').slideDown('fast');
					$('#email_reg').removeClass('true');
					$('#daftar').attr('disabled', 'disabled').addClass('disabled');
					window.emailstatus = false;
				}
				else if(data.status == false){
					$('p#email_reg_status').html('mohon maaf, email sudah terdaftar!').addClass('error').slideDown('fast');	
					$('#email_reg').removeClass('true');
					$('#daftar').attr('disabled', 'disabled').addClass('disabled');
					window.emailstatus = false;
				}
				else if(data.status == true){
					$('p#email_reg_status').html('').removeClass('error').slideUp('normal');	
					$('#email_reg').addClass('true');
					window.emailstatus = true;
					btnregactivate();
				}
			}
		});	
		
	}
}

function checkpassw(){
	var passval = $('#password_reg').val();
	if(passval == '' || passval == null){
		$('p#password_reg_status').removeClass('error').slideUp('fast');
		$('#password_reg').removeClass('true');	
		window.passwordstatus = false;
		$('#daftar').attr('disabled', 'disabled').addClass('disabled');
	}
	else{

		$.ajax({
			url: "/signup/check_passwd/",
			type: "POST",
			dataType: "json",
			data: {passval : passval},	
			beforeSend: function(){
			},
			
			success: function(data){	
				if(data.status == 'strong'){
					$('p#password_reg_status').html('').removeClass('error').slideUp('normal');	
					$('#password_reg').addClass('true');
					window.passwordstatus = true;
					btnregactivate();
				}	
				else if(data.status == 'weak'){
					$('p#password_reg_status').html('gunakan kombinasi huruf dan Angka').addClass('error').slideDown('fast');
					$('#password_reg').removeClass('true');	
					$('#daftar').attr('disabled', 'disabled').addClass('disabled');
					window.passwordstatus = false;
				}		
				else if(data.status == 'UNVALID'){
					$('p#password_reg_status').html('password kurang dari 7 digit').addClass('error').slideDown('fast');	
					$('#password_reg').removeClass('true');
					$('#daftar').attr('disabled', 'disabled').addClass('disabled');
					window.passwordstatus = false;
				}
				
			}
		});	

		
	}
}

function checkhp(){
	var hpval = $('#no_hp_reg').val();
	if(hpval == '' || hpval == null){
		$('p#no_hp_reg_status').removeClass('error').slideUp('fast');
		$('#no_hp_reg').removeClass('true');
		window.no_hpstatus = false;
		$('#daftar').attr('disabled', 'disabled').addClass('disabled');
	}
	else{		

		$.ajax({
			url: "/signup/check_hp/",
			type: "POST",
			dataType: "json",
			data: {hpval : hpval},	
			beforeSend: function(){
			},
			
			success: function(data){	
				if(data.status == true){
					$('p#no_hp_reg_status').html('').removeClass('error').slideUp('normal');	
					$('#no_hp_reg').addClass('true');
					window.no_hpstatus = true;
					btnregactivate();
				}

				else if(data.status == false){
					$('p#no_hp_reg_status').html('masukkan No.HP dengan format : 08xxx, contoh : 081234567890').addClass('error').slideDown('fast');	
					$('#no_hp_reg').removeClass('true');
					$('#daftar').attr('disabled', 'disabled').addClass('disabled');
					window.no_hpstatus = false;
				}

				else if(data.status == 'INVALID'){
					$('p#no_hp_reg_status').html('No.HP kurang dari 9 digit').addClass('error').slideDown('fast');	
					$('#no_hp_reg').removeClass('true');
					$('#daftar').attr('disabled', 'disabled').addClass('disabled');
					window.no_hpstatus = false;
				}

				else if(data.status == 'EXIST'){
					$('p#no_hp_reg_status').html('Maaf, No.HP sudah terdaftar').addClass('error').slideDown('fast');	
					$('#no_hp_reg').removeClass('true');
					$('#daftar').attr('disabled', 'disabled').addClass('disabled');
					window.no_hpstatus = false;
				}								
			}
		});			
		
	}	
}

function checkdom(){
	var alamatval = $('#alamat_web').val();
	var tld = $('#tld').val();

	if(alamatval == '' || alamatval == null){
		$('#daftar').attr('disabled', 'disabled').addClass('disabled');
		$('p#alamat_web_status').html('').removeClass('error').removeClass('true').slideUp('fast');
		window.alamat_webstatus = false;
	}
	else{						

		$.ajax({
			url: "/signup/check_dom/",
			type: "POST",
			dataType: "json",
			data: {alamatval: alamatval, tld: tld},	
			beforeSend: function(){
				$('p#alamat_web_status').addClass('loading').removeClass('true').removeClass('error').html('mohon menunggu sebentar...').slideDown('fast');
			},
			
			success: function(data){	
				

				if(data.status == true){
					$('p#alamat_web_status').html('alamat website <b>'+alamatval+tld+'</b> tersedia!').removeClass('error').removeClass('loading').addClass('true').slideDown('fast');
					window.alamat_webstatus = true;
					btnregactivate();	
				}
				else if(data.status == false){
					$('p#alamat_web_status').html('maaf alamat '+alamatval+tld+' tidak tersedia!').removeClass('loading').removeClass('true').addClass('error').slideDown('fast');
					window.alamat_webstatus = false;
					$('#daftar').attr('disabled', 'disabled').addClass('disabled');
				}
				else if(data.status == 'INVALID_DOM'){
					$('p#alamat_web_status').html('mohon tidak menggunakan "www."').removeClass('loading').removeClass('true').addClass('error').slideDown('fast');
					window.alamat_webstatus = false;
					$('#daftar').attr('disabled', 'disabled').addClass('disabled');
				}

				else {
					$('p#alamat_web_status').html('alamat '+alamatval+tld+' tidak valid. "Gunakan huruf, angka, dan -"').removeClass('loading').removeClass('true').addClass('error').slideDown('fast');
					window.alamat_webstatus = false;
					$('#daftar').attr('disabled', 'disabled').addClass('disabled');
				}

				
			}
		});			
	}	
}

function btnregactivate(){
	if(window.emailstatus == true && window.passwordstatus == true && window.no_hpstatus == true && window.alamat_webstatus == true && window.syarat == true){
		$('#daftar').removeAttr('disabled').removeClass('disabled');	
	}
}