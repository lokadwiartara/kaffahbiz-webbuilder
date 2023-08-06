$(document).ready(function(){
	/* variable init */
	var status = 'publish' ;
	var reseller = $('#reseller').val();
	var storeid = $('#storeid').val();
	var textsearch = null;
	var filtercategory = null;

	if(reseller == ''){
		window.location.replace("http://www.kaffah.biz/site/member");		
	}

	var limit = $('#limit').val();
	var page = 1;
	var delsinglearticle = null;	
	var textsearch = null;
	var filtercategory = null;
	loadcategory(storeid);
	loadproduct('product',status,storeid,limit,page); 	
	searching('product',status,storeid,limit,page);
	categoryproduct('product',status,storeid,limit,page);
	linkinit('product',status,storeid,limit,page);	
	useonweb();

});

function useonweb(){
	/* link pasang di web */
	$(document).on('click','a.btnset', function(eve){
		eve.preventDefault();
		thisbtn = $(this);
		attridproduct = $(this).attr('id').split('_');
		product_reseller_id = attridproduct[1] ; 

		/* sebelah sini langsung di masukkan ke dalam database produk si rseller */
		/* menginsertkan */
		url = 'http://www.kaffah.biz/reqpost/addproductbyreseller/';
		$.ajax(url, {
			dataType : 'json',
			type : 'POST',
			data: {prodid:product_reseller_id},
			beforeSend: function(){
		     	$('div.blackbg').show();	
				$('div.loadupload').show();					 	
			},		
			success: function(data){
				if(data.success == 'TRUE'){					
					thisbtn.text('Sudah terpasang').removeClass('set').addClass('set1');
					$('div.loadupload').hide();	
					$('div.popup').css('height','auto').css('width','640px').css('margin-top','5%');
					$('div.wrappop').html('<h2 class="headpop">Produk Berhasil Dipasang</h2><p>Produk telah berhasil di pasang pada website Anda, silahkan mencari order</p><a id="confirmtplno" class="confirmbox">Ok!</a> ');	
					$('div.popup').show();	
				}
				else{
					$('div.loadupload').hide();	
					$('div.popup').css('height','auto').css('width','640px').css('margin-top','5%');
					$('div.wrappop').html('<h2 class="headpop">Produk Gagal Dipasang</h2><p>Produk gagal di pasang pada website Anda. Produk ini sudah Ada di website Anda.</p><a id="confirmtplno" class="confirmbox">Ok!</a> ');	
					$('div.popup').show();	
				}
			},

		});

	});

	$('a.close').click(function(eve){
		eve.preventDefault();
		$('div.popup').slideUp('fast');
		$('div.blackbg').fadeOut('fast');
	});		
}

function loadcategory(blogid){
	var parenting ;
	var childing ;
	var topofthem ;
	var separate = '';
	
	$.getJSON('http://www.kaffah.biz/reqpost/getallcat_except_reseller/category_product/'+blogid, { get_param: 'value' }, function(data) {
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
			 
			 // alert(element.name);<li class=""><a href="" name="">Semua Kategori</a></li>
			 $('.postfilterproductr ul').append('<li class=""><a class="catfilter" href="" name="'+ element.slug +'" >' + element.name +'</a></li>');
		});
	});		
}

function searching(type,status,blogid,limit,page){
	var web_address = 'http://'+window.location.hostname ;
	
	$(document).on('keyup','#search', function(){
		delay(function(){
	      loadproduct(type,status,blogid,limit,page);
	    }, 800 );	
	});		
}

function categoryproduct(type,status,blogid,limit,page){
	/*  ketika kategori di hover */
	$('div.postfilter').hover(function(){
		$(this).children('.postfilterproductr').show();
		$(this).children('.postsortproductr').show();
	}, function(){
		$(this).children('.postfilterproductr').hide();
		$(this).children('.postsortproductr').hide();
	});



	/* ketika category filternya di klik */
	$(document).on('click','#productsort ul li a', function(eve){
		eve.preventDefault();
		$('em.sortproduct').html($(this).html());
		var valuea = $(this).attr('name');
		sortirproduct = $('#sortproduct').val(valuea);
		$('#filtercategory').val('')
		$('em.filterproduct').html('Semua Kategori');
		loadproduct(type,status,blogid,limit,page); 
		$('em.sortproduct').html($(this).html());
		$('#search').val('');
		$('.postsortproductr').hide();
		$(this).die('click');
		return false;
	});
	
	/* ketika category filternya di klik */
	$(document).on('click','.postfilterproductr ul li a.catfilter', function(eve){
	//$('.postfilterproduct ul li a.catfilter').click(function(eve){
		eve.preventDefault();
		var valuea = $(this).attr('name');
		filtercategory = $('#filtercategory').val(valuea);
		$('#sortproduct').val('');
		$('em.sortproduct').html('Semua Produk');
		loadproduct(type,status,blogid,limit,page,textsearch,filtercategory); 
		$('em.filterproduct').html($(this).html());
		$('#search').val('');
		
		$('.postfilterproductr').hide();
		$(this).die('click');
		return false;
	});
}

function loadproduct(type,status,storeid,limit,page){
	var type = 'product';
	var web_address = 'http://'+window.location.hostname ;
 	$('div.blackbg').show();	
	$('div.loadupload').show();	
	$('div.popup').hide();	

	
	var productset = getallproduct();	


	if(productset != null){
		var product_set_id = [];
		$.each(productset, function(index, element) {
			product_set_id.push(element.post_parent); 
		});		
	}

	var x = 0;
	var url = null;
	var pageinit = paginginit('product',status,storeid,page);	

	/* if page access is empty */
	if(page != '') page = page - 1;		
	else page = 0;	
	
	/* offset for paging */
	var offset = page * pageinit.per_page;	

	/* preparing for textsearch and category */	
	textsearch = $('#search').val();
	filtercategory = $('#filtercategory').val();	
	sortproduct = $('#sortproduct').val();

	/* ketika textsearch dan kategori tidak kosong */

	/* ketika textsearch dan kategori tidak kosong */
	if(textsearch != null && textsearch != ''){
		url = 'http://www.kaffah.biz/reqpost/getallarticlereseller/product/'+status+'/'+limit+'/'+offset+'/s/'+textsearch;
	}	
	
	else if(filtercategory != null && filtercategory != ''){
		url = 'http://www.kaffah.biz/reqpost/getallarticlereseller/product/'+status+'/'+limit+'/'+offset+'/f/'+filtercategory;
	}

	else if(sortproduct != null && sortproduct != ''){		
		url = 'http://www.kaffah.biz/reqpost/getallarticlereseller/product/'+status+'/'+limit+'/'+offset+'/sort/'+sortproduct;				
	}

	else{
		url = 'http://www.kaffah.biz/reqpost/getallarticlereseller/product/'+status+'/'+limit+'/'+offset;
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
				var product_atr;
				var exist_product =jQuery.inArray( element.ID, product_set_id );

				if(exist_product >= 0){
					btnpasang = '<a target="_blank" id="repro_'+element.ID+'" href="#" class="btnset set1">Sudah terpasang</a>';
				}
				else{
					btnpasang = '<a target="_blank" id="repro_'+element.ID+'" href="#" class="btnset set">Pasang Di Web</a>';
				}
				

				odd = x % 2;
				
				if(odd == 1){trclass = 'even';}
				else{trclass = 'odd';}



				product_atr = '<em class="kode">Kode: <b>'+element.post_code+'</b></em>'
						+'<em class="price">Harga: <b>Rp '+element.post_price.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.")+'</b></em>'
						+'<em class="stock">Stok: <b>'+element.post_stock.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.")+'</b></em>';



				$('#productlist').find('tbody')
					.append($('<tr>').attr('class', trclass)
						.append(							
							$('<td>').attr('class', 'title productlist reseller')
								.html('<img class="imgontd"  src="'+element.post_image+'" />'+
										'<h2><a target="_blank" href="http://store.kaffahbiz.co.id/produk/'+element.post_category+'/'+element.post_name+'" title="Silahkan klik untuk lihat detilnya!">'+element.post_title+'</a></h2>'+product_atr
									)

							, $('<td>').attr('class', 'price_product_reseller').html('<em class="">Rp '+element.post_price.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.")+'</em>')
							, $('<td>').attr('class', 'price_product_reseller green').html('<em class=""><b>Rp '+element.post_reseller_fee.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.")+'</b></em>')
							, $('<td>').attr('class', 'action_product_reseller').html('<a target="_blank" href="http://store.kaffahbiz.co.id/produk/'+element.post_category+'/'+element.post_name+'" class="see_detail">Lihat Detail Produk</a><br />'+btnpasang)
						
						)
					);
				
				x++;
			 	
			});	

			$('div.blackbg').fadeOut('fast');
			$('div.loadupload').hide();						
		}
	});	

 	$('div.blackbg').hide();	
	$('div.loadupload').hide();		
}

function linkinit(type,status,blogid,limit,page){
	/* ini digunakan untuk menjelaskan bahwa page yang di akses di adalah : */
	$(document).on('click','#numcountproductr ul li a, .forwardproductr, .prevproductr', function(eve){
		eve.preventDefault();
		
		var valname = $(this).attr('name');
		$.getJSON('http://www.kaffah.biz/reqpost/pagingarticle/'+valname+'/product', { }, function(data) {
			$('#page').val(data.pagecurrent);
			page = $('#page').val();
			loadproduct(type,status,blogid,limit,page,textsearch,filtercategory); 		
		});	
		$('#numcountproductr ul').hide();
		$(this).die('click');
		
	});
}

function paginginit(type,status,blogid,page) {
	var paginglink = null;
	var pagetemp = null;
	var url = null;	

	if(page == '') page = 1;	
	
	/* preparing for textsearch and category */
	textsearch = $('#search').val();
	filtercategory = $('#filtercategory').val();	
	
	/* ketika textsearch dan kategori tidak kosong */
	if(textsearch != null && textsearch != ''){
		url = 'http://www.kaffah.biz/reqpost/paginginitreseller/'+type+'/'+status+'/'+blogid+'/s/'+textsearch;
	}
	else if(filtercategory != null && filtercategory != ''){
		url = 'http://www.kaffah.biz/reqpost/paginginitreseller/'+type+'/'+status+'/'+blogid+'/f/'+filtercategory;
	}
	else{
		url = 'http://www.kaffah.biz/reqpost/paginginitreseller/'+type+'/'+status+'/'+blogid;
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

		$('.forwardproductr').attr('name',forward);
		$('.prevproductr').attr('name',prev);

		
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
		$('div#numcountproductr').html(paginglink);
	    return msg;
	 }
	}).responseText);
}

function getallproduct(){
	var blogid = $('#hideblogid').val();
	url = 'http://www.kaffah.biz/reqpost/getallproductparent/'+blogid;
	return JSON.parse($.ajax({
	     type: 'POST',
	     url: url,
	     dataType: 'json',
		 data : {},
	     global: false,
	     async:false,
	     success: function(data) {
	     
	        return data;
	     }
	 }).responseText);
}

var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();