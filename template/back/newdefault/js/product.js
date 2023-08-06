$(document).ready(function(){
	/* variable init */
	var status = null ;
	var blogid = $('#hideblogid').val();
	var limit = $('#limit').val();
	var page = 1;
	var delsinglearticle = null;	
	var textsearch = null;
	var filtercategory = null;

	/* function loaded */
	setatr();
	loadproduct('product',status,blogid,limit,page); 
	deleteproduct('product',status,blogid,limit,page);
	massdelproduct('product',status,blogid,limit,page);		
	massdraft('product',status,blogid,limit,page);		
	masspublish('product',status,blogid,limit,page);		
	linkinit('product',status,blogid,limit,page);	
	searching('product',status,blogid,limit,page);
	categoryproduct('product',status,blogid,limit,page);
	loadcategory(blogid);
	$('div.blackbg').hide();					
});

function loadcategory(blogid){
	var parenting ;
	var childing ;
	var topofthem ;
	var separate = '';
	
	$.getJSON('http://www.kaffah.biz/reqpost/getallcat_except/category_product/'+blogid, { get_param: 'value' }, function(data) {
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
			 $('.postfilterproduct ul').append('<li class=""><a class="catfilter" href="" name="'+ element.slug +'" >' + element.name +'</a></li>');
		});
	});		
}

function categoryproduct(type,status,blogid,limit,page){
	/*  ketika kategori di hover */
	$('div.postfilter').hover(function(){
		$(this).children('.postfilterproduct').show();
	}, function(){
		$(this).children('.postfilterproduct').hide();
	});
	
	/* ketika category filternya di klik */
	$(document).on('click','.postfilterproduct ul li a.catfilter', function(eve){
	//$('.postfilterproduct ul li a.catfilter').click(function(eve){
		eve.preventDefault();
		var valuea = $(this).attr('name');
		filtercategory = $('#filtercategory').val(valuea);
		loadproduct(type,status,blogid,limit,page,textsearch,filtercategory); 
		$('em.postfilter').html($(this).html());
		$('#search').val('');
		$('.postfilterproduct').hide();
		$(this).die('click');
		return false;
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

function linkinit(type,status,blogid,limit,page){
	/* ini digunakan untuk menjelaskan bahwa page yang di akses di adalah : */
	$(document).on('click','#numcountproduct ul li a, .forwardproduct, .prevproduct', function(eve){
		eve.preventDefault();
		
		var valname = $(this).attr('name');
		$.getJSON('http://www.kaffah.biz/reqpost/pagingarticle/'+valname+'/product', { }, function(data) {
			$('#page').val(data.pagecurrent);
			page = $('#page').val();
			loadproduct(type,status,blogid,limit,page,textsearch,filtercategory); 		
		});	
		$('#numcountproduct ul').hide();
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
	status = $('#statusarticle').val();
	
	/* ketika textsearch dan kategori tidak kosong */
	if(textsearch != null && textsearch != ''){
		url = 'http://www.kaffah.biz/reqpost/paginginit/'+type+'/'+status+'/'+blogid+'/s/'+textsearch;
	}
	else if(filtercategory != null && filtercategory != ''){
		url = 'http://www.kaffah.biz/reqpost/paginginit/'+type+'/'+status+'/'+blogid+'/f/'+filtercategory;
	}
	else{
		url = 'http://www.kaffah.biz/reqpost/paginginit/'+type+'/'+status+'/'+blogid;
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

function masspublish(type,status,blogid,limit,page){
	var web_address = 'http://'+window.location.hostname ;
	$('#blogid').val(blogid);
	
	/* ketika tombol mass delete di klik*/
	$('#masspublish_product').click(function(){	 		
		$('div.wrappop').html('<h2 class="headpop">Konfirmasi Status Artikel</h2><p>Apakah Anda ingin menerbitkan artikel terpilih ?</p><a id="confirmmasspublishyes_product" class="confirmbox">Ya</a><a id="confirmmassno" class="confirmbox">Tidak</a> ');
		$('div.blackbg').fadeIn('fast');
		$('div.popup').slideDown('fast');	
	});

	/* ketika tombol close di klik */
	$('a.close').click(function(eve){
		eve.preventDefault();
		$('div.popup').slideUp('fast');
		$('div.blackbg').fadeOut('fast');
	});
	
	// confirmmasspublishyes
	$(document).on('click','a#confirmmasspublishyes_product', function(eve){	
		eve.preventDefault();
		$(this).attr('disabled','disabled');
		
		/* kirim via ajax semuanya untuk di delete */
		$.ajax('http://www.kaffah.biz/reqpost/masspubarticle', {
			dataType : 'html',
			type : 'post',
			data: $('#tablepost :input').serialize(),
			beforeSend: function(){
				$('div.popup').hide();
			 	$('div.blackbg').show();	
				$('div.loadupload').show();
			 },
			 
			success: function(msg){			
				$('#checkall').prop('checked', false);	
			 	$('div.blackbg').hide();	
				$('div.loadupload').hide();
				loadproduct(type,status,blogid,limit,page);
				$('a.close').click();
			},
		});

		$(this).die('click');
		
	});
}

function massdraft(type,status,blogid,limit,page){
	var web_address = 'http://'+window.location.hostname ;
	$('#blogid').val(blogid);
	
	/* ketika tombol mass delete di klik*/
	$('#massdraft_product').click(function(){	 		
		$('div.wrappop').html('<h2 class="headpop">Konfirmasi Status Produk</h2><p>Apakah Anda ingin menjadikan <b>ke dalam draft</b> pada produk terpilih  ?</p><a id="confirmmassdraftyes_product" class="confirmbox">Ya</a><a id="confirmmassno" class="confirmbox">Tidak</a> ');
		$('div.blackbg').fadeIn('fast');
		$('div.popup').slideDown('fast');	
	});

	/* ketika tombol close di klik */
	$('a.close').click(function(eve){
		eve.preventDefault();
		$('div.popup').slideUp('fast');
		$('div.blackbg').fadeOut('fast');
	});
	
	// confirmmassdraftyes
	$(document).on('click','a#confirmmassdraftyes_product', function(eve){	
		eve.preventDefault();
		$(this).attr('disabled','disabled');
		
		/* kirim via ajax semuanya untuk di delete */
		$.ajax('http://www.kaffah.biz/reqpost/massdraftarticle', {
			dataType : 'html',
			type : 'post',
			data: $('#tablepost :input').serialize(),
			beforeSend: function(){
				$('div.popup').hide();
			 	$('div.blackbg').show();	
				$('div.loadupload').show();
			 },
			 
			success: function(msg){
				$('#checkall').prop('checked', false);	
			 	$('div.blackbg').hide();	
				$('div.loadupload').hide();
				loadproduct(type,status,blogid,limit,page);
				$('a.close').click();
			},
		});

		$(this).die('click');
		
	});
}

function massdelproduct(type,status,blogid,limit,page){
	var web_address = 'http://'+window.location.hostname ;
	$('#blogid').val(blogid);
	
	/* ketika tombol mass delete di klik*/
	$('#massdel_product').click(function(){	 		
		$('div.wrappop').html('<h2 class="headpop">Konfirmasi Hapus Artikel</h2><p>Apakah Anda ingin menghapus artikel yang dipilih </p><a id="confirmmassyes_product" class="confirmbox">Ya</a><a id="confirmmassno" class="confirmbox">Tidak</a> ');
		$('div.blackbg').fadeIn('fast');
		$('div.popup').slideDown('fast');	
	});

	/* ketika tombol close di klik */
	$('a.close').click(function(eve){
		eve.preventDefault();
		$('div.popup').slideUp('fast');
		$('div.blackbg').fadeOut('fast');
	});
		
	/* jika pilihan konfirmasi no di pilih */
	$(document).on('click','a#confirmmassno', function(eve){	
		eve.preventDefault();
		$('a.close').click();
	});

	
	/* ketika tombol konfirmasi yes di klik */	
	$(document).on('click','a#confirmmassyes_product', function(eve){	
		eve.preventDefault();
		$(this).attr('disabled','disabled');
		
		/* kirim via ajax semuanya untuk di delete */
		$.ajax('http://www.kaffah.biz/reqpost/massdelproduct', {
			dataType : 'html',
			type : 'post',
			data: $('#tablepost :input').serialize(),
			beforeSend: function(){
				$('div.popup').hide();
			 	$('div.blackbg').show();	
				$('div.loadupload').show();
			 },
			 
			success: function(msg){
				$('#checkall').prop('checked', false);
			 	$('div.blackbg').hide();	
				$('div.loadupload').hide();
				loadproduct(type,status,blogid,limit,page);
				$('a.close').click();
			},
		});
		$(this).die('click');
	});
}

function deleteproduct(type,status,blogid,limit,page){	
	$(document).on('click', 'a.delarticle', function(eve){
		eve.preventDefault();
		var ID = $(this).attr('id');
		var title = $(this).attr('title');
		delsinglearticle = ID;
		$('div.wrappop').html('<h2 class="headpop">Konfirmasi Hapus Artikel</h2><p>Apakah Anda ingin menghapus artikel : <strong>' +title+'</strong></p><a id="confirmyes" class="confirmbox">Ya</a><a id="confirmno" class="confirmbox">Tidak</a> ');
		$('div.blackbg').fadeIn('fast');
		$('div.popup').slideDown('fast');
	});
	
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

		$.post('http://www.kaffah.biz/reqpost/delsinglearticle', {id: delsinglearticle, blogid: blogid}, 
			function(html){
				$('a#'+delsinglearticle).parents('#productlist tbody tr').fadeOut('slow');
				loadproduct(type,status,blogid,limit,page)
				$('a.close').click();				
			}
		);	
		$(this).die('click');

	});
}

function loadproduct(type,status,blogid,limit,page){
	var type = 'product';
	var web_address = 'http://'+window.location.hostname ;
 	$('div.blackbg').show();	
	$('div.loadupload').show();	
			 
	var x = 0;
	var url = null;
	
	/* paging configuration */
	var pageinit = paginginit('product',status,blogid,page);	

	
	/* if page access is empty */
	if(page != '') page = page - 1;		
	else page = 0;	
	
	/* offset for paging */
	var offset = page * pageinit.per_page;
	
	/* preparing for textsearch and category */
	textsearch = $('#search').val();
	filtercategory = $('#filtercategory').val();
	status = $('#statusarticle').val();
	
	/* ketika textsearch dan kategori tidak kosong */
	if(textsearch != null && textsearch != ''){
		url = 'http://www.kaffah.biz/reqpost/getallarticle/product/'+status+'/'+blogid+'/'+limit+'/'+offset+'/s/'+textsearch;
	}
	else if(filtercategory != null && filtercategory != ''){
		url = 'http://www.kaffah.biz/reqpost/getallarticle/product/'+status+'/'+blogid+'/'+limit+'/'+offset+'/f/'+filtercategory;
	}
	else{
		url = 'http://www.kaffah.biz/reqpost/getallarticle/product/'+status+'/'+blogid+'/'+limit+'/'+offset;
	}
	
	/* get data */			
	$.getJSON(url, { }, function(data) {
		 
		 $('#productlist tbody tr').remove();
		 $.each(data, function(index, element) {
		 	 
			var datetime = element.post_date.split(' ');
			var date = datetime[0].split('-');
			var fixdate = date[2]+'/'+date[1]+'/'+date[0];
			var post_status;
			var product_atr;
			
			odd = x % 2;
			if(odd == 1){trclass = 'even';}
			else{trclass = 'odd';}
			
			if(element.post_status == 'publish') {class_status="orange"; post_status = 'diterbitkan';}
			else {class_status="red"; post_status = 'draft';}

			if(element.post_reseller == 'yes' && element.blog_id == '113410'){
				product_atr = '<em class="kode">Kode: <b>'+element.post_code+'</b></em>'
						+'<em class="price">Harga Konsumen: <b>Rp '+element.post_price.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.")+'</b></em>'
						+'<em class="stock">Stok: <b>'+element.post_stock.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.")+'</b></em>'
						+'<em class="commision">Komisi Reseller: <b>Rp '+element.post_reseller_fee.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.")+'</b></em>' 
						+'<em class="commisionks">Fee KaffahStore: <b>Rp '+element.post_ks_fee.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.")+'</b></em>';
			}
			else{				
				product_atr = '<em class="kode">Kode: <b>'+element.post_code+'</b></em>'
						+'<em class="price">Harga: <b>Rp '+element.post_price.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.")+'</b></em>'
						+'<em class="stock">Stok: <b>'+element.post_stock.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.")+'</b></em>';
			}
			
		 	$('#productlist').find('tbody')
				.append($('<tr>').attr('class', trclass)
					.append(
					$('<td>', {text:'tes'}).html('<input type="checkbox" name="postid[]" value="'+element.ID+'" class="checkinput" />')
					, $('<td>').attr('class', 'title').html('<img class="imgontd" src="'+element.post_image+
					'" /><a title="klik untuk mengedit artikel : '+element.post_title+'" href="/site/member/'
						+element.blog_id+'/full/'+element.ID+'/#editproduct">'+element.post_title+' <em class="status '
						+class_status+'">('+ post_status +')</em></a>'
						+product_atr						
						+'<div class="edit product"><a href="/site/member/'
						+element.blog_id+'/full/'+element.ID+'/#editproduct">Edit</a> | <a id="'+element.ID+'" class="delarticle" title="'
						+element.post_title+'" href="/site/member/'+element.blog_id+'/full/#delarticle/'+element.ID+'">Hapus</a></div>')
					//, $('<td>').attr('class', 'author').html('<em class="author">'+element.name+'</em>')
					, $('<td>').html('<em class="cmt">'+element.comment_count+'</em>')
					, $('<td>').html('<em class="see">'+element.post_counter+'</em>')
					, $('<td>').html('<em class="clock">'+fixdate+'</em>')
					
					)	
				
				);
				
			x++;
		 });
	});	
	
	
	$(document).on('mouseover','#productlist tbody tr td.title', function(eve){	
		eve.preventDefault();
		$(this).children('div.edit').show();
	});
	
	$(document).on('mouseleave','#productlist tbody tr td.title', function(eve){	
		eve.preventDefault();
		$(this).children('div.edit').hide();
	});
	
 	$('div.blackbg').hide();	
	$('div.loadupload').hide();
}


function setatr(){
	var blogid = $('#hideblogid').val();
	
	$('#checkall').on('click', function() {
		if($(this).is(":checked") == true){
			$('.checkinput').prop('checked', true);	
		}
		else{
			$('.checkinput').prop('checked', false);
		}
	    
	});
	
	$('#addblog').click(function(eve){eve.preventDefault();window.location = '/site/member/'+blogid+'/full/#newproduct'});
	
	$('#addblog').hover(function(eve){
		$(this).removeAttr('href');
	});	
}

var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();