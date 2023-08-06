$(document).ready(function(){
	/* variable init */
	var status = null ;
	var limit = $('#limit').val();
	var page = 1;
	var delsinglearticle = null;	
	var textsearch = null;
	var filtercategory = null;

	loadproduct('product',status,limit,page); 
	actionproduct('product',limit,page);
	linkinit('product',status,limit,page);
	searching(status,limit,page);
	btnproduct('product',status,limit,page);
});

function btnproduct(type,status,limit,page){
	$('div.postsort').hover(function(){
		$(this).children('.postsortproduct').show();
	}, function(){
		$(this).children('.postsortproduct').hide();
	});

	$('div.postfilter').hover(function(){
		$(this).children('.postfilterproduct').show();
	}, function(){
		$(this).children('.postfilterproduct').hide();
	});

	$(document).on('click','#productsort ul li a', function(eve){
		eve.preventDefault();
		$('em.sortproduct').html($(this).html());
		var valuea = $(this).attr('name');
		sortirproduct = $('#sortproduct').val(valuea);
		$('#search').val('');
		$('#filtercategory').val('');
		$('em.filterproduct').html('Semua Kategori');
		loadproduct('product',status,limit,page);
		$('.postsortproduct').hide();
		$(this).die('click');
	});

	/* ketika category filternya di klik */
	$(document).on('click','#productfilter ul li a.catfilter', function(eve){
	//$('.postfilterproduct ul li a.catfilter').click(function(eve){
		eve.preventDefault();
		var valuea = $(this).attr('name');
		filtercategory = $('#filtercategory').val(valuea);
		loadproduct('product',status,limit,page);
		$('em.filterproduct').html($(this).html());
		$('em.sortproduct').html('Semua Produk');
		$('#search').val('');
		$('#sortproduct').val('');
		$('.postfilterproduct').hide();
		$(this).die('click');
		return false;
	});


	$(document).on('click', 'a.editproduct', function(eve){
		eve.preventDefault();		
		var title = $(this).attr('title');
		var id = $(this).attr('name');
		var selectmarket = '';

		product = getproductdetail('/req/editarticlesa/product/'+id);		
		market = getmarketcategory(null);

		$.each(market, function(index, element) {				
			selectmarket = selectmarket + '<option value="'+element.market_name+'">'+element.market_title+'</option>';
		});			

		$('div.popup').css('width','60%').css('height','79%').css('margin-top','4%');
		$('div.wrappop').html(
						'<h2 class="headpop">Aksi Cepat Pengeditan "<b>'+title+'</b>" </h2>'+
						'<div class="overflowform">'+
						'<form id="form_update_product" method="post" accept-charset="utf-8">'+
						'<label for="produk">Nama Produk</label><input type="text" class="input_text long_text" name="produk" id="produk" value="'+product.post_title+'"> <br>'+
						'<label for="produk">Harga / Stok</label><input type="text" class="input_text sort_text price" name="harga" id="harga" value="'+formatNumber(product.post_price)+'"> /  <input type="text" class="input_text sort_text price" name="stok" id="stok" value="'+product.post_stock+'"><br>'+												
						'<label for="domain">Website</label><input type="text" class="input_text long_text disable" name="domain" disabled="disabled" id="domain" value="'+product.domain_name+'"><br>'+												
						'<label for="status">Status</label>'+
						'<select name="status" id="status" class="action edit">'+
						'<option value="">Pilih Status</option>'+
						'<option value="publish">Publish</option>'+
						'<option value="draft">Draft</option>'+												
						'<option value="warn">Warning</option>'+
						'</select>'+

						'<label for="kategori_market">Kategori Market</label>'+
						'<select name="kategori_market" id="kategori_market" class="action edit">'+
						'<option value="">Pilih Kategori</option>'+
						selectmarket+
						'</select>'+
						'<p class="note">Untuk menambahkan kategori sub lain pilih terlebih dahulu yang ini </p>'+
						'<label for="kategori_market_sub">Sub Kategori</label>'+
						'<select name="kategori_market_sub" id="kategori_market_sub" class="action edit">'+
						'<option value="">Pilih Sub Kategori</option>'+
						'</select>'+
						'<p class="note">Jika tidak ada pilihan di sub kategori Anda bisa membuat di bag. sub kategori lain</p>'+
						'<label for="kategori_market_sub_other">Sub Kategori Lain</label><input type="text" class="input_text long_text" name="kategori_market_sub_other" id="kategori_market_sub_other" value="">'+
						'<p class="note">Anda bisa menambahkan kategori sub lain (anak dari kategori market yg dipilih) </p>'+
						'<br>'+
						'<label for="atribut">Atribut Market</label><input type="checkbox" name="pilihan" id="pilihan" /><label for="pilihan" class="insideinput">Pilihan Tim Kaffah</label> <input type="checkbox" name="hot" id="hot" /><label for="hot" class="insideinput">Paling Diinginkan (HOT)</label><br />'+
						'<label for="moderasi">Moderasi</label><input type="checkbox" name="modeyes" id="modeyes" /><label for="modeyes" class="insideinput">Sudah Dimoderasi</label> <br />'+
						'<input type="hidden" name="id" value="'+product.ID+'">'+
						'<input type="submit" value="Update Product!" class="btnAct addSubmit">'+
						'</form></div>');


		$('#kategori_market option[value="'+product.post_market_category+'"]').prop('selected', true);	
		$('#status option[value="'+product.post_status+'"]').prop('selected', true);	

		if(product.post_moderation != 0){
			$('#modeyes').attr('checked', true);
		}

		if(product.post_market_category != ''){
			submarket = getmarketcategory(product.post_market_category);			
			if(submarket != null){
				$('#kategori_market_sub option').remove();
				$('#kategori_market_sub').append('<option value="">Pilih Sub Kategori</option>');			
				$.each(submarket, function(index, element) {
					$('#kategori_market_sub').append('<option value="'+element.market_name+'">'+element.market_title+'</option>');									
				});				

				$('#kategori_market_sub option[value="'+product.post_market_sub_cat+'"]').prop('selected', true);	
			}
			else{
				$('#kategori_market_sub option').remove();
				$('#kategori_market_sub').append('<option value="">Pilih Sub Kategori</option>');	
			}

			submarket = '';
		}		


		var attribute = product.post_market_attribute.split(',');
		if(product.post_market_attribute != ''){

			if(attribute.length > 1){
				if(attribute[0] == 'choice'){
					$('#pilihan').attr('checked', true);
				}
				
				if(attribute[1] == 'hot'){
					$('#hot').attr('checked', true);
				}
			}
			else{
				if(attribute == 'choice'){
					$('#pilihan').attr('checked', true);
				}
				else if(attribute == 'hot'){
					$('#hot').attr('checked', true);
				}
			}
		}		

		$('div.blackbg').fadeIn(100);
		$('div.popup').slideDown(0);		

		$(this).die('click');
	});
	
	$(document).on('change','#kategori_market', function(eve){
		var value = $(this).val();
		submarket = getmarketcategory(value);
		if(submarket != null){
			$('#kategori_market_sub option').remove();
			$('#kategori_market_sub').append('<option value="">Pilih Sub Kategori</option>');			
			$.each(submarket, function(index, element) {
				$('#kategori_market_sub').append('<option value="'+element.market_name+'">'+element.market_title+'</option>');				
				
			});				
		}
		else{
			$('#kategori_market_sub option').remove();
			$('#kategori_market_sub').append('<option value="">Pilih Sub Kategori</option>');	
		}

		submarket = null;
		$(this).die('change');
	});

	$(document).on('click', 'a.delproduct', function(eve){
		eve.preventDefault();		
	});	

	$(document).on('mouseover','#productlist tbody tr', function(eve){	
		eve.preventDefault();
		$(this).children('td').children('div.edit').show();
	});
	
	$(document).on('mouseleave','#productlist tbody tr', function(eve){	
		eve.preventDefault();
		$(this).children('td').children('div.edit').hide();
	});

	$(document).on('submit', '#form_update_product', function(eve){
		eve.preventDefault();
		var web_address = 'http://'+window.location.hostname ;		
		$.ajax('/req/updatearticlesa', {
			dataType : 'json',
			type : 'POST',
			data: $('#form_update_product :input').serialize(),
			beforeSend: function(){
				$('div.popup').hide();
			 	$('div.blackbg').show();	
				$('div.loadupload').show();					
			},
			 
			success: function(msg){
				if(msg.success == true){
					var page = parseInt($('#currpage').html());
					loadproduct('product',status,limit,page); 
					$('a.close').click();
				}
			},
		});

		$(this).die('submit');
	});
	
	$('a.close').click(function(eve){
		eve.preventDefault();
		$('div.popup').slideUp('fast');
		$('div.blackbg').fadeOut('fast');
		$('div.loadupload').fadeOut('fast');	
	});	
}

function loadproduct(type,status,limit,page){
	var type = 'product';
	var web_address = 'http://'+window.location.hostname ;
 	$('div.blackbg').show();	
	$('div.loadupload').show();	
	$('div.popup').hide();	

	var x = 0;
	var url = null;

	var pageinit = paginginit('product',status,page);	

	/* if page access is empty */
	if(page != '') page = page - 1;		
	else page = 0;	
	
	var offset = page * pageinit.per_page;
	
	/* preparing for textsearch and category */
	textsearch = $('#search').val();	
	filtercategory = $('#filtercategory').val();
	sortproduct = $('#sortproduct').val();

	/* ketika textsearch dan kategori tidak kosong */
	if(textsearch != null && textsearch != ''){
		url = '/req/getallarticlesa/product/'+status+'/'+limit+'/'+offset+'/s/'+textsearch;
	}

	else if(filtercategory != null && filtercategory != ''){
		url = '/req/getallarticlesa/product/'+status+'/'+limit+'/'+offset+'/f/'+filtercategory;
	}

	else if(sortproduct != null && sortproduct != ''){
		if(sortproduct == 'warn'){
			status = 'warn';
			url = '/req/getallarticlesa/product/'+status+'/'+limit+'/'+offset+'/sort/'+sortproduct;	
		}
		else{
			url = '/req/getallarticlesa/product/'+status+'/'+limit+'/'+offset+'/sort/'+sortproduct;
		}
		
	}

	else{
		url = '/req/getallarticlesa/product/'+status+'/'+limit+'/'+offset;	
	}	

	$.ajax(url, {
		dataType : 'json',
		type : 'POST',
		data: {},
		beforeSend: function(){
	     	$('div.blackbg').show();	
			$('div.loadupload').show();					 	
		},
		 
		success: function(data){			
			$('#productlist tbody tr').remove();
		
			$.each(data, function(index, element) {	
				var datetime = element.post_date.split(' ');
				var date = datetime[0].split('-');
				var fixdate = date[2]+'/'+date[1]+'/'+date[0];
				var post_status;
				
				odd = x % 2;
				
				if(odd == 1){trclass = 'even';}
				else{trclass = 'odd';}
				
				if(element.post_market_category != '') {class_status="orange"; post_status = '(/DIR: '+formatString(element.post_market_category) +')';}
				else {class_status="red"; post_status = '';}

				if(element.post_moderation == 0){class_moderation="no";text_moderation="BELUM!!!";class_tr="red";}
				else{class_moderation="yes";text_moderation="Sudah";class_tr="";}

				var attribute = element.post_market_attribute.split(',');
				var choiceclass = '';
				var choicetext = '';
				var hotclass = '';
				var hottext = '';

				if(element.post_market_attribute != ''){

					if(attribute.length > 1){
						if(attribute[0] == 'choice'){
							var choiceclass = 'choice';
							var choicetext = 'Pilihan';
						}
						
						if(attribute[1] == 'hot'){
							var hotclass = 'hot';
							var hottext = 'Hot';
						}
					}
					else{
						if(attribute == 'choice'){
							var choiceclass = 'choice';
							var choicetext = 'Pilihan';
						}
						else if(attribute == 'hot'){
							var hotclass = 'hot';
							var hottext = 'Hot';
						}
					}
				}

				if(element.post_status == 'publish'){
					var classstatus = 'publish';
					var textstatus = 'Produk Diterbitkan';
				}

				else if(element.post_status == 'warn') {
					var classstatus = 'warn';
					var textstatus = 'Produk Dipending...';
				}

				else {
					var classstatus = 'pending';
					var textstatus = 'Produk Pending';
				}


				if(element.total_beli == null){
					element.total_beli = 0;
				}

			 	$('#productlist').find('tbody')
					.append($('<tr>').attr('class', trclass).addClass(class_tr)
						.append(
						$('<td>', {text:'tes'}).html('<input type="checkbox" name="postid[]" value="'+element.ID+'" class="checkinput" />')
						, $('<td>').attr('class', 'title productlist').html('<img class="imgontd" title="'+textstatus+'" src="'+element.post_image+
						'" /><em class="'+classstatus+'" title="'+textstatus+'"></em><h2><a title="klik untuk mengedit artikel : '+element.post_title+'" href="/site/member/'
							+element.blog_id+'/full/'+element.ID+'/#editproduct">'+element.post_title+' </a></h2><em class="status '
							+class_status+'">'+ post_status +'</em>'
							
							+'<em class="price">Harga: <b>Rp '+element.post_price.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.")+'</b></em>'
							+'<em class="stock">Stok: <b>'+element.post_stock.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.")+'</b></em>'
							+'<em class="moderation '+class_moderation+'">Moderasi: <b>'+text_moderation+'</b></em>'
							
							+'<div class="edit product"><a href="/site/member/'
							+element.blog_id+'/full/'+element.ID+'/#editproduct" name="'+element.ID+'" title="'+element.post_title+'" class="editproduct" >Edit Produk /DIR</a> </div>')
						//, $('<td>').attr('class', 'author').html('<em class="author">'+element.name+'</em>')
						, $('<td>').html('<em class="'+choiceclass+'">'+choicetext+'</em><em class="'+hotclass+'">'+hottext+'</em>')
						
						, $('<td>').html('<em class="cmt">'+element.comment_count+'</em>')
						, $('<td>').html('<em class="trans" id="trans_"'+element.ID+'>'+element.total_beli+'</em>')
						, $('<td>').html('<em class="see">'+element.post_counter+'</em>')					
						, $('<td>').html('<em class="clock">'+fixdate+'</em>')
						
						)	
					
					);
					
					x++;
			 });
			 	$('div.blackbg').fadeOut('fast');
				$('div.loadupload').hide();							
			},


	});	


 	$('div.blackbg').hide();	
	$('div.loadupload').hide();		
}

function actionproduct(type,limit,page){

	$('#checkall').on('click', function() {
		if($(this).is(":checked") == true){
			$('.checkinput').prop('checked', true);	
		}
		else{
			$('.checkinput').prop('checked', false);
		}
	    
	});

	$(document).on('click', '#postaction ul li', function(eve){
		eve.preventDefault();
		var action = $(this).attr('id');
		var numberOfChecked = $('#tablepost input:checkbox:checked').length;

		if(numberOfChecked > 0){
			/* ketika action yang diklik adalah sebagai berikut */
			$('div.popup').css('width','640px').css('height','auto').css('margin-top','5%');
			
			if(action == 'masspublish'){				
				$('div.wrappop').html('<h2 class="headpop">Konfirmasi Publish Produk</h2><p>Apakah Anda akan mempublish/menerbitkan semua produk yang dipilih?</p><a id="confirm_masspublish_yes" class="confirmbox confirm_yes">Ya</a><a id="confirm_masspublish_no" class="confirmbox confirm_no">Tidak</a> ');
			}

			else if(action == 'massdraft'){
				$('div.wrappop').html('<h2 class="headpop">Konfirmasi Pending Produk</h2><p>Apakah Anda akan menjadikan draft semua produk yang dipilih?</p><a id="confirm_massdraft_yes" class="confirmbox confirm_yes">Ya</a><a id="confirm_massdraft_no" class="confirmbox confirm_no">Tidak</a> ');
			}

			else if(action == 'massundir'){
				$('div.wrappop').html('<h2 class="headpop">Konfirmasi Pencabutan Produk Dari Dir</h2><p>Apakah Anda akan mencabut semua produk yang dipilih dari Direktori Kaffah?</p><a id="confirm_massundir_yes" class="confirmbox confirm_yes">Ya</a><a id="confirm_massundir_no" class="confirmbox confirm_no">Tidak</a> ');
			}

			else if(action == 'massdel'){
				$('div.wrappop').html('<h2 class="headpop">Konfirmasi Hapus Produk</h2><p>Apakah Anda akan menghapus semua produk yang dipilih ?</p><a id="confirm_massdel_yes" class="confirmbox confirm_yes">Ya</a><a id="confirm_massdel_no" class="confirmbox confirm_no">Tidak</a> ');
			}	

			$('div.blackbg').fadeIn('fast');
			$('div.popup').slideDown('fast');
		}

		else{
			$('div.popup').css('width','640px').css('height','auto').css('margin-top','5%');
			$('div.wrappop').html('<h2 class="headpop">Konfirmasi Aksi</h2><p>Mohon maaf, belum ada produk yang Anda pilih, silahkan memilih terlebih dahulu, baru melakukan aksi produk. </p><a class="confirmbox confirm_no">Baik!</a> ');
			$('div.blackbg').fadeIn('fast');
			$('div.popup').slideDown('fast');			
		}
		
	});

	$(document).on('click','a.confirm_yes', function(eve){	
		eve.preventDefault();
		$(this).attr('disabled','disabled');
		var tra_stats = $(this).attr('id');
		var web_address = 'http://'+window.location.hostname ;

		if(tra_stats == 'confirm_masspublish_yes'){
			stats = 'publish';
		}
		
		else if(tra_stats == 'confirm_massdraft_yes'){
			stats = 'draft';
		}
		
		else if(tra_stats == 'confirm_massundir_yes'){
			stats = 'undir';
		}	
		
		else if(tra_stats == 'confirm_massdel_yes'){
			stats = 'delete';
		}		

		else{
			stats = null;
		}

		if(stats != null){
			
			$.ajax('/req/mass_updatearticlesa/'+stats, {
				dataType : 'json',
				type : 'POST',
				data: $('input:checkbox:checked').serialize(),
				beforeSend: function(){
					$('div.popup').hide();
				 	$('div.blackbg').show();	
					$('div.loadupload').show();					 	
				},
				 
				success: function(msg){			
					if(msg.status == true){
						$('#checkall').prop('checked', false);
						var page = parseInt($('#currpage').html());
						loadproduct('product',null,limit,page);
					 	$('div.blackbg').hide();	
						$('div.loadupload').hide();							
						
					}
					else{
					 	$('div.blackbg').hide();	
						$('div.loadupload').hide();	
					}
					
				},
			});		
			
		}

		$(this).die('click');

	});

	$(document).on('click','a.confirm_no', function(eve){	
		eve.preventDefault();
		$('a.close').click();
	});
}

function searching(status,limit,page){
	var web_address = 'http://'+window.location.hostname ;	
	$(document).on('keyup','#search', function(){		
		delay(function(){			
	      loadproduct('product',status,limit,page);
	    }, 800 );	
	});		
}

function getproductdetail(url){
	return JSON.parse($.ajax({
		type: 'POST',
		url: url,
		dataType: 'json',
		global: false,
		async:false,
		success: function(data) {
			return data;
		}
	}).responseText);
}

function getmarketcategory(parent){	
	if(parent == null){		
		return JSON.parse($.ajax({
			type: 'POST',
			url:  '/req/getmarketcategory',
			dataType: 'json',
			global: false,
			async:false,
			success: function(data) {				
				return data;
			}
		}).responseText);				
	}
	else{
		return JSON.parse($.ajax({
			type: 'POST',
			url:  '/req/getmarketcategory/'+parent,
			dataType: 'json',
			global: false,
			async:false,
			success: function(data) {				
				return data;
			}
		}).responseText);			
	}
}

function linkinit(type,status,limit,page){
	/* ini digunakan untuk menjelaskan bahwa page yang di akses di adalah : */
	$(document).on('click','#numcountproduct ul li a, .forwardproduct, .prevproduct', function(eve){
		eve.preventDefault();
		
		var valname = $(this).attr('name');
		
		$.getJSON('/reqpost/pagingarticle/'+valname+'/product', { }, function(data) {
			$('#page').val(data.pagecurrent);
			page = $('#page').val();
			loadproduct(type,status,limit,page); 		
		});	

		$('#numcountproduct ul').hide();
		$(this).die('click');
		
	});
}

function paginginit(type,status,page) {
	var paginglink = null;
	var pagetemp = null;
	var url = null;

	if(page == '') page = 1;	
	
	/* preparing for textsearch and category 	
	filtercategory = $('#filtercategory').val();
	status = $('#statusarticle').val(); */

	textsearch = $('#search').val();
	
	/* ketika textsearch dan kategori tidak kosong */
	if(textsearch != null && textsearch != ''){
		url = '/req/paginginitsa/'+type+'/'+status+'/s/'+textsearch;
	}
	/*else if(filtercategory != null && filtercategory != ''){
		url = '/req/paginginitsa/'+type+'/'+status+'/f/'+filtercategory;
	}*/
	else{
		url = '/req/paginginitsa/'+type+'/'+status;
	}
	
	
	/* ambil paging */
	return JSON.parse($.ajax({
	 type: 'post',
	 url: url,
	 dataType: 'json',
	 global: false,
	 async:false,
	 success: function(msg) {
	 	paginglink = '<ul>';

		$('#currpage').html(page);
		
		if(page == '1' && page == msg.total_page ){
			forward = parseInt(page) ;
			prev = parseInt(page);
		}
		
		else if(page == '1' ){
			forward = parseInt(page) + parseInt(1);
			prev = parseInt(page);
		}
		
		else if(page == msg.total_page){
			forward = parseInt(page);
			prev = parseInt(page) - parseInt(1);			
		}
			
		else{
			forward = parseInt(page) + parseInt(1);
			prev = parseInt(page) - parseInt(1);						
		}

		$('.forwardproduct').attr('name',forward);
		$('.prevproduct').attr('name',prev);

		
		if (parseInt(page)+ parseInt(1) > 5){
			 paginglink = paginglink + '<li>...</li>';
		}
		
		for(x=1;x<=msg.total_page;x++){
			if(x == parseInt(page) + parseInt(1)){
				paginglink = paginglink + '<li><a href="" name="'+x+'">'+x+'</a></li>';
			}
			else{
				pagetempb = parseInt(page) + parseInt(1) + parseInt(5);
				pagetempf = parseInt(page) + parseInt(1) - parseInt(5);
				
				if(x < pagetempb && x > pagetempf){	
					paginglink = paginglink + '<li><a href="" name="'+x+'">'+x+'</a></li>';
				}
			}
		}
		
		if (parseInt(page) + parseInt(1) < parseInt(msg.total_page) - parseInt(4)){
			 paginglink = paginglink + '<li><a >...</a></li>';
		}
		
		paginglink = paginglink + '</ul>';
		$('div#numcountproduct').html(paginglink);
	    return msg;
	 }
	}).responseText);
}

function formatNumber(num) {
   return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.")
}

function formatString(string){
	return string.replace(/_/g, " ");
}

var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();