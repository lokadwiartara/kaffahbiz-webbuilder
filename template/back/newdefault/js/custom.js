	$('document').ready(function(){

		var hash = window.location.hash;	
		
		$('.coremenu li a.topa').click(function(eve){
			eve.preventDefault();

			var linkcore = $(this).attr('href');						

			if(linkcore == '#dashboard'){
				$(this).parent('.coremenu li').siblings().children('a').removeClass('actvmenu').siblings().slideUp(100).removeClass('actvmenu').children('li').children('a').removeClass('actvmenu');
				$(this).addClass('actvmenu');
			}
			else{
				$(this).siblings('ul.subcodemenu').children('li').children('a').removeClass('actvmenu');
				$(this).addClass('actvmenu').siblings('ul').slideDown(100);
				$(this).parent('.coremenu li').siblings().children('a').removeClass('actvmenu').siblings().slideUp(100).removeClass('actvmenu').children('li').children('a').removeClass('actvmenu');

			}

			$(this).parent('.coremenu li').parent('ul').siblings('ul').children('li').children('ul').slideUp(300);
			$(this).parent('.coremenu li').parent('ul').siblings('ul').children('li').children('a').removeClass('actvmenu');

			window.location = linkcore;
		});

		$('.coremenu li ul li a').click(function(eve){
			eve.preventDefault();
			var linkcore = $(this).attr('href');			

			$(this).addClass('actvmenu').parent('li').siblings().children('a').removeClass('actvmenu');
			window.location = linkcore;
		});


		$('#accountmenu a#accounta').click(function(eve){
			eve.preventDefault();
			$('div.accountmenu').toggle(10);
			$(this).children('em').toggleClass('bottomnone');

		});


		$('#accountmenu ul.user li a').click(function(eve){			
			$('div.accountmenu').slideUp(10);
			$('a#accounta em').removeClass('bottomnone');
			return true;
		});
	});