$(document).ready(function(){
	var web_address = 'http://'+window.location.hostname ;
	var hash = window.location.hash;	
	var blogid = $('#hideblogid').val();
	var id = $('#articleid').val();		

	/* ketika hash yang di akses itu kosong */
	if(hash==''){
		window.location.href = web_address + '/site/member/#web';
	}
	
	else{
		$.ajax({			
			
			url: web_address+'/site/'+$.param.fragment(),
			type : 'POST',
			 beforeSend: function(){
				$('#bodywrap').html('');
			 	$('div.blackbg').show();	
				$('div.loadupload').show();

				 },
	  		success: function(text) {  			  			
				$("a[href$="+hash+"]").parent('#sidebarmenu li').addClass('currenta').children('#sidebarmenu li a').siblings('ul.sub').slideDown(140);
				$("a[href$="+hash+"]").parent('#sidebarmenu li').siblings().removeClass('currenta');
				
				$('div.blackbg').hide();	
				$('div.loadupload').hide();
				html = $.parseHTML( text );
				title = $(html).filter('h1.headnote').html();
				titler = title.replace(/(<.*?>)/ig," ").replace("   ", " >");
				
				document.title = 'Kaffah Member Area > ' + titler;
				$('h2.current').html(title);
				$('#bodywrap').html(text);

				popupinit(blogid);
			}
		});
		
		$(window).hashchange( function(){
			var myObj = $.param.fragment();	
			
			$.ajax({
				url: web_address+'/site/'+$.param.fragment(),
				type : 'POST',
				 beforeSend: function(){
				 	$('div.blackbg').show();	
					$('div.loadupload').show();
				 },
		  		success: function(text) {	  				  			
					// $("a[href$="+hash+"]").parent('#sidebarmenu li').addClass('currenta').children('#sidebarmenu li a').siblings('ul.sub').slideDown(140).parent('#sidebarmenu li').siblings().removeClass('currenta');
					$("a[href$="+hash+"]").parent('#sidebarmenu li').addClass('currenta').children('#sidebarmenu li a').siblings('ul.sub').slideDown(140);
					$("a[href$="+hash+"]").parent('#sidebarmenu li').siblings().removeClass('currenta');				

					$('div.blackbg').hide();	
					$('div.loadupload').hide();
					html = $.parseHTML( text );
					
					title = $(html).filter('h1').html();
					titler = title.replace(/(<.*?>)/ig," ").replace("   ", " >");
					
					document.title = 'Kaffah Member Area > ' + titler;
					$('h2.current').html(title);
					$('#bodywrap').html(text);
					popupinit(blogid);
				}
			});
			
		  });		
	}
	
	
	/* ketika menu di klik */
	$('#sidebarmenu li a').click(function(eve){		
		$('#sidebarmenu li a').removeClass('actvmenu');
		$(this).addClass('actvmenu');		
		$(this).parent('#sidebarmenu li').addClass('currenta').children('#sidebarmenu li a').siblings('ul.sub').slideDown(140);
		$(this).parent('#sidebarmenu li').siblings().removeClass('currenta').children('#sidebarmenu li a').siblings('ul.sub').slideUp(80);


	});


	$('a.sabtn').click(function(eve){
		eve.preventDefault();
		$(this).toggleClass('btnhover')
		$('ul.samenu').toggle(0);
	});



	$('ul.samenu li a').click(function(eve){
		$(this).parents('ul').fadeOut(0);
		$('a.sabtn').removeClass('btnhover');
	});	

	$('a.close').click(function(eve){
		eve.preventDefault();				
		$('div.popup').slideUp('fast');
		$('div.blackbg').fadeOut('fast');
		$(this).die('click');
	});	

	

	$('nav a').click(function(eve){		
		if($(this).attr('class') == 'topa'){
			eve.preventDefault();
			$(this).siblings('.subcodemenu').slideToggle();
			$(this).parent('.nav-item').siblings().children('ul.subcodemenu').slideUp();
			$('.h_right nav').click();
		}
		else{
			$(this).parent('.nav-item').siblings().children('ul.subcodemenu').slideUp();
			$('.h_right nav-mobile-open').click();
			
		}
		$(this).die('click');	
	});



	getcity(blogid);
	submitverifikasi();
	uploadimage(blogid);
	delimage(blogid);

});



function attr(){
	/* atribut href */
	$('a.article').attr('href','/site/member/'+blogid+'/#article');

	/* pembaharuan di sini ... */
	if(hash == '#newarticle' || hash == '#newproduct' || hash == '#newpage' ){
		window.location.href = web_address + '/site/member/'+blogid+'/full/'+hash;
	}
	else if(hash == '#editarticle' ||  hash == '#editproduct' ||  hash == '#editpage'){
		window.location.href = web_address + '/site/member/'+blogid+'/full/'+id+'/'+hash;
	}

	var linkkaffdir = web_address+'/dir';
	var direct = 'Direktori';
	var kaffdir = 'Kaffah.biz';

	$('.linkkaffdir').text(linkkaffdir);
	$('.direct').text(direct);
	$('.kaffdir').text(kaffdir);
}

function statuslog(){
	$.getJSON('http://www.kaffah.biz/req/status_login', { get_param: 'value' }, function(data) {
		if(data.logged_in != true){
			window.location = 'http://www.kaffah.biz/req/login';
		}	
	});
}

function popupinit(blogid){
	$('div.loadupload').hide(0);
	$('div.blackbg').hide('fast');
	$('div.popup').hide('fast');	
}

(function($) {
    $.strRemove = function(theTarget, theString) {
        return $("<div/>").append(
            $(theTarget, theString).remove().end()
        ).html();
    };
})(jQuery);


function submitverifikasi(){
	$(document).on('submit','#formverifikasi', function(eve){
		eve.preventDefault();	
		$.ajax('http://www.kaffah.biz/req/popupsave/', {
			dataType : 'json',
			type : 'POST',		
			data: $('#formverifikasi :input').serialize(),
			beforeSend: function(){
				$('div.popup').hide();
			 	$('div.blackbg').show();	
				$('div.loadupload').show();
			},
			 
			success: function(data){
				if(data.success == true){
					
					$('a.close').click();
				}
				else{

					$('a.close').click();
				}
			}
		});		
	});


	$(document).on('click','#savesettingakun', function(eve){	
		eve.preventDefault();	
		$.ajax('http://www.kaffah.biz/req/popupsave/', {
			dataType : 'json',
			type : 'POST',		
			data: $('#formverifikasi :input').serialize(),
			beforeSend: function(){
				$('div.popup').hide();
			 	$('div.blackbg').show();	
				$('div.loadupload').show();
			},
			 
			success: function(data){
				if(data.success == true){
					$('div.loadupload').hide();	
					$('div.popup').css('height','auto').css('width','640px').css('margin-top','5%');
					$('div.wrappop').html('<h2 class="headpop">Konfirmasi Akun dan Verifikasi</h2><p>Informasi Akun dan Data Verifikasi telah sukses disimpan.</p><a id="confirmtplno" class="confirmbox">Ok!</a> ');	
					$('div.popup').show();						
				}

				else{
					$('div.loadupload').hide();	
					$('div.blackbg').hide();
				}
			}
		});		
	});

	$(document).on('click','a#confirmtplno', function(eve){	
		eve.preventDefault();
		$('a.close').click();

	});	
}

function getcity(blogid){
	$(document).on('change','#provinsi', function(){	
		var provinsi = $(this).val();		
		
		/* get ajax from provinsi yang di klik */
		$.ajax('http://www.kaffah.biz/req/getcity/'+blogid, {
			dataType : 'json',
			type : 'post',
			data : {provinsi: provinsi},
			beforeSend: function(){
			},
			success: function(data){
				/* remove provinsi kota form */
				$('#provinsi_kota').children().remove();
				
				/* get data */
				for(var key in data){
					$('#provinsi_kota').append('<option value="'+key+'">'+data[key]+'</option>');
				}	
			}
		});	
	
	});	
}

function uploadimage(blogid){
/*
	$(document).on('click', 'input.triggchange_ktp', function(eve){
		eve.preventDefault();				
		$('#img_ktp').click();
	});
	
	$(document).on('change', 'input.imagefilektp', function(eve){		
		$('#change_ktp').val($(this).val()).attr('disabled', 'disabled').addClass('disabledtext');
	});
	
	$(document).on('click','input.triggclick_ktp', function(eve){
		eve.preventDefault();
		var name = 'img_ktp';				
		alert('Mohon merefresh halaman ini, jika gagal upload atau terlalu lama loading...');
		$.ajaxFileUpload({
			url         	: '/reqpost/upload', 
			secureuri      : true,
			fileElementId  : name,
			dataType    	: 'json',
			data        	: {'blogid' : blogid },
			success: function (data){
				if(data.success == 'TRUE'){
					$('#label_ktp').after('<a href="'+data.url+'" class="uploadimg" target="_blank" id="link_ktp">'+data.url.substring(7,50)+'...</a> <a href="" class="delimg" id="del_ktp">- Hapus</a>');
					$('#img_ktp').remove();
					$('#change_ktp').remove();
					$('#click_ktp').remove();
					$('#hide_ktp').val(data.url);
				}
			}
		}); 		
	
	});

	$(document).on('click', 'input.triggchange_id', function(eve){
		eve.preventDefault();		
		$('#img_id').click();
	});
	
	$(document).on('change', 'input.imagefileid', function(eve){		
		$('#change_id').val($(this).val()).attr('disabled', 'disabled').addClass('disabledtext');
	});
	
	$(document).on('click','input.triggclick_id', function(eve){
		eve.preventDefault();
		var name = 'img_id';		
		alert('Mohon merefresh halaman ini, jika gagal upload atau terlalu lama loading...');
		$.ajaxFileUpload({
			url         	: '/reqpost/upload', 
			secureuri      : true,
			fileElementId  : name,
			dataType    	: 'json',
			data        	: {'blogid' : blogid },
			success: function (data){
				if(data.success == 'TRUE'){
					$('#label_id').after('<a href="'+data.url+'" class="uploadimg" target="_blank" id="link_id">'+data.url.substring(7,50)+'...</a> <a href="" class="delimg" id="del_id">- Hapus</a>');
					$('#img_id').remove();
					$('#change_id').remove();
					$('#click_id').remove();
					$('#hide_id').val(data.url);
				}
			}
		}); 		
	
	});	
*/
}

function delimage(blogid){
	$(document).on('click', 'a#del_img_ktp', function(eve){
		eve.preventDefault();
		$('#link_img_ktp').remove();
		$('#img_ktp').remove();
		$('#hide_img_ktp').remove();		
		
		$('#label_img_ktp').after('<input type="file" class="imagefile imagefilektp" name="userfile" id="img_ktp" />'+
								'<input type="hidden" id="hide_img_ktp" name="img_ktp" />');
		$(this).remove();

		new AjaxUpload('img_ktp', {
			action: '/reqpost/upload',
			name: 'userfile',
			responseType: "json",
			data: {'tag' : 'img_ktp'},
			onSubmit: function(file, extension) {
				alert('Mohon menunggu, jika terlalu lama loading silahkan refresh halaman ini lalu upload kembali...');
				$('div.blackbg').show();
				$('div.loadupload').show();	
				$('div.popup').hide();							
			},
			onComplete: function(file, response) {
				
				$('div.loadupload').hide();	
						if($('#notpopupverify').val() == 0){
							$('div.popup').show();
						}
						else{
							$('div.blackbg').hide();
						}
				

				if(response.success == 'TRUE'){	
					$('#img_ktp').remove();
					$('br.del_img_ktp').remove();	
					$('#label_img_ktp').after('<a href="'+response.url+'" class="uploadimg" target="_blank" id="link_img_ktp">'+response.url.substring(7,50)+'...</a> <a href="" class="delimg" id="del_img_ktp">- Hapus</a><br class="del_img_ktp" />');
					
					$('#hide_img_ktp').val(response.url);
												

				}						
			}
		});	

	});

	$(document).on('click', 'a#del_img_id', function(eve){
		eve.preventDefault();
		$('#link_img_id').remove();
		$('#img_id').remove();
		$('#hide_img_id').remove();		
		
		$('#label_img_id').after('<input type="file" class="imagefile imagefileid" name="userfile" id="img_id" />'+
								'<input type="hidden" id="hide_img_id" name="img_id" />');
		$(this).remove();

		new AjaxUpload('img_id', {
			action: '/reqpost/upload',
			name: 'userfile',
			responseType: "json",
			data: {'tag' : 'img_id'},
			onSubmit: function(file, extension) {
				alert('Mohon menunggu, jika terlalu lama loading silahkan refresh halaman ini lalu upload kembali...');
				$('div.blackbg').show();
				$('div.loadupload').show();	
				$('div.popup').hide();							
			},
			onComplete: function(file, response) {
				
				$('div.loadupload').hide();	
						if($('#notpopupverify').val() == 0){
							$('div.popup').show();
						}
						else{
							$('div.blackbg').hide();
						}
																			

				if(response.success == 'TRUE'){	
					$('#img_id').remove();
					$('br.del_img_id').remove();	
					$('#label_img_id').after('<a href="'+response.url+'" class="uploadimg" target="_blank" id="link_img_id">'+response.url.substring(7,50)+'...</a> <a href="" class="delimg" id="del_img_id">- Hapus</a><br class="del_img_id" />');
					
					$('#hide_img_id').val(response.url);
												

				}						
			}
		});			
	});	
}

function checkCounter(){
	if (sessionStorage.clickc >= 1){
		return false;
	}
	else{
		return true;
	}
}

function clickCounter(){
	if(typeof(Storage)!=="undefined"){
		if (sessionStorage.clickc){
			sessionStorage.clickc=Number(sessionStorage.clickc)+1;
			
		}
		else{
			sessionStorage.clickc=1;
		}
	}
	else {
	  
	}
}

