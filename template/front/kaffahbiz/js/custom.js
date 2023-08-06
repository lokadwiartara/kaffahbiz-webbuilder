$(document).ready(function(){
		/* registration */

		/* cek email */
		$('#emailajax').keyup(function(){
			  clearTimeout($.data(this, 'timer'));
			  var wait = setTimeout(checkemail, 500);
			  $(this).data('timer', wait);	
		});

		function checkemail(){
			var email = $('#emailajax').val();
			$.ajax({
				url: "/signup/email_exist/",
				type: "POST",
				dataType: "html",
				data: {email : email},	
				beforeSend: function(){
				},
				
				success: function(html){
					if(html == 'TRUE'){
						$('em.emailwarn').addClass('warning').text('Mohon gunakan email yang lain');
						$('#emailstats').val('FALSE');
						allinput_check();
					}
					else if(html == 'UNVALID'){
						$('em.emailwarn').addClass('warning').text('Maaf, email tidak valid.');
						$('#emailstats').val('FALSE');
						allinput_check();
					}
					else{
						$('em.emailwarn').removeClass('warning').text('Email sudah benar');
						$('#emailstats').val('');
						allinput_check();
					}

					/* */

				}
			});			
		}


		/* cek username */
		$('#usernameajax').keyup(function(){
			  clearTimeout($.data(this, 'timer'));
			  var wait = setTimeout(checkusername, 500);
			  $(this).data('timer', wait);	
		});


		function checkusername(){
			var username = $('#usernameajax').val();
			$.ajax({
				url: "/signup/username_exist/",
				type: "POST",
				dataType: "html",
				data: {username : username},
				beforeSend: function(){
				},
				
				success: function(html){
					if(html == 'TRUE'){
						$('em.usernamewarn').addClass('warning').text('Mohon gunakan username lain');
						$('#usernamestats').val('FALSE');
						allinput_check();
					}
					else if(html == 'UNVALID'){
						$('em.usernamewarn').addClass('warning').text('Maaf, username tidak valid.');
						$('#usernamestats').val('FALSE');		
						allinput_check();				
					}
					else{
						$('em.usernamewarn').removeClass('warning').text('Username tersedia');
						$('#usernamestats').val('');
						allinput_check();
					}
				}
			});			
		}

		/* cek password */
		$('#passwordajax').keyup(function(){
			  clearTimeout($.data(this, 'timer'));
			  var wait = setTimeout(checkpassword, 500);
			  $(this).data('timer', wait);	
		});


		function checkpassword(){
			var password = $('#passwordajax').val();
			$.ajax({
				url: "/signup/password_length/",
				type: "POST",
				dataType: "html",
				data: {password : password},
				beforeSend: function(){
				},
				
				success: function(html){
					if(html == 'TRUE'){
						$('em.passwarn').removeClass('warning').text('Password tersimpan');
						$('#passwordstats').val('');
						allinput_check();
					}
					else{
						$('em.passwarn').addClass('warning').text('Password kurang dari 6 digit');
						$('#passwordstats').val('FALSE');
						allinput_check();
					}
				}
			});			
		}

		/* cek domain */
		$('#domainajax').keyup(function(){
			  clearTimeout($.data(this, 'timer'));
			  var wait = setTimeout(checkdomain, 500);
			  var tld = $('em.selecteddom').text().split(' ');
			  $(this).data('timer', wait);	
			  /*$('#domname').val($('#domainajax').val());*/


			  $('#domname').text($('#domainajax').val()+tld[0]);

			  // alert($('#domainajax').val());
		});


		function checkdomain(){
			var domain = $('#domainajax').val();
			var tld = $('em.selecteddom').text().split(' ');

			$.ajax({
				url: "/signup/domain_exist/",
				type: "POST",
				dataType: "html",
				data: {domain : domain, tld : tld[0]},
				beforeSend: function(){

				},
				
				success: function(html){
					/* jika tidak tersedia */
					if(html == 0){
						$('em.domainwarn').addClass('warning').text('Maaf domain tidak tersedia');
						$('#domainstats').val('FALSE');
						allinput_check();	
					}

					/* jika tersedia */
					else if(html == 1){
						$('em.domainwarn').removeClass('warning').text('Domain tersedia');
						$('#domainstats').val('');	
						
						allinput_check();				
					}

					/* selain dari pada itu */
					else{

					}
				}
			});			
		}		



		function allinput_check(){
			/* $('#submitweb').removeClass('disableinput');	 */
			var domainstats = $('#domainstats').val();
			var passwordstats = $('#passwordstats').val();
			var usernamestats = $('#usernamestats').val();
			var emailstats = $('#emailstats').val();

			if(domainstats != 'FALSE' && passwordstats != 'FALSE' && usernamestats != 'FALSE' && emailstats != 'FALSE'){
				$('#submitweb').removeClass('disableinput').removeAttr("disabled");
			}
			else{
				$('#submitweb').addClass('disableinput').attr("disabled", "disabled");
			}
		}

		/* domain */
		$('em.selecteddom').click(function(){
			$('ul.listdom').slideToggle('fast');
		});


		$('ul.listdom li').click(function(){
			var litext = $(this).text();
			$('em.selecteddom').text(litext);
			
			/* pengembangan untuk domainnya */ 
			clearTimeout($.data(this, 'timer'));
			var wait = setTimeout(checkdomain, 100);
			var tld = $('em.selecteddom').text().split(' ');
			$(this).data('timer', wait);
			
			$('#domname').text($('#domainajax').val()+tld[0]);
			$('ul.listdom').slideUp('fast');

			allinput_check();	
		});		


		/* handling form default action */
		$('#regnew').submit(function(eve){
			eve.preventDefault();
		});

		/* submitweb */
		$('#submitweb').click(function(){
			$(this).attr('disabled','disabled').addClass('disableinput');

			var tld = $('em.selecteddom').text().split(' ');
			var domainajax = $('#domainajax').val();
			var passwordajax = $('#passwordajax').val();
			var usernameajax = $('#usernameajax').val();
			var emailajax = $('#emailajax').val();			

			$.ajax({
				url: "/signup/finish_reg/",
				type: "POST",
				dataType: "html",
				data: {
					tld : tld[0], domain : domainajax, 
					password : passwordajax, 
					username : usernameajax, 
					email : emailajax
				},
				beforeSend: function(){

				},
				
				success: function(html){
					if(html == 1){
						$('div.pop_up div.wrapform').hide('fast');
						$('div.pop_up h2').text('Terima Kasih. Selamat Bergabung.');
						$('div.pop_up p').html('Kami telah mengirim email berisi informasi mengenai detil selanjutnya, <b>silahkan cek email Anda</b>. <br />Terima kasih telah melakukan pendaftaran website di <b>kaffah.biz</b> (Jika tidak ada di inbox, mohon lihat dibagian bulk/spam).Jika masih tidak masuk silahkan meminta invoice manual ke <b>billing@kaffahbiz.co.id</b> atau <b>0857 1958 0912</b>');
					}
					
					else if(html == 'FAILED'){
						window.location.href = 'http://www.kaffah.biz';
					}
				}
			});	

		});


		/* **************************************************** */


		/* filter for text anmiation */
		
		var filterList = {
		
			init: function () {
			
				// MixItUp plugin
				// http://mixitup.io
				$('#portfoliolist').mixitup({
					targetSelector: '.portfolio',
					filterSelector: '.filter',
					effects: ['fade'],
					easing: 'snap',
					// call the hover effect
					onMixEnd: filterList.hoverEffect()
				});				
			
			},
			
			hoverEffect: function () {
			
				// Simple parallax effect
				$('#portfoliolist .portfolio').hover(
					function () {
						$(this).find('.label').stop().animate({bottom: 0}, 200, 'easeOutQuad');
						$(this).find('img').stop().animate({top: -30}, 500, 'easeOutQuad');				
					},
					function () {
						$(this).find('.label').stop().animate({bottom: -40}, 200, 'easeInQuad');
						$(this).find('img').stop().animate({top: 0}, 300, 'easeOutQuad');								
					}		
				);				
			
			}

		};
		
		// Run the show!
		filterList.init();


	
})
