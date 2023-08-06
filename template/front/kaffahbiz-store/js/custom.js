$(document).ready(function(){
	window.emailstatus = false;
	window.passwordstatus = false;
	window.no_hpstatus = false;
	window.alamat_webstatus = false;
	window.syarat = false;


	$('div.rone h3').click(function(){
		$(this).siblings('div.rcontent').slideToggle('fast');				

		var em = $(this).children('em').attr('class');
		
		if(em == 'arrow'){
			$(this).children('em').removeClass('arrow').addClass('arrowclick');
			
		}
		else{
			$(this).children('em').removeClass('arrowclick').addClass('arrow');
			
		}

		
	});

	jenis_reseller();	

	/* login to web reseller */

	$('#loginlink, .loginlink').click(function(eve){
		eve.preventDefault();
		$('#formloginlink').submit();
	})

	/* registration */

	$('#nama_lengkap').keyup(function(){
		clearTimeout($.data(this, 'timer'));
		var wait = setTimeout(checkname, 500);
		$(this).data('timer', wait);	
	});

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
			var jenis_reseller = $('#jenis_reseller').val();
			var nama_lengkap = $('#nama_lengkap').val();
			var provinsi = $('#provinsi').val();
			var provinsi_kota = $('#provinsi_kota').val();
			var alamat_lengkap = $('#alamat_lengkap').val();
			var email = $('#email_reg').val();
			var password = $('#password_reg').val();
			var no_hp = $('#no_hp_reg').val();
			var alamat = $('#alamat_web').val();
			var tld = $('#tld').val();

			$.ajax({
				url: "/signup/finishing/",
				type: "POST",
				dataType: "json",
				data: {jenis_reseller:jenis_reseller, nama_lengkap:nama_lengkap, provinsi:provinsi, provinsi_kota:provinsi_kota, alamat_lengkap:alamat_lengkap, email:email, password:password, no_hp:no_hp, alamat:alamat, tld:tld},	
				beforeSend: function(){
					$('div.loadupload').html();
					$('div.blackbg').show();	
					$('div.loadupload').show();	
					$('div.blog_main.single h2').html('...');
					$('p.headin').html('');
					$('#form_reg').slideUp().remove();						
				},
				
				success: function(data){									
					if(data.status == true){
						
						$('div.blackbg').hide();	
						$('div.loadupload').hide();		
						$('div.blog_main.single h2').html('Terima Kasih. Selamat Bergabung.');
						$('p.headin').html('Kami telah mengirim email berisi informasi mengenai detil selanjutnya, <b>silahkan cek email Anda</b>. (Jika tidak ada di inbox, mohon lihat dibagian bulk/spam/promosi).Jika masih tidak masuk silahkan menunggu 1x24 Jam, silahkan mengecek SPAM atau bisa langsung  masuk ke area member ex: <strong><a href="http://store.kaffahbiz.co.id/user/login">store.kaffahbiz.co.id/user/login</a></strong>. <br /><br />Tim KaffahStore');
					}
					else{

						$('div.blackbg').hide();	
						$('div.loadupload').hide();	
						$('div.blog_main.single h2').html('Registrasi Gagal.');
						$('p.headin').html('Mohon maaf, Anda belum beruntung. Mohon dicoba kembali dengan me-refresh halaman ini, atau klik link ini <a href="http://www.kaffah.biz/reg">www.kaffah.biz/reg</a> untuk registrasi ulang.<br /><br />Tim KaffahBiz');
 
					}
				}
			});		
		}
	});
	
	imagelist();
});

function checkname(){
	var nama_lengkap = $('#nama_lengkap').val();
	if(nama_lengkap == '' || nama_lengkap == null){
		$('#nama_lengkap_status').slideDown();
		$('#nama_lengkap').removeClass('true');
	}
	else{
		$('#nama_lengkap').addClass('true');
		$('#nama_lengkap_status').slideUp();
	}
}

function checkpinbb(){
	var pinbb = $('#pinbb').val();
	if(pinbb == '' || pinbb == null){		
		$('#pinbb').removeClass('true');
		$('#pinbb_status').slideDown();
	}
	else{
		$('#pinbb').addClass('true');
		$('#pinbb_status').slideUp();
	}
}

function checkwa(){
	var wa = $('#wa').val();
	if(wa == '' || wa == null){		
		$('#wa').removeClass('true');
		$('#wa_status').slideDown();
	}
	else{
		$('#wa').addClass('true');
		$('#wa_status').slideUp();
	}
}

function checktelegram(){
	var telegram = $('#telegram').val();
	if(telegram == '' || telegram == null){		
		$('#telegram').removeClass('true');
		$('#telegram_status').slideDown();
	}
	else{
		$('#telegram').addClass('true');
		$('#telegram_status').slideUp();
	}
}

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

function jenis_reseller(){
	$("#jenis_reseller").change(function() {
		var jenisreseller = $(this).val();
		$('div.iffree').hide();
		$('div.formreseller').show();
		$('#tld option').remove();
		switch(jenisreseller){
			case "silver" : 				
				$('#tld').append('<option value=".com" selected>.com</option><option value=".net">.net</option>');
				break;				

			case "basic" : 
				$('#tld').append('<option value=".ol-shop.net">.ol-shop.net</option><option value=".shopp.id" selected>.shopp.id</option><option value=".onweb.id">.onweb.id</option>');
				break;
			
			case "system" : 
				$('#tld').append('<option value=".ol-shop.net">.ol-shop.net</option><option value=".shopp.id" selected>.shopp.id</option><option value=".onweb.id">.onweb.id</option>');
				break;

			case "free" :
				$('div.formreseller').hide();
				$('div.iffree').show();
				break;	
			
			default: break;
		}


	});
}

function checkdom(){
	var alamatval = $('#alamat_web').val();
	var tld = $('#tld').val();	

	if(alamatval == '' || alamatval == null){
		$('#daftar').attr('disabled', 'disabled').addClass('disabled');
		$('p#alamat_web_status').html('').removeClass('error').removeClass('true').slideUp('fast');
		window.alamat_webstatus = false;
	}
	else if(tld == '-'){
		$('p#alamat_web_status').html('Silahkan memilih jenis reseller terlebih dahulu!').removeClass('loading').removeClass('true').addClass('error').slideDown('fast');
		window.alamat_webstatus = false;
		$('#daftar').attr('disabled', 'disabled').addClass('disabled');
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

function imagelist(){
	$('div.wraplistimg ul li img').click(function(){
		var src = $(this).attr('src');
		$('div#bigdisplayimg img').attr('src', src);
	});
}