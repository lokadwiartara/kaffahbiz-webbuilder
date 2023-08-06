
$(document).ready(function(){
	popupinit();
	
);

function popupinit(){
	$.ajax('/req/popupinit/', {
		dataType : 'json',
		type : 'POST',		
		beforeSend: function(){
			$('div.popup').hide();
		 	$('div.blackbg').show();	
			$('div.loadupload').show();
		},
		 
		success: function(data){
			if(data.popup == 'verify'){
			alert('tes')''								
				$('div.popup').css('width','60%').css('height','70%').css('margin-top','5%');
				$('div.wrappop').html('<h2 class="headpop"></h2><div class="overflowform">'+
				'<form id="formorder_edit" method="post">'+
				'</form></div><br />');

				$('div.loadupload').hide();
				$('div.blackbg').fadeIn(0);
				$('div.popup').slideDown(0);								
			}	
			else if(data.popup == 'admin_notif'){

			}
		}
	});


	$('a.close').click(function(eve){
		eve.preventDefault();
		alert('tes');
		$('div.popup').slideUp('fast');
		$('div.blackbg').fadeOut('fast');
	});
}