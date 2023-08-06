var editor, datareq, datacontent, html = '';
var datas, dataReceived = false;
var blogid = $('#hideblogid').val();
var id = $('#articleid').val();

$(document).ready(function() {
	$('#blog_id').val(blogid);
	$('#article_id').val(id);
	datacontent = getarticle('http://www.kaffah.biz/reqpost/editarticle/post/'+id+'/'+blogid);		
	datareq = {post:datacontent};
	closearticle(blogid);
	addnewcat();
	initCat();
	loadingcategory(blogid, datacontent,datareq);
	getparent(blogid);
	articleatr(blogid);
	submitarticle();
	savearticle(blogid);
	createeditor(datacontent);
});


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

function closearticle(blogid){
	$('a.close').click(function(eve){
		eve.preventDefault();
		$('div.popup').slideUp('fast');
		$('div.popup').css('width','640px').css('height','auto').css('margin-top','5%');
		$('div.blackbg').fadeOut('fast');
	});

	$(document).on('click','a#confirmno', function(eve){	
		eve.preventDefault();
		$('a.close').click();
	});

	$(document).on('click','a#confirmyes', function(eve){	
		eve.preventDefault();
		$(this).attr('disabled','disabled');
		window.location = '/site/member/'+blogid+'/#article';			
	});
		
	$('#tutupblog').click(function(eve){
		eve.preventDefault();
		$('div.popup').css('width','640px').css('height','auto').css('margin-top','5%').slideDown('fast');
		$('div.wrappop').html('<h2 class="headpop">Konfirmasi Tutup Halaman Artikel</h2><p>Apakah Anda akan menutup halaman ini ?</p><a id="confirmyes" class="confirmbox">Ya</a><a id="confirmno" class="confirmbox">Tidak</a> ');
		$('div.blackbg').fadeIn('fast');
		
	});
}

function savearticle(blogid){
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
					window.location = '/site/member/'+blogid+'/#article';
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
					
					$('p.warning').html(msg.warning_category);	
									
				}				
			},
		});	
	});
}

function submitarticle(blogid){
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
			 	$('div.blackbg').show();	
				$('div.loadupload').show();
			 },
			success: function(msg){		
				if(msg.success == 'TRUE'){	
					$('div.loadupload').hide();
					$('div.wrappop').html('<h2 class="headpop">Konfirmasi Update Artikel</h2><p>Terima kasih, artikel <strong>sudah selesai</strong> di update ... </p><a id="confirmno" class="confirmbox">Oke</a>');					
					$('div.popup').css('width','640px').css('height','auto').css('margin-top','5%');
					$('div.popup').slideDown('fast');						
					$('#addblogyes').html('Update Artikel Ini!');
					$('#simpanblog').html('Simpan Sebagai Draft');
				
	
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
					
					$('p.warning').html(msg.warning_category);	
									
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
	$.ajax('http://www.kaffah.biz/reqpost/getallcatli/category_article/'+blogid, {
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
				$('#addblogyes').html('Terbitkan Artikel Ini!');
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

function articleatr(blogid){
		
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
