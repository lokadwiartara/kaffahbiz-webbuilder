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
		$('div.popup').css('width','60%').css('height','70%').css('margin-top','5%');
		$('div.popup').slideUp('fast');
		$('div.blackbg').fadeOut('fast');
	});	
	


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



(function($) {
    $.strRemove = function(theTarget, theString) {
        return $("<div/>").append(
            $(theTarget, theString).remove().end()
        ).html();
    };
})(jQuery);

