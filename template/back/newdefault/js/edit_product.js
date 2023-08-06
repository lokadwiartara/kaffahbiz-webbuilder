var editor, datareq, datacontent, html = '';
var datas, dataReceived = false;
var blogid = $('#hideblogid').val();
var id = $('#articleid').val();

$(document).ready(function() {

	$('#blog_id').val(blogid);
	$('#article_id').val(id);
	datacontent = getarticle('http://www.kaffah.biz/reqpost/editarticle/product/'+id+'/'+blogid);		
	datareq = {post:datacontent};
	closeproduct(blogid);
	addnewcat();
	initCat();
	loadingcategory(blogid, datacontent,datareq);
	getparent(blogid);
	productatr(blogid);
	submitproduct(blogid);
	saveproduct(blogid);
	createeditor(datacontent);
	uploadimgproduct(blogid);
	$('#product_price,#harga_modal,#komisi_reseller,#komisi_ks,#product_price_old').priceFormat({prefix: '',centsSeparator: '',thousandsSeparator: '.', centsLimit: 0});

	new AjaxUpload('massuploadedit', {
		action: '/reqpost/upload',
		name: 'userfile',
		responseType: "json",
		data: {'blogid' : $('#hideblogid').val(),  'type' : 'product'},
		onSubmit: function(file, extension) {
			alert('Mohon menunggu, jika terlalu lama loading silahkan refresh halaman ini, atau simpan terlebih dahulu lalu upload kembali...');
			$('div.blackbg').show();
			$('div.loadupload').show();			

		},
		onComplete: function(file, response) {
			$('div.blackbg').hide();
			$('div.loadupload').hide();
			
			if(response.success == 'TRUE'){		

				var isimgcover_exist = $('.imgsingle a.coverimg').length;
				if(isimgcover_exist > 0){

					$('.imguploadwrap')
						.append('<div class="imgsingle"><img src="'+response.img+'" />'+
								'<input type="hidden" name="img[]" class="imglist" value="'+response.img.replace('_100x100','')+'" />'+
								'<div class="wrapbtn"><a class="delimgproduct" title="Hapus Gambar"></a>'+
								'<a class="setcoverimg" title="Jadikan Gambar Utama"></a>'+
								'</div></div>');						
				}
				else{

					$('.imguploadwrap')
						.append('<div class="imgsingle"><img src="'+response.img+'" />'+
								'<input type="hidden" name="postimage" class="postimage" value="'+response.img.replace('_100x100','')+'" />'+
								'<div class="wrapbtn"><a class="delimgproduct" name="coverimgdel" title="Hapus Gambar"></a>'+
								'<a class="coverimg" title="Gambar Utama"></a>'+
								'</div></div>');						
				}

			}							
		}
	});	

});

function uploadimgproduct(blogid){
	
	/* $('.btnuploadimg').click(function(eve){
		eve.preventDefault();
		$('#massupload').click();		
	});
	ketika tombol file di klik
	$(document).on('change', '#massupload', function(eve){
		eve.preventDefault();
		$('#formuploadfileproduct').submit();
		//$('#formuploadfileproduct').ajaxForm({target: '#imguploadwrap' }).submit();
	}); */
	
	$('.btnuploadimg').hover(function(eve){
		eve.preventDefault();
		$(this).removeAttr('href');
	}, function(eve){
		$(this).attr('href','#');
	});
	
	
	/* ketika setcoverimg di klik */
	$(document).on('click','a.setcoverimg', function(event){
		event.preventDefault();
		fs = $(this).parents('div.imgsingle');
		fsimage =  fs.children('img').attr('src').replace("_100x100", "");
		valueimg = fs.children('input.imglist').val();
		fs.children('.imglist').remove();
		fs.children('div.wrapbtn').children('a.setcoverimg').remove();
		fs.children('div.wrapbtn').children('a.delimgproduct').attr('name','coverimgdel');
		fs.children('div.wrapbtn').append('<a class="coverimg" title="Gambar Utama"></a>');
		fs.children('div.wrapbtn').append('<input type="hidden" name="postimage" class="postimage" value="'+valueimg.replace('_100x100','')+'" />');
		fs.siblings().children('div.wrapbtn').children('a.delimgproduct').removeAttr('name');
		fs.siblings().children('div.wrapbtn').children('input.postimage').remove();
		fs.siblings().children('div.wrapbtn').children('a.coverimg').remove();
		fs.siblings().children('div.wrapbtn').children('a.setcoverimg').remove();		
		fs.siblings().children('input.postimage').attr('name', 'img[]').attr('class','imglist'); 
		fs.siblings().children('div.wrapbtn').append('<a class="setcoverimg" title="Jadikan Gambar Utama"></a>');
	});
	
	/* ketika tombol delete button di klik maka */
	$(document).on('click', 'div.imgsingle div.wrapbtn a.delimgproduct', function(event){
		event.preventDefault();
		/* 	jika yang diklik ini adalah coverimage maka silahkan 
			yang lainnya langsung di aktifkan sebagai */
			
		var isimgcover_exist = $('.imgsingle a.coverimg').length;
		var iscoverimgdel = $(this).attr('name');
		$(this).parents('div.imgsingle').remove();
		
		/* belum selesai untuk new product media yang diupload */
		if(iscoverimgdel == 'coverimgdel'){
			fs = $('div.imgsingle').first(); 
			valueimg = fs.children('input.imglist').val();
			fs.children('.imglist').remove();
			fs.children('div.wrapbtn').children('a.setcoverimg').remove();
			fs.children('div.wrapbtn').children('a.delimgproduct').attr('name','coverimgdel');
			fs.children('div.wrapbtn').append('<a class="coverimg" title="Gambar Utama"></a>');
			fs.children('div.wrapbtn').append('<input type="hidden" name="postimage" class="postimage" value="'+valueimg+'" />');
		}
	});
	
	/*$('#formuploadfileproductedit').submit(function(eve){
		eve.preventDefault();
		var web_address = 'http://'+window.location.hostname ;
		$('div.blackbg').show();	
		$('div.loadupload').css('background-position', 'center 70%').css('padding', '10px').css('width', '27%').css('height','auto').html('<p style="margin-top:20px; margin-bottom:30px ; font-size:13px;">mohon merefresh halaman ini, jika terlalu lama loading</p>').show();
		$.ajaxFileUpload({
			url         	: 'http://www.kaffah.biz/reqpost/upload', 
			secureuri      : true,
			fileElementId  : 'massuploadedit',
			dataType    	: 'json',
			data        	: {'blogid' : $('#hideblogid').val(),  'type' : 'product'},
			success: function(data){
				$('div.blackbg').hide();
				$('div.loadupload').hide();
				
				if(data.success == 'TRUE'){									

					var isimgcover_exist = $('.imgsingle a.coverimg').length;
					if(isimgcover_exist > 0){
						$('.imguploadwrap')
							.append('<div class="imgsingle"><img src="'+data.img+'" />'+
									'<input type="hidden" name="img[]" class="imglist" value="'+data.img_original.replace('_100x100','')+'" />'+
									'<div class="wrapbtn"><a class="delimgproduct" title="Hapus Gambar"></a>'+
									'<a class="setcoverimg" title="Jadikan Gambar Utama"></a>'+
									'</div></div>');						
					}
					else{
						$('.imguploadwrap')
							.append('<div class="imgsingle"><img src="'+data.img+'" />'+
									'<input type="hidden" name="postimage" class="postimage" value="'+data.img_original.replace('_100x100','')+'" />'+
									'<div class="wrapbtn"><a class="delimgproduct" name="coverimgdel" title="Hapus Gambar"></a>'+
									'<a class="coverimg" title="Gambar Utama"></a>'+
									'</div></div>');						
					}

				}
			}
			
		});
		
		return false;
	}); */
}


function jsonserialjson(url,datareq){
	var jsonreq = getjson(url,datareq);
	return jsonreq;
}

function saveeditor() {
	if ( !editor )
		return;

	return editor.getData();
}

function createeditor(html){
	if ( editor )
				return;
	editor = CKEDITOR.appendTo( 'editorwrap', 
	{
		bodyId: 'editor1',
		uiColor: '#fafafa', 
		height: '800px',
		toolbar: [
			'/',
			{ name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source' ] },
			{ name: 'tools', items: [ 'Maximize' ] },
			{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Strike', '-' ] },
			{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] },
			{ name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: ['Undo', 'Redo' ] },
			{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
			{ name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'SpecialChar' ] },
			{ name: 'styles', items: [ 'Styles', 'Format' ] }
			
		]
	
	}, html.post_content );
}

function getarticle(url) {
 return JSON.parse($.ajax({
     type: 'GET',
     url: url,
     dataType: 'json',
     global: false,
     async:false,
     success: function(data) {
	 	$('#titlearticle').val(data.post_title);
		$('#editor1').text(data.post_content);
        return data;
     }
 }).responseText);
}

function getjson(url,datareq) {
 return JSON.parse($.ajax({
     type: 'POST',
     url: url,
     dataType: 'json',
	 data : datareq,
     global: false,
     async:false,
     success: function(data) {
        return data;
     }
 }).responseText);
}

function closeproduct(blogid){
	$('a.close').click(function(eve){
		eve.preventDefault();
		$('div.popup').slideUp('fast');
		$('div.blackbg').fadeOut('fast');
	});

	$(document).on('click','a#confirmno', function(eve){	
		eve.preventDefault();
		$('a.close').click();
	});

	$(document).on('click','a#confirmyes', function(eve){	
		eve.preventDefault();
		$(this).attr('disabled','disabled');
		window.location = '/site/member/'+blogid+'/#product';			
	});
		
	$('#tutupblog').click(function(eve){
		eve.preventDefault();
		$('div.popup').css('width','640px').css('height','auto').css('margin-top','9%').slideDown('fast');
		$('div.wrappop').html('<h2 class="headpop">Konfirmasi Tutup Halaman Produk</h2><p>Apakah Anda akan menutup halaman ini ?</p><a id="confirmyes" class="confirmbox">Ya</a><a id="confirmno" class="confirmbox">Tidak</a> ');
		$('div.blackbg').fadeIn('fast');	
	});
}

function saveproduct(blogid){
	var web_address = 'http://'+window.location.hostname ;	

	$('#simpanblog').click(function(eve){
		eve.preventDefault();
		
		$('p.warning').slideUp('fast');
		$('#status').val('draft');
		
		$.ajax('http://www.kaffah.biz/reqpost/updatearticle', {
			dataType : 'json',
			type : 'POST',
			data: $('div.newarticle :input').serialize(),
			beforeSend: function(){
			 	$('div.blackbg').show();	
				$('div.loadupload').show();

			 },
			success: function(msg){
			
				if(msg.success == 'TRUE'){
					window.location = '/site/member/'+blogid+'/#product';
				}
				else{
					$('div.blackbg').hide();	
					$('div.loadupload').hide();
					if((msg.warning_category != '') || (msg.warning_titlearticle != '')){
						$('p.warning').html('').slideDown(100);	
					}
					else{
						$('p.warning').slideUp('fast');
					}
					
					if( msg.warning_image != ''){
						$('p.warning').html('').slideDown(100);	
					}
					else{
						$('p.warning').slideUp('fast');
					}
					
					
					
					var alertvar = msg.warning_category + msg.warning_image;
					
					
					$('p.warning').html(alertvar.replace('undefined', ''));		
					
					$(this).die('click');							
				}				
			},
		});	
		
		
	});
}

function submitproduct(blogid){
	var web_address = 'http://'+window.location.hostname ;
	
	$('#addblogyes').click(function(eve){
		eve.preventDefault();

		var value = CKEDITOR.instances['editor1'].getData();

		$('#editor1').text(value);
		$('p.warning').slideUp('fast');
		$('#status').val('publish');

		$.ajax('http://www.kaffah.biz/reqpost/updatearticle', {
			dataType : 'json',
			type : 'POST',
			data: $('div.newarticle :input').serialize(),
			beforeSend: function(){
			 	$('div.loadupload').show();
			 },
			success: function(msg){		
				if(msg.success == 'TRUE'){
					
					/*$('div.loadupload').hide();
					$('div.wrappop').html('<h2 class="headpop">Konfirmasi Update Produk</h2><p>Terima kasih, Produk <strong>sudah selesai</strong> di update ... </p><a id="confirmno" class="confirmbox">Oke</a>');
					$('div.blackbg').fadeIn('fast');
					$('div.popup').css('width','640px').css('height','auto').css('margin-top','5%');
					$('div.popup').slideDown('fast');						
					$('#addblogyes').html('Update Produk Ini!');
					$('#simpanblog').html('Simpan Sebagai Draft');*/
					window.location = '/site/member/'+blogid+'/#product';
				
	
				}
				else{
					$('div.loadupload').hide();
					if((msg.warning_category != '') || (msg.warning_titlearticle != '')){
						$('p.warning').html('').slideDown(100);	
					}
					else{
						$('p.warning').slideUp('fast');
					}
					
					if( msg.warning_image != ''){
						$('p.warning').html('').slideDown(100);	
					}
					else{
						$('p.warning').slideUp('fast');
					}
					
					var alertvar = msg.warning_category + msg.warning_image;
					
					$('p.warning').html(alertvar.replace('undefined', ''));	
									
					$(this).die('click');
				}
			},
		});	
		
		
	});
}

function initCat(){

	$(document).on('keyup','#formaddcat', function(){
	/* lakukan pengecekan menggunakan ajax */
		
		$.ajax('http://www.kaffah.biz/reqpost/cat_init', {
			dataType : 'json',
			type : 'POST',
			data: $('#formaddcat').serialize(),

			success: function(msg){
				
				if(msg.cat_exist == 1){
					$('#addnewbtn').attr('disabled', 'disable').addClass('disable');
				}
				else{
					$('#addnewbtn').removeAttr('disabled').removeClass('disable');
				}
			},
		});

	});		
}

function loadingcategory(blogid,datacontent,datareq){
	/* dapatkan semua kategori */
	$.ajax('http://www.kaffah.biz/reqpost/getallcatli/category_product/'+blogid, {
		dataType : 'html',
		type : 'POST',
		success: function(msg){			
			$('.catlistli ul').html(msg);
			var slug = datacontent.post_category.split(',');
			var timezone = datacontent.post_date.split(' ');
			var comment = datacontent.comment_status;
			var notif = datacontent.notif_status;
			var date,time ='';
			var status = datacontent.post_status;
			var meta = jsonserialjson('http://www.kaffah.biz/reqpost/convertjson',datareq);

				
			
			/* for category choice checked */
			for(x=0;x<slug.length;x++){
				$('.checkcat[value='+slug[x]+']').prop('checked', true);	
			}
			
			
			/* this is for product - ini digunakan pada bagian produk */
			$('#product_code').val(datacontent.post_code);
			$('#product_price').val(formatNumber(datacontent.post_price));
			$('#product_price_old').val(formatNumber(datacontent.post_price_old));
			$('#product_stock').val(datacontent.post_stock);

			if(datacontent.post_reseller == 'yes'){
				$('#product_reseller').prop('checked', true);
			}

			$('#harga_modal').val(formatNumber(datacontent.post_basic));
			$('#komisi_reseller').val(formatNumber(datacontent.post_reseller_fee));
			$('#komisi_ks').val(formatNumber(datacontent.post_ks_fee));

									

			$('.imguploadwrap').append('<div class="imgsingle"><img src="'+datacontent.post_image+'" />'+
								'<input type="hidden" name="postimage" class="postimage" value="'+datacontent.post_image+'" />'+
								'<div class="wrapbtn"><a class="delimgproduct" name="coverimgdel" title="Hapus Gambar"></a>'+
								'<a class="coverimg" title="Gambar Utama"></a>'+
								'</div></div>');
			
			/* untuk meta product di letakkan di sini */
			$('#berat').val(meta.berat);
			$('#satuanberat option[value='+meta.satuanberat+']').attr('selected','selected');
			$('#tinggi').val(meta.tinggi);
			$('#satuantinggi option[value='+meta.satuantinggi+']').attr('selected','selected');
			$('#lebar').val(meta.lebar);
			$('#satuanlebar option[value='+meta.satuanlebar+']').attr('selected','selected');
			$('#panjang').val(meta.panjang);
			$('#satuanpanjang option[value='+meta.satuanpanjang+']').attr('selected','selected');
			
			/* ini untuk meta product bagian warna dan ukuran */
			$('#product_colour').val(meta.warna);
			$('#product_size').val(meta.ukuran);
			
			/* ini digunakan untuk meta image nantinya */
			for(var i=0;i<meta.img.length;i++){
				$('.imguploadwrap').append('<div class="imgsingle"><img src="'+meta.img[i]+'" />'+
					'<input type="hidden" name="img[]" class="imglist" value="'+meta.img[i]+'" />'+
					'<div class="wrapbtn"><a class="delimgproduct" title="Hapus Gambar"></a>'+
					'<a class="setcoverimg" title="Jadikan Gambar Utama"></a>'+
					'</div></div>');	
			}
			
			/ * date and time */
			date = timezone[0].split('-');
			time = timezone[1].split(':');
			
			/* for date */
			$('.timewrapshort[name=date]').val(date[2]);
			$('.timewrapselect option[value='+date[1]+']').attr('selected','selected');
			$('.timewrapshort[name=year]').val(date[0]);
			
			/* for time */
			$('.timewrapshort[name=hour]').val(time[0]);
			$('.timewrapshort[name=minute]').val(time[1]);
			
			/* for seo */
			$('input[name=metatitle]').val(meta.title);
			$('textarea[name=metakeyword]').text(meta.metakeyword);
			$('textarea[name=metadescription]').text(meta.metadescription);
			
			/* for public discussion */
			if(notif == 'on') $('input[name=notification]').attr('checked','checked');
			if(comment == 'on') $('input[name=comment]').attr('checked','checked');
			
			/* post status */
			if(status == 'draft'){
				$('#addblogyes').html('Terbitkan Produk Ini!');
				$('#simpanblog').html('Simpan Kembali!');
			}
			else{
				
			}	
			
				
		},
	});		
}

function listPos(param,blogid){
	$.post('http://www.kaffah.biz/reqpost/ajax_arr_litem', {
		 postdata: param, blogid: blogid,
	}, function(html){
	});		
}

function getparent(blogid){
	/* menambahkan isi select */
	var parenting ;
	var childing ;
	var topofthem ;
	var separate = '';
		
	$('#formaddcat #parentcat option').remove();
	$('#formaddcat #parentcat').append('<option value="">-- Induk Kategori --</option>');
	
	$.getJSON('http://www.kaffah.biz/reqpost/getallcat_except/category_article/'+blogid, { get_param: 'value' }, function(data) {
		$.each(data, function(index, element) {
			 if(element.parent == 0){
			 	parenting = element.term_id;
				topofthem = 0;
				separate = '';
			 }
			 else{
			 	if(topofthem == 0 ){
					parenting = element.parent;
					if(parenting == element.parent){
						separate = '--';
						topofthem = element.term_id; 
					}
				}
				
				else if(element.parent == topofthem){
					separate = separate + '--' ;
					topofthem = element.term_id; 
				}
				
				else{
					separate = '--' ;
					topofthem = element.term_id; 
				}
				
			 }
			 			 
			 $('#formaddcat #parentcat').append('<option value="'+ element.term_id +'">' + separate + element.name +'</option>');
		});
	});		
}

function productatr(blogid){	
		
	$('#contenthtml').click(function(){
		$(this).hide();
	});
	
	$('#settingbar h3.setting').click(function(){
		$(this).siblings().slideToggle(60);
		$(this).toggleClass('orange90');
	});
	
	$('a.newcat').click(function(eve){
		eve.preventDefault();
		$('div.formnewcatwrap').slideToggle(50);
		
	});
	
	$('.headwrap').click(function(){
		var classs = $(this).attr('class');
		var atr = classs.split(' ');
		$(this).css('border-bottom','1px solid #F4F4F4').css('background-color','#F4F4F4').siblings().css('background-color','#fff').css('border-bottom','1px solid #efefef');
		$('div.'+atr[0]).css('background','#F4F4F4').slideToggle(60).children('div.wrappset').css('background','#F4F4F4').slideDown(60);
		$('div.'+atr[0]).siblings('div.wraptop').css('background','#fff').slideUp(60);
	});
	
	$('#addblogyes, #tutupblog, #simpanblog').hover(function(eve){
		eve.preventDefault();
		$(this).removeAttr('href');
	});	


}

function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.")
}

function addnewcat(){
	var blogid = $('#hideblogid').val();
	/* ketika formnya di submit */
	$('div.newcat div.formnewcatwrap #formaddcat').css('border', '2px solid red !important;');
	$(document).on('submit','#formaddcat', function(eve){
		eve.preventDefault();
		$('#addnewbtn').attr('disabled', 'disable').addClass('disable');
		$.ajax('http://www.kaffah.biz/reqpost/addnewcat', {
			dataType : 'json',
			type : 'POST',
			data: $('#formaddcat').serialize(),
			success: function(element){	
				
				if(element.response == 'SUCCESS'){	
					loadingcategory(blogid);
					$(':input','div.newcat div.formnewcatwrap #formaddcat')
						.not(':button, :submit, :reset, :hidden').val('')
							.removeAttr('checked').removeAttr('selected');
							
					$('#formaddcat #parentcat option').remove();
					$('#formaddcat #parentcat').append('<option value="">-- Induk Kategori --</option>');
					listPos($('ul.category').text().replace(/(\r\n|\n|\r)/gm,""),$('#hideblogid').val());
					getparent(blogid);
					
				}
				else{
					$('#addnewbtn').attr('disabled', 'disable').addClass('disable');
				}
								
			},
		});
			
	});		
}	
