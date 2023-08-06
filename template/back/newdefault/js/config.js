$(document).ready(function(){
	var blogid = $('#hideblogid').val();
	var web_address = 'http://'+window.location.hostname ;	

	
	savegeneralsetting(blogid);
	getgeneralsetting(blogid, web_address);
});

function savegeneralsetting(blogid){
	$('#addblog').click(function(eve){
		eve.preventDefault();
		$('#config_general').submit();
	});	

	$('#config_general').submit(function(eve){
		eve.preventDefault();
		/* ketika tombol addblog di klik */
		$.ajax('http://www.kaffah.biz/req/savesettingconfigumum/'+blogid, {
			dataType : 'json',
			type : 'post',
			data: $('#config_general :input').serialize(),
			beforeSend: function(){		
				$('div.popup').hide();		 	
			 	$('div.blackbg').show();	
				$('div.loadupload').show();

			},
			success: function(msg){
				if(msg.success == true){
					$('div.loadupload').hide();	
					$('div.popup').css('height','auto').css('width','640px').css('margin-top','5%');
					$('div.wrappop').html('<h2 class="headpop">Konfirmasi Konfigurasi Umum</h2><p>Konfigurasi telah sukses disimpan.</p><a id="confirmtplno" class="confirmbox">Ok!</a> ');	
					$('div.popup').show();						
				}

				else{
					$('div.loadupload').hide();	
					$('div.blackbg').hide();
				}
	
			},
		});
			
	});

	$(document).on('click','a#confirmtplno', function(eve){	
		eve.preventDefault();
		$('a.close').click();

	});	

	$('a.close').click(function(eve){
		eve.preventDefault();
		$('div.popup').slideUp('fast');
		$('div.blackbg').fadeOut('fast');
	});		
}

function getgeneralsetting(blogid, web_address){
	$.ajax('http://www.kaffah.biz/req/getsettingconfigumum/'+blogid, {
		dataType : 'json',
		type : 'post',
		beforeSend: function(){
			$('div.popup').hide();		 	
		 	$('div.blackbg').show();	
			$('div.loadupload').show();	
		
		},
		success: function(data){
			for(var key in data){
				$('#'+key).val(data[key]);
			}	

			$('div.loadupload').hide();	
			$('div.blackbg').hide();	
		}
	});		
}

