$(document).ready(function(){
	var web_address = 'http://'+window.location.hostname ;
	
	/* variable init */
	var status = null ;
	var limit = $('#limit').val();
	var page = 1;		
	var temp = null;

	linkinit(status,limit,page);
	initloadBloglist(status,limit,page);
	searching(status,limit,page);
	categoryfilter(status,limit,page);

	/* for popup */
	$('#addblog').click(function(eve){
			eve.preventDefault();
			$('div.blackbg').fadeIn('fast');
			$('div.popup').slideDown('fast');
	});
		
	$('a.close').click(function(eve){
			eve.preventDefault();
			$('div.popup').slideUp('fast');
			$('div.blackbg').fadeOut('fast');
			$('#formaddblog').find('input[type=text], textarea').val('');
			$('#formaddblog').children('p.notes').html('Silahkan masukkan alamat website, contoh: <b>demo.kaffah.biz</b>').removeClass('domaincheck');
			$('#addnewbtn').attr('disabled', 'disable').addClass('disable');
			var temp = null;
	});

	/* blog new registration */
	$('#newdomain').keyup(function(){
		/* 	domain ini di ambil dari inputan yang diketikan oleh userAgent
			autoincomplete off itu mencegah user untuk dapat memilih inputan menggunakan 
		*/
		var domain =  $(this).val();	
		var domaintld = $('#domaintld').val();	
		
		/* lakukan pengecekan menggunakan ajax */
		$.ajax('http://www.kaffah.biz/req/domaincheck', {
			dataType : 'json',
			type : 'POST',
			data: $('#formaddblog').serialize(),
			success: function(msg){
				// $('p.notes').addClass('domaincheck').html(html.fixdomain);	
				
				if(msg.domainexist == 1){
					$('#hiddenval').val(0);
					$('#addnewbtn').attr('disabled', 'disable').addClass('disable');
					$('p.notes').addClass('domaincheck').html('<p class="red"><strong>'+msg.fixdomain+'</strong> tidak tersedia</p>');	
				}
				else{
					$('#hiddenval').val(1);
					$('p.notes').addClass('domaincheck').html('<p class="grey"><strong>'+msg.fixdomain+'</strong> website tersedia</p>');
					
					/* mengetahui apakah sudah*/
					var title = $('#title').val();
					var newdomain = $('#newdomain').val();
					var description = $('#description').val();
					var hiddenval = $('#hiddenval').val();
					var templatechoose = $('#templatechoose').val();
					
					/* ketika semuanya sudah di isi */
					if((title != '') && (newdomain != '') && (description != '') && (hiddenval != 0) && (templatechoose != 0)){
						$('#addnewbtn').removeAttr('disabled').removeClass('disable');
					}						
				}
			},
		});
	});	
	
	/* blog new registration */
	$('#domaintld').change(function(){
		/* 	domain ini di ambil dari inputan yang diketikan oleh userAgent
			autoincomplete off itu mencegah user untuk dapat memilih inputan menggunakan 
		*/
		var domain =  $(this).val();	
		var domaintld = $('#domaintld').val();	
		
		/* lakukan pengecekan menggunakan ajax */
		$.ajax('http://www.kaffah.biz/req/domaincheck', {
			dataType : 'json',
			type : 'POST',
			data: $('#formaddblog').serialize(),
			success: function(msg){
				// $('p.notes').addClass('domaincheck').html(html.fixdomain);	
				if(msg.domainexist == 1){
					$('#hiddenval').val(0);
					$('#addnewbtn').attr('disabled', 'disable').addClass('disable');
					$('p.notes').addClass('domaincheck').html('<p class="red"><strong>'+msg.fixdomain+'</strong> tidak tersedia</p>');	
				}
				else{
					$('#hiddenval').val(1);
					$('p.notes').addClass('domaincheck').html('<p class="grey"><strong>'+msg.fixdomain+'</strong> website tersedia</p>');	
				}
			},
		});	
	});	
	
	/* ketika tombol tambah blog baru di klik */
	$('#addnewbtn').click(function(eve){
		$(this).attr('disabled', 'disable').addClass('disable');
		eve.preventDefault();
		
		/* lakukan pengecekan menggunakan ajax */
		$.ajax('http://www.kaffah.biz/req/adddomain', {
			dataType : 'html',
			type : 'POST',
			data: $('#formaddblog').serialize(),
			before: function(){
				$('div.wrappop').css('background', '#fff url("'+web_address+'/assets/images/ajax-loader.gif") no-repeat center 30px ');
			},
			success: function(msg){
				if(msg == 1){
					$('div.popup').slideUp('fast');
					$('div.blackbg').fadeOut('fast');
					$('#formaddblog').find('input[type=text], textarea').val('');
					$('#formaddblog').children('p.notes').html('Silahkan masukkan alamat website, contoh: <b>demo.kaffah.biz</b>').removeClass('domaincheck');
					$('#addnewbtn').attr('disabled', 'disable').addClass('disable');
					$('#bloglist').find('tbody').children('tr').remove();
					loadBloglist(status,limit,page);
				}
				else{
					
				}
			},
		});			
	});
	
	$(document).on('submit','#form_update_domain', function(eve){

		eve.preventDefault();
		
		var domain = $('#domain').val();		
		/* yang ini digunakan untuk mengupdate domain sesuai dengan domain yang diedit */		
		$.ajax('http://www.kaffah.biz/req/updatedomain', {
			dataType : 'json',
			type : 'POST',
			data: $('#form_update_domain').serialize(),
			beforeSend: function(){
				$('div.popup').hide();
			 	$('div.blackbg').show();	
				$('div.loadupload').show();	
			},
			success: function(msg){
				if(msg.status == true){
					var page = parseInt($('#currpagebloglist').html());
					loadBloglist(status,limit,page);
					$('a.close').click();				
				}
				else{
					$('div.loadupload').hide();										
					$('div.wrappop').html('<h2 class="headpop">Konfirmasi Aktivasi Website</h2><p>Mohon maaf, aktivasi website <b>"'+domain.replace('www.','')+'"</b> GAGAL!!! Domain sudah Ada, silahkan hapus dulu yang sebelumnya sudah ada, atau silahkan gunakan nama domain yang lain.</p>');
					$('div.popup').css('width','640px').css('height','auto').css('margin-top','5%');	
					$('div.popup').show();				
				}

			}
		});

		$(this).die('click');
	});

	/* ketika tombol add blog di klik */
	$('#formaddblog').keyup(function(){
		/* $('#addnewbtn').removeAttr('disabled');*/
		var title = $('#title').val();
		var newdomain = $('#newdomain').val();
		var description = $('#description').val();
		var hiddenval = $('#hiddenval').val();
		var templatechoose = $('#templatechoose').val();
		
		/* ketika semuanya sudah di isi */
		if((title != '') && (newdomain != '') && (description != '') && (hiddenval != 0) && (templatechoose != 0)){
			$('#addnewbtn').removeAttr('disabled').removeClass('disable');
		}
		else{
			$('#addnewbtn').attr('disabled', 'disable').addClass('disable');
		}	
	});
	
	$(document).on('click','a.fast_action', function(eve){
		eve.preventDefault();	
		$('div.loadupload').hide();	
		var domain_name = $(this).attr('title');
		var domain_id = $(this).attr('id').replace('action_','');	
		var url = 'http://www.kaffah.biz/req/getdomaindetail';
		

		return JSON.parse($.ajax({
		     type: 'POST',
		     url: url,
		     dataType: 'json',
			 data : {domain_id:domain_id},
		     global: false,
		     async:false,
		     before: function(data){
		     	$('div.blackbg').show();	
				$('div.loadupload').show();	
		     },
		     success: function(data) {
			   	if(data.status == true){			   		
			   		var dateactivated = data[0].domain_activated.split('-');					
					var dayactivated = dateactivated[2];
					var monthactivated = dateactivated[1];
					var yearctivated = dateactivated[0];

					var dateexpired = data[0].domain_expire.split('-');
					var dayexpired = dateexpired[2];
					var monthexpired = dateexpired[1];
					var yearexpired = dateexpired[0];

					var date = data[0].tanggal_lahir.split('-');
					var fixdate = date[2]+'/'+date[1]+'/'+date[0];	
					
					if(data[0].handphone == null){
						data[0].handphone = '-';
					}

					$('div.popup').css('width','60%').css('height','70%').css('margin-top','5%');
					$('div.wrappop').html(
						'<h2 class="headpop">Aksi Cepat Pengeditan Web "'+data[0].domain_name+'"</h2>'+
						'<div class="overflowform">'+
						'<form id="form_update_domain" method="post" accept-charset="utf-8">'+
						'<label for="domain">Nama Domain</label><input type="text" class="input_text long_text" name="domain" id="domain" value="'+data[0].domain_name+'"> <br>'+
						'<label for="owner">Pemilik</label><input type="text" disabled="disabled" class="disable input_text long_text" name="owner" id="owner" value="'+data[0].name+'"> <br>'+
						'<label for="email">Email</label><input type="text" disabled="disabled" class="disable input_text long_text" name="email" id="email" value="'+data[0].email+'"> <br>'+
						'<label for="packet">User Verifikasi</label><select name="user_verify" id="user_verify" class="action edit"><option value="" >Pilih Verifikasi</option><option value="0" >Gagal Verifikasi</option><option value="1">Sudah Diverifikasi</option><option value="2">Meminta Verifikasi</option></select><br>'+
						'<label for="handphone">No. HP</label><input type="text" disabled="disabled" class="disable input_text long_text" name="handphone" id="handphone" value="'+data[0].handphone+'">'+
						'<p class="note">info selengkapnya <a href="#" class="detail_info">klik disini</a></p><br />'+
						
						'<div class="detail_info">'+
						'<label for="facebook">Facebook</label><input type="text" disabled="disabled" class="disable input_text long_text" name="facebook" id="facebook" value="'+data[0].facebook+'">'+
						'<label for="jenis_kelamin">Jenis Kelamin</label><input type="text" disabled="disabled" class="disable uppercase input_text long_text" name="jenis_kelamin" id="jenis_kelamin" value="'+formatString(data[0].jenis_kelamin)+'">'+
						'<label for="tempat_lahir">Tempat/Tgl Lahir</label><input type="text" disabled="disabled" class="disable uppercase input_text long_text" name="tempat_lahir" id="tempat_lahir" value="'+formatString(data[0].tempat_lahir)+', '+fixdate+'">'+
						'<label for="provinsi">Kota/Provinsi</label><input type="text" disabled="disabled" class="disable input_text uppercase long_text" name="provinsi" id="provinsi" value="'+formatString(data[0].kota)+', '+formatString(data[0].provinsi)+'">'+
						'<label for="alamat">Alamat</label>'+
						'<textarea class="long_text disable" disabled="disabled" name="alamat" id="alamat">'+data[0].alamat+'</textarea>'+
						'<br>'+
						'<label for="noktp">No.KTP</label><input type="text" disabled="disabled" class="disable input_text uppercase long_text" name="noktp" id="noktp" value="'+formatString(data[0].noktp)+'">'+
						'<label for="scanktp">Scan KTP</label><a href="'+data[0].scanktp+'" class="uploadimg linkimg" target="_blank" id="link_ktp">'+data[0].scanktp.substring(7,80)+'...</a><br />'+
						'<label for="scanid">Scan ID</label><a href="'+data[0].scanid+'" class="uploadimg linkimg" target="_blank" id="link_id">'+data[0].scanid.substring(7,80)+'...</a><br />'+
						'</div>'+
						
						'<label for="packet">Paket Website</label><select name="packet" id="packet" class="action edit"><option value="1" selected="selected">Free (Rp 0 /tahun)</option><option value="3">Basic (Rp 99.000/tahun)</option><option value="2">Silver (Rp 555.000 /tahun)</option></select><br>'+
						'<label for="tanggal_aktif">Tanggal Aktif</label><input type="text"  name="day_start" id="day_start" class="input_text sort_text dates">'+
						'<select name="month_start" id="month_start" class="action edit sort">'+
						'<option value="01">Januari</option>'+
						'<option value="02">Februari</option>'+
						'<option value="03">Maret</option>'+
						'<option value="04">April</option>'+
						'<option value="05">Mei</option>'+
						'<option value="06">Juni</option>'+
						'<option value="07">Juli</option>'+
						'<option value="08">Agustus</option>'+
						'<option value="09">September</option>'+
						'<option value="10">Oktober</option>'+
						'<option value="11">November</option>'+
						'<option value="12">Desember</option>'+
						'</select>'+
						'<input type="text" name="year_start" id="year_start" class="input_text sort_text dates years"><br>'+
						'<label for="tanggal_habis">Tanggal Habis</label><input type="text"  id="day_expire" name="day_expire" class="input_text sort_text dates">'+
						'<select name="month_expire" id="month_expire" class="action edit sort">'+
						'<option value="01">Januari</option>'+
						'<option value="02">Februari</option>'+
						'<option value="03">Maret</option>'+
						'<option value="04">April</option>'+
						'<option value="05">Mei</option>'+
						'<option value="06">Juni</option>'+
						'<option value="07">Juli</option>'+
						'<option value="08">Agustus</option>'+
						'<option value="09">September</option>'+
						'<option value="10">Oktober</option>'+
						'<option value="11">November</option>'+
						'<option value="12">Desember</option>'+
						'</select><input type="text" name="year_expire" id="year_expire" name="year_expire" class="input_text sort_text dates years"><br>'+
						'<label for="domain_status">Status Domain</label>'+
						'<select name="domain_status" id="domain_status" class="action edit">'+
						'<option value="0">Non Aktif</option>'+
						'<option value="1" >Aktif</option>'+
						'</select><br>'+
						'<label for="domain_verify">Status Verifikasi</label>'+
						'<select name="domain_verify" id="domain_verify" class="action edit">'+
						'<option value="0" >Belum Diverifikasi</option>'+
						'<option value="1">Terverifikasi</option>'+
						'</select><br>'+
						'<input type="hidden" name="id" value="'+data[0].domain_id+'">'+
						'<input type="hidden" name="user_id" value="'+data[0].user_id+'">'+
						'<input type="submit" value="Update Domain!" class="btnAct addSubmit">'+
						'</form></div>');		
					
					$('#day_start').val(dayactivated);
					$('#year_start').val(yearctivated);
					$('#month_start option[value='+monthactivated+']').prop('selected', true);

					$('#day_expire').val(dayexpired);
					$('#year_expire').val(yearexpired);
					$('#month_expire option[value='+monthexpired+']').prop('selected', true);


					$('#packet option[value='+data[0].packet_id+']').prop('selected', true);
					$('#domain_status option[value='+data[0].domain_status+']').prop('selected', true);
					$('#domain_verify option[value='+data[0].domain_verify+']').prop('selected', true);
					$('#user_verify option[value='+data[0].user_verify+']').prop('selected', true);
					$('div.blackbg').fadeIn(100);
					$('div.popup').slideDown(0);	
					$('div.loadupload').hide();			   		
			   	}
		        return data;

		    }
		 }).responseText);	

		$(this).die('click');
	});


	
	$(document).on('click','a.detail_info', function(eve){
		eve.preventDefault();
		$('div.detail_info').slideToggle('fast');
		$(this).die('click');
	});

	$(document).on('click','a.delete_action', function(eve){
		eve.preventDefault();	
		var title = $(this).attr('title');
		temp = $(this).attr('id').replace('delete_','');				
		$('div.popup').css('width','640px').css('height','auto').css('margin-top','5%');
		$('div.wrappop').html('<h2 class="headpop">Konfirmasi Hapus Website</h2><p>Apakah Anda serius akan menghapus semua konten website <b>"'+title.replace('www.','')+'"</b> ? Semua database pada website tersebut berupa artikel, produk, gambar dan semua atributnya akan dihapus sesaat setelah Anda mengklik tombol "Ya".</p><a id="confirm_delete_yes" class="confirmbox confirm_yes">Ya</a><a id="confirm_delete_no" class="confirmbox confirm_no">Tidak</a> ');
		$('div.blackbg').fadeIn('fast');
		$('div.popup').slideDown('fast');	
		$('div.loadupload').hide();			
	});

	$(document).on('click','a.status_action_publish', function(eve){
		eve.preventDefault();
		var title = $(this).attr('title');
		temp = $(this).attr('id').replace('status_','');
		$('div.popup').css('width','640px').css('height','auto').css('margin-top','5%');
		$('div.wrappop').html('<h2 class="headpop">Konfirmasi Aktivasi Website</h2><p>Apakah Anda ingin mengaktifkan website <b>"'+title.replace('www.','')+'"</b> ? Pastikan akun tersebut sudah menyelesaikan administrasinya!</p><a id="confirm_publish_yes" class="confirmbox confirm_yes">Ya</a><a id="confirm_publish_no" class="confirmbox confirm_no">Tidak</a> ');
		$('div.blackbg').fadeIn('fast');
		$('div.popup').slideDown('fast');		
		$('div.loadupload').hide();		
	});

	$(document).on('click','a.status_action_pending', function(eve){
		eve.preventDefault();
		var title = $(this).attr('title');
		temp = $(this).attr('id').replace('status_','');
		$('div.popup').css('width','640px').css('height','auto').css('margin-top','5%');
		$('div.wrappop').html('<h2 class="headpop">Konfirmasi Menonaktifkan Website</h2><p>Apakah Anda ingin menonaktifkan website <b>"'+title.replace('www.','')+'"</b> ? Mohon cek kembali administrasinya sebelum benar-benar menonaktifkan website ini</p><a id="confirm_pending_yes" class="confirmbox confirm_yes">Ya</a><a id="confirm_pending_no" class="confirmbox confirm_no">Tidak</a> ');
		$('div.blackbg').fadeIn('fast');
		$('div.popup').slideDown('fast');		
		$('div.loadupload').hide();		
	});	

	/* confirm_publish_yes */
	$(document).on('click','a#confirm_delete_yes', function(eve){	
		eve.preventDefault();		
		var domain_id = temp;		
		$('a.close').click();
		var url = 'http://www.kaffah.biz/req/deletedomain';
		$('a.close').click();
		return JSON.parse($.ajax({
		     type: 'POST',
		     url: url,
		     dataType: 'json',
			 data : {domain_id:domain_id},
		     global: false,
		     async:false,
		     before: function(data){
		     	$('div.popup').hide(0);
		     	$('div.blackbg').show();	
				$('div.loadupload').show();	
		     },
		     success: function(data) {
				if(data.status == true){
					$('div.blackbg').hide();	
					$('div.loadupload').hide();	
					var page = parseInt($('#currpagebloglist').html());
					loadBloglist(status,limit,page);				
				}
				else{
					$('div.loadupload').hide();										
					$('div.wrappop').html('<h2 class="headpop">Konfirmasi Delete Website</h2><p>Mohon maaf, delete website <b>"'+domain.replace('www.','')+'"</b> GAGAL!!! </p>');
					$('div.popup').css('width','640px').css('height','auto').css('margin-top','5%');
					$('div.blackbg').show();
				}
		    }
		 }).responseText);	

		 $(this).die('click');		
	});	

	$(document).on('click','a#confirm_publish_yes', function(eve){	
		eve.preventDefault();				
		var domain_id = temp;		
		var url = 'http://www.kaffah.biz/req/activationdomain';
		$('a.close').click();
		return JSON.parse($.ajax({
		     type: 'POST',
		     url: url,
		     dataType: 'json',
			 data : {domain_id:domain_id,action:'publish'},
		     global: false,
		     async:false,
		     before: function(data){
		     	$('div.blackbg').show();	
				$('div.loadupload').show();	
		     },
		     success: function(data) {
				if(data.status == true){
					$('div.blackbg').hide();	
					$('div.loadupload').hide();	
					var page = parseInt($('#currpagebloglist').html());
					loadBloglist(status,limit,page);				
				}

				$('div.blackbg').hide();	
				$('div.loadupload').hide();					
		    }
		 }).responseText);	

		$(this).die('click');
	});	

	$(document).on('click','a#confirm_pending_yes', function(eve){	
		eve.preventDefault();				
		var domain_id = temp;		
		var url = 'http://www.kaffah.biz/req/activationdomain';
		$('a.close').click();
		return JSON.parse($.ajax({
		     type: 'POST',
		     url: url,
		     dataType: 'json',
			 data : {domain_id:domain_id,action:'pending'},
		     global: false,
		     async:false,
		     before: function(data){
		     	$('div.blackbg').show();	
				$('div.loadupload').show();	
		     },
		     success: function(data) {

				if(data.status == true){
					$('div.blackbg').hide();	
					$('div.loadupload').hide();	
					var page = parseInt($('#currpagebloglist').html());
					loadBloglist(status,limit,page);				
				}

				$('div.blackbg').hide();	
				$('div.loadupload').hide();	
		    }
		 }).responseText);	

		$(this).die('click');
	});		

	$(document).on('click','a.confirm_no', function(eve){	
		eve.preventDefault();
		var temp = null;
		$('a.close').click();
	});			

});

function initloadBloglist(status,limit,page){
	bloglist = loadBloglist(status,limit,page);	
	/*if(bloglist != null){
		var domain_id = [];
		$.each(bloglist, function(index, element) {
			domain_id.push(element.domain_id); 
		});		
		loadtransactioncount(domain_id);
	}
	*/
}

function categoryfilter(status,limit,page){
	/*  ketika kategori di hover */
	$('div.postfilter').hover(function(){
		$(this).children('#postfilter').show();
	}, function(){
		$(this).children('#postfilter').hide();
	});
	
	/* ketika category filternya di klik */
	$(document).on('click','#postfilter ul li a.catfilter', function(eve){
	//$('#postfilter ul li a.catfilter').click(function(eve){
		eve.preventDefault();
		var valuea = $(this).attr('name');
		$('#filterwebsite').val(valuea);		
		$('em.postfilter').html($(this).html());
		$('em.postsortir').html('Sortir Website');
		$('#search').val('');
		$('#sortirwebsite').val('all');
		$('#postfilter').hide();
		initloadBloglist(status,limit,page); 
		$(this).die('click');
		return false;
	});

	$('div.postsortir').hover(function(){
		$(this).children('#postsortir').show();
	}, function(){
		$(this).children('#postsortir').hide();
	});	

	$(document).on('click','#postsortir ul li a.catsort', function(eve){
	//$('#postfilter ul li a.catfilter').click(function(eve){
		eve.preventDefault();
		var valuea = $(this).attr('name');
		$('#sortirwebsite').val(valuea);		
		$('em.postsortir').html($(this).html());
		$('em.postfilter').html('Filter Website');
		$('#search').val('');
		$('#filterwebsite').val('');
		$('#postsortir').hide();
		initloadBloglist(status,limit,page); 
		$(this).die('click');
		return false;
	});	
}

function searching(status,limit,page){
	var web_address = 'http://'+window.location.hostname ;	
	$(document).on('keyup','#search', function(){

		delay(function(){
	      initloadBloglist(status,limit,page);
	    }, 800 );	
	});		
}

function loadBloglist(status,limit,page){
	var web_address = 'http://'+window.location.hostname ;

	var x = 0;	
	var url = null;

	var pageinit = paginginit(page);

	if(page != '') page = page - 1;		
	else page = 0;		
	var offset = page * pageinit.per_page;

	/* preparing for textsearch and category */
	textsearch = $('#search').val();
	filtercategory = $('#filterwebsite').val();
	sortirwebsite = $('#sortirwebsite').val();	

	/* ketika textsearch dan kategori tidak kosong */
	if(textsearch != null && textsearch != ''){
		url = 'http://www.kaffah.biz/req/getalldomain/'+limit+'/'+offset+'/s/'+textsearch;
	}
	else if(filtercategory != null && filtercategory != ''){
		url = 'http://www.kaffah.biz/req/getalldomain/'+limit+'/'+offset+'/f/'+filtercategory;
	}
	else if(sortirwebsite != null && sortirwebsite != ''){
		url = 'http://www.kaffah.biz/req/getalldomain/'+limit+'/'+offset+'/sort/'+sortirwebsite;
	}	
	else{
		url = 'http://www.kaffah.biz/req/getalldomain/'+limit+'/'+offset;
	}


	return JSON.parse($.ajax({
	     type: 'POST',
	     url: url,
	     dataType: 'json',
		 data : {},
	     global: false,
	     async:false,
	     beforeSend: function(data){
	     	$('div.blackbg').show();	
			$('div.loadupload').show();	
	     },
	     success: function(data) {	     	
	     	$('#bloglist tbody tr').remove();	 
	     	 	     	
	     	if(data.status == false && data.status != undefined){	     		
	     	}
	     	else {

			    $.each(data, function(index, element) {
					
					/* jika ada NULL dalam element */
					var commenttotal = element.comment_total;
					var datetime = element.domain_expire.split(' ');
					var date = datetime[0].split('-');
					var fixdate = date[2]+'/'+date[1]+'/'+date[0];	
					if(commenttotal == null) commenttotal = 0;
					if(element.domain_status == 0){
						var addclass = 'unactive';
						var domain_status_text = "Aktifkan";
						var domain_status_url = '#publish';
						var domain_status_action = 'publish';
					}
					else{
						var addClass = null;
						var domain_status_text = "Non Aktifkan";
						var domain_status_url = '#pending';
						var domain_status_action = 'pending';
					}
					
					if(element.domain_verify == 0){
						var domain_verify_text = "Belum verifikasi";
						var domain_verify_class = "warn";
					}
					else{
						var domain_verify_text = "Terverifikasi";
						var domain_verify_class = ""; 
					}

					if(element.domain_reseller == ''){
						if(element.packet_id == 1){
							packet_name = 'Free';
						}
						else if(element.packet_id == 2){
							packet_name = 'Silver';
						}
						else if(element.packet_id == 3){
							packet_name = 'Basic';
						}
					}
					else{
						if(element.packet_id == 1){
							packet_name = '';
						}
						else if(element.packet_id == 2){
							packet_name = '<b>R-Silver</b>';
						}
						else if(element.packet_id == 3){
							packet_name = '<b>R-Basic</b>';
						}	
					}

					

					/* mengisikan table dari hasil json */				
			        $('#bloglist').find('tbody')
						.append($('<tr>')
								.append(
									$('<td>').attr('class','blogname').append($('<h2>').attr('class',addclass).append($('<a>',{text: element.domain_name.replace('www.','')}).attr('href','/site/member/'+element.domain_id+'/#dashboard')), $('<p>').html('<b>'+element.domain_title+' - </b> '+element.domain_desc), $('<p>').html('<em class="type">'+packet_name+'</em><em class="expire">'+fixdate+'</em><em class="key '+domain_verify_class+'">'+domain_verify_text+'</em>'))
									,$('<td>').attr('class','middle').append($('<em>', {text:""}).attr('class', 'article').attr('id', 'article_id_'+element.domain_id))
									,$('<td>').attr('class','middle').append($('<em>', {text:""}).attr('class', 'product').attr('id', 'product_id_'+element.domain_id))
									,$('<td>').attr('class','middle').append($('<em>', {text:""}).attr('class', 'image').attr('id', 'image_id_'+element.domain_id))
									,$('<td>').attr('class','middle').append($('<em>', {text:""}).attr('class', 'trans').attr('id', 'transaction_id_'+element.domain_id))
									,$('<td>').attr('class','middle').append($('<em>', {text:""}).attr('class', 'comment').attr('id', 'comment_id_'+element.domain_id))
									,$('<td>').attr('class','action').append($('<a>').attr('class','actionblog').attr('href','/site/member/#web')
										.append($('<em>',{text:"Aksi Web"}),$('<ul>').attr('class','actionblog').append(									
											$('<li>').append($('<a>',{text:"Aksi Cepat"}).attr('href', '/site/member/'+element.domain_id+'/#fast').attr('class','fast_action').attr('id','action_'+element.domain_id).attr('title',element.domain_name)),									
											$('<li>').append($('<a>',{text:domain_status_text}).attr('href', '/site/member/'+element.domain_id+'/'+domain_status_url).attr('class','status_action_'+domain_status_action).attr('id','status_'+element.domain_id).attr('title',element.domain_name)),
											$('<li>').append($('<a>',{text:"Hapus"}).attr('href', '/site/member/'+element.domain_id+'/#delete').attr('class','delete_action').attr('id','delete_'+element.domain_id).attr('title',element.domain_name))									
											)
										
										)
											,$('<a>',{text:"Lihat Website"}).attr('class','lihatblog').attr('href','http://'+element.domain_name).attr('target', '_blank'))
								)
						);
			    });	
			}
			
			$('div.blackbg').fadeOut();
			$('div.loadupload').fadeOut();
	        return data;

	    }
	 }).responseText);

	$('div.blackbg').hide();	
	$('div.loadupload').hide();
}

function linkinit(status,limit,page){
	/* ini digunakan untuk menjelaskan bahwa page yang di akses di adalah : */
	$(document).on('click','#numcountbloglist ul li a, .forwardbloglist, .prevbloglist', function(eve){
		eve.preventDefault();		
		var valname = $(this).attr('name');

		$.getJSON('http://www.kaffah.biz/req/pagingbloglist/'+valname, { }, function(data) {
			$('#page').val(data.pagebloglistcurrent);			
			page = $('#page').val();

			initloadBloglist(status,limit,page);
		});			
		
		$('#numcountbloglist ul').hide();
		$(this).die('click');		
	});
}

function paginginit(page) {
	var paginglink = null;
	var pagetemp = null;
	var url = null;

	if(page == '') page = 1;
	
	textsearch = $('#search').val();
	filtercategory = $('#filterwebsite').val();

	/* ketika textsearch dan kategori tidak kosong */
	if(textsearch != null && textsearch != ''){
		url = 'http://www.kaffah.biz/req/bloglistpaginginit/s/'+textsearch;
	}
	else if(filtercategory != null && filtercategory != ''){
		url = 'http://www.kaffah.biz/req/bloglistpaginginit/f/'+filtercategory;
	}
	else{	
		url = 'http://www.kaffah.biz/req/bloglistpaginginit/';
	}	
	
	
	/* ambil paging */
	return JSON.parse($.ajax({
	 type: 'POST',
	 url: url,
	 dataType: 'json',
	 global: false,
	 async:false,
	 success: function(msg) {
	 	paginglink = '<ul>';

		$('#currpagebloglist').html(page);			

		if(page == '1' && page == msg.total_page ){
			forward = parseInt(page) ;
			prev = parseInt(page);
		}
		
		else if(page == '1' ){
			forward = parseInt(page) + parseInt(1);
			prev = parseInt(page);
		}
		
		else if(page == msg.total_page){
			forward = parseInt(page);
			prev = parseInt(page) - parseInt(1);			
		}
			
		else{
			forward = parseInt(page) + parseInt(1);
			prev = parseInt(page) - parseInt(1);						
		}



		$('.forwardbloglist').attr('name',forward);
		$('.prevbloglist').attr('name',prev);

		
		if (parseInt(page)+ parseInt(1) > 5){
			 paginglink = paginglink + '<li>...</li>';
		}
		
		for(x=1;x<=msg.total_page;x++){
			if(x == parseInt(page) + parseInt(1)){
				paginglink = paginglink + '<li><a href="" name="'+x+'">'+x+'</a></li>';
			}
			else{
				pagetempb = parseInt(page) + parseInt(1) + parseInt(5);
				pagetempf = parseInt(page) + parseInt(1) - parseInt(5);
				
				if(x < pagetempb && x > pagetempf){	
					paginglink = paginglink + '<li><a href="" name="'+x+'">'+x+'</a></li>';
				}
			}
		}
		
		if (parseInt(page) + parseInt(1) < parseInt(msg.total_page) - parseInt(4)){
			 paginglink = paginglink + '<li><a >...</a></li>';
		}
		
		paginglink = paginglink + '</ul>';
		$('div#numcountbloglist').html(paginglink);
	    return msg;
	 }
	}).responseText);
}

/* file ini digunakan berhubungan dengan ajax request */
function loadtransactioncount(domain_id){
	/*var url = 'http://www.kaffah.biz/req/gettransactionalldomain'
	return JSON.parse($.ajax({
	     type: 'POST',
	     url: url,
	     dataType: 'json',
		 data : {domain_id:domain_id},
	     global: false,
	     async:false,
	     success: function(data) {
	     	$.each(data, function(index, element) {
	     		if(element.total_transaction){		
	     			if(sortirwebsite == 'cart'){
	     				valimage = parseInt($('#bloglist tbody tr td em#image_id_'+element.blog_id).html());	
	     				$('#bloglist tbody tr td em#image_id_'+element.blog_id).html(valimage / element.total_transaction);	

	     				valproduct = parseInt($('#bloglist tbody tr td em#product_id_'+element.blog_id).html());	
	     				$('#bloglist tbody tr td em#product_id_'+element.blog_id).html(valproduct / element.total_transaction);	 

	     				valarticle = parseInt($('#bloglist tbody tr td em#article_id_'+element.blog_id).html());	
	     				$('#bloglist tbody tr td em#article_id_'+element.blog_id).html(valarticle / element.total_transaction);		

	     				valcomment = parseInt($('#bloglist tbody tr td em#comment_id_'+element.blog_id).html());	
	     				$('#bloglist tbody tr td em#comment_id_'+element.blog_id).html(valcomment / element.total_transaction);		     				     				    		
	     					
	     			}     					     		
		     		
		     		$('#bloglist tbody tr td em#transaction_id_'+element.blog_id).html(element.total_transaction);
	     		}
	     	});
	        return data;
	     }
	 }).responseText);
*/
}

var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();

function formatString(string){
	return string.replace(/_/g, " ");
}

function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.")
}
