$(document).ready(function() {
	var blogid = $('#hideblogid').val();
	$('#blog_id').val(blogid);
		
	CKEDITOR.replace( 'editor1', {
		uiColor: '#fafafa',		
		allowedContent: true,
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
	});
	
	
	
	closearticle(blogid);
	initCat();
	getparent(blogid);
	articleatr(blogid);
	submitarticle(blogid);
	savearticle();
});


function closearticle(blogid){
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
		$('div.wrappop').html('<h2 class="headpop">Konfirmasi Tutup Halaman Artikel</h2><p>Apakah Anda akan menutup halaman ini ?</p><a id="confirmyes" class="confirmbox">Ya</a><a id="confirmno" class="confirmbox">Tidak</a> ');
		$('div.blackbg').fadeIn('fast');
		
	});
}

function savearticle(){
	var web_address = 'http://'+window.location.hostname ;
	
	$('#simpanblog').click(function(eve){
		eve.preventDefault();
		
		$('p.warning').slideUp('fast');
		$('#status').val('draft');
		
		$.ajax('http://www.kaffah.biz/reqpost/addnewarticle', {
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

					if(msg.limit == 'TRUE'){
						$('p.warning').html('Mohon maaf, Paket Anda dibatasi, silahkan Upgrade Paket');	
					}
					else{
						$('p.warning').html(msg.warning_category);	
					}					
									
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
		$.ajax('http://www.kaffah.biz/reqpost/addnewarticle', {
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
				 	$('div.blackbg').show();	
					$('div.loadupload').show();
					if((msg.warning_category != '') || (msg.warning_titlearticle != '')){
						$('p.warning').html('').slideDown(100);	
					}
					else{
						$('p.warning').slideUp('fast');
					}

					if(msg.limit == 'TRUE'){
						$('p.warning').html('Mohon maaf, Paket Anda dibatasi, silahkan Upgrade Paket');	
					}
					else{
						$('p.warning').html(msg.warning_category);	
					}
					
					
									
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
			 
			 // alert(element.name);
			 $('#formaddcat #parentpage').append('<option value="'+ element.ID+'">' + separate + element.post_title +'</option>');
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
	

}

