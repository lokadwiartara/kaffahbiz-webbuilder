var editor, datareq, datacontent, html = '';
var datas, dataReceived = false;
var blogid = $('#hideblogid').val();
var pagename = $('#pagename').val();

$(document).ready(function() {
	$('#blog_id').val(blogid);
	pagename = $('#pagename').val();
	$('#page_name').val(pagename);
	datacontent = getpage('http://www.kaffah.biz/reqpost/editpage/page/'+pagename+'/'+blogid);		
	createeditor(datacontent);	
	pageatr(blogid);
	getparent(blogid,datacontent);
	submitpage(blogid);
	savepage(blogid);
	closepage(blogid)
});




function getpage(url) {
 return JSON.parse($.ajax({
     type: 'GET',
     url: url,
     dataType: 'json',
     global: false,
     async:false,
     success: function(datacontent) {

		var timezone = datacontent.post_date.split(' ');
		var comment = datacontent.comment_status;
		var notif = datacontent.notif_status;
		var date,time ='';
		var status = datacontent.post_status;
		var datareq = {post:datacontent};
		var meta = jsonserialjson('http://www.kaffah.biz/reqpost/convertjson',datareq);

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
			$('#addblogyes').html('Terbitkan Halaman Ini!');
			$('#simpanblog').html('Simpan Kembali!');
		}
		else{
			
		}
		
	 	$('#titlearticle').val(datacontent.post_title);
		$('#editor1').text(datacontent.post_content);
        return datacontent;
     }
 }).responseText);
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

function jsonserialjson(url,datareq){
	var jsonreq = getjson(url,datareq);
	return jsonreq;
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

function getparent(blogid,datacontent){
	/* menambahkan isi select */
	var parenting ;
	var childing ;
	var topofthem ;
	var separate = '';
		
	$('#formaddcat #parentpage option').remove();
	$('#formaddcat #parentpage').append('<option value="">-- Induk Halaman --</option>');
	
	$.getJSON('http://www.kaffah.biz/reqpost/getallpage/page/'+blogid, { get_param: 'value' }, function(data) {
		$.each(data, function(index, element) {
			 if(element.post_parent == 0){
			 	parenting = element.UD;
				topofthem = 0;
				separate = '';
			 }
			 else{
			 	if(topofthem == 0 ){
					parenting = element.post_parent;
					if(parenting == element.post_parent){
						separate = '--';
						topofthem = element.ID; 
					}
				}
				
				else if(element.post_parent == topofthem){
					separate = separate + '--' ;
					topofthem = element.ID; 
				}
				
				else{
					separate = '--' ;
					topofthem = element.ID; 
				}
				
			 }
			
			 $('#formaddcat #parentpage').append('<option value="'+ element.ID+'">' + separate + element.post_title +'</option>');
			 $('#formaddcat #parentpage option[value='+datacontent.post_parent+']').attr('selected','selected');
		});
	});		
}

function pageatr(blogid){
		
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

function closepage(blogid){
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
		window.location = '/site/member/'+blogid+'/#page';			
	});
		
	$('#tutupblog').click(function(eve){
		eve.preventDefault();
		$('div.popup').css('width','640px').css('height','auto').css('margin-top','9%').slideDown('fast');
		$('div.wrappop').html('<h2 class="headpop">Konfirmasi Tutup Halaman</h2><p>Apakah Anda akan menutup halaman ini ?</p><a id="confirmyes" class="confirmbox">Ya</a><a id="confirmno" class="confirmbox">Tidak</a> ');
		$('div.blackbg').fadeIn('fast');
		
	});
}

function savepage(blogid){
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
					window.location = '/site/member/'+blogid+'/#page';
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

function submitpage(blogid){
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
				 	$('div.blackbg').hide();	
					$('div.loadupload').hide();
					$('div.popup').css('width','640px').css('height','auto').css('margin-top','5%');
					$('div.wrappop').html('<h2 class="headpop">Konfirmasi Update Halaman</h2><p>Terima kasih, Halaman <strong>sudah selesai</strong> di update ... </p><a id="confirmno" class="confirmbox">Oke</a>');
					$('div.blackbg').fadeIn('fast');
					$('div.popup').slideDown('fast');						
					$('#addblogyes').html('Update Halaman Ini!');
					$('#simpanblog').html('Simpan Sebagai Draft');
					window.location = '/site/member/'+blogid+'/full/'+msg.page+'/#editpage';
	
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
