$(document).ready(function(){
	
	/* variable init */
	var status = null ;
	var blogid = $('#hideblogid').val();
	var limit = $('#limit').val();
	var page = 1;

	loadorder(blogid,status,limit,page);	
	paginginit(blogid,page);

	$(document).on('click','#commisionlist tbody tr td em.klikdetil', function(eve){
		eve.preventDefault();
		$(this).parents('#commisionlist tbody tr td').siblings().children('div.detailpop').fadeOut();
		$(this).parents('#commisionlist tbody tr').siblings().children('td').children('div.detailpop').fadeOut();
		$(this).siblings('div.detailpop').fadeIn('fast');
	});	

	$(document).on('click','div.detailpop a.close', function(eve){
		eve.preventDefault();		
		$(this).parent('div.detailpop').fadeOut();
	});		
});


function loadorder(blogid,status,limit,page){
	var web_address = 'http://'+window.location.hostname ;
 	$('div.blackbg').show();	
	$('div.loadupload').show();	
	
	var transaction = gettransaction(blogid,status,limit,page);	
	if(transaction != null){
		var transaction_id = [];
		var blogidparent ;

		$.each(transaction, function(index, element) { 		
			if(element.transaction_parent != 0){
				trxid = element.transaction_parent;
			}
			else{
				trxid = trxid;
			}
			transaction_id.push(trxid); 
		});

		gettransactiondetail(transaction_id,blogid,status,limit,page);		
	}


 	$('div.blackbg').hide();	
	$('div.loadupload').hide();

	$(document).on('mouseover','#commisionlist tbody tr', function(eve){	
		eve.preventDefault();
		$(this).children('td').children('div.edit').show();
	});

	$(document).on('mouseleave','#commisionlist tbody tr', function(eve){	
		eve.preventDefault();
		$(this).children('td').children('div.edit').hide();
	});
}

function gettransaction(blogid,status,limit,page){		

	var x = 0;	
	var url = null;

	var pageinit = paginginit(blogid,page);
	if(page != '') page = page - 1;		
	else page = 0;		
	var offset = page * pageinit.per_page;

	/* preparing for textsearch and category */
	textsearch = $('#search').val();
	filterstatus = $('#filterstatus').val();
	
	/* ketika textsearch dan kategori tidak kosong */
	if(textsearch != null && textsearch != ''){
		url = 'http://www.kaffah.biz/reqpost/getorder_byself_commision/'+blogid+'/'+limit+'/'+offset+'/s/'+textsearch;
	}
	else if(filterstatus != null && filterstatus != ''){
		url = 'http://www.kaffah.biz/reqpost/getorder_byself_commision/'+blogid+'/'+limit+'/'+offset+'/f/'+filterstatus;
	}
	else{
		url = 'http://www.kaffah.biz/reqpost/getorder_byself_commision/'+blogid+'/'+limit+'/'+offset;
	}

	

	return JSON.parse($.ajax({
	     type: 'POST',
	     url: url,
	     dataType: 'json',
		 data : {},
	     global: false,
	     async:false,
	     beforeSend: function(data){
	     	$('div.blackbg').show();	
			$('div.loadupload').show();	
			$('div.popup').hide();	
	     },
	     success: function(data) {

		 	$('div.blackbg').hide();	
			$('div.loadupload').hide();

			$('#commisionlist tbody tr').remove();
			if(data != null){
				$.each(data, function(index, element) {
					var datetime = element.transaction_time.split(' ');
					var date = datetime[0].split('-');
					var fixdate = date[2]+'/'+date[1]+'/'+date[0];			

					if(element.transaction_parent != 0){
						trxid = element.transaction_parent;
					}
					else{
						trxid = trxid;
					}

					odd = x % 2;
					if(odd == 1){trclass = 'even';}
					else{trclass = 'odd';}			

						$('#commisionlist').find('tbody')
						.append($('<tr>').attr('class', trclass)
							.append(
								
								
								$('<td>').attr('class', 'invoice trx').attr('id', 'transaction_id_'+trxid).html('<a href="#" >#'+trxid+'</a>'+

										'<em class="transaction_d"><ul class="transdetail"></ul></em>'+
										'<em class="transaction_d normal klikdetil">(klik untuk detil)</em>'+
										'<div class="detailpop"><a href="#" class="close">X</a>'+
										'<strong class="name">'+element.nama_lengkap+' (invoice #'+trxid+')</strong>'+
										'Melakukan pembelian :'+
										'<ul class="transdetailfull">'+
										
										'</ul>'+'<br>Jumlah : <strong>'+formatNumber(element.total)+'</strong><br>'+
										'Ongkos Kirim : <strong>'+formatNumber(element.total_tax)+'</strong> (JNE REG) <br>'+
										'Angka Unik : <strong>'+formatNumber(element.random)+'</strong><br>'+
										'Total pembayaran : <strong>'+formatNumber(element.all_total)+'</strong><br>'+
										'Waktu Transaksi : <strong>'+fixdate+'</strong><br>'+
										'Status : <strong>'+ formatString(element.transaction_status)+'</strong>'+
										'</div>' +

										'<div class="tablehidden_'+trxid+' hidden">'+
											
										'</div>'

									),
								
								$('<td>').attr('class', 'nama').html(
										'<h3>'+element.nama_lengkap+'</h3>'+fixdate+'<em class="info_detil normal klikdetil shipdetil">(klik untuk detil)</em>'+
										'<div class="detailpop"><a href="#" class="close">X</a>'+
											'<strong class="name">'+element.nama_lengkap+' (invoice #'+trxid+')</strong>'+
												'Alamat :<br><strong>'+element.alamat+', '+
												formatString(element.kota)+', '+formatString(element.provinsi)+'</strong><br>'+
												'No HP : <strong>'+formatString(element.no_handphone)+'</strong> <br>'+										
												'No Telp : <strong>'+formatString(element.no_telepon)+'</strong><br>'+										
												'Email : <strong>'+formatString(element.email)+'</strong>'+								
										'</div>'
									),		

								$('<td>').html('<em class="info_detil">'+formatString(element.name)+' X '+element.quantity+' = '+ formatNumber(element.price * element.quantity) +'</em>'),
								$('<td>').attr('class', 'commfee').html(formatNumber(element.commision_reseller * element.quantity)),								
								$('<td>').attr('class', 'commision').html(formatString(element.commision_status))
								
							)	
						
						);			
						
					x++;
				});			
			}
		        

	        return data;
	     }
	 }).responseText);
}
	
function gettransactiondetail(transaction_id,blogid,status,limit,page) {
	var url = 'http://www.kaffah.biz/reqpost/getdetailtransaction_byself/'+blogid		

	/* mengambil detail transaksi lewat transaction_parent */

	return JSON.parse($.ajax({
	     type: 'POST',
	     url: url,
	     dataType: 'json',
		 data : {transaction_id:transaction_id},
	     global: false,
	     async:false,
		 beforeSend: function(){
			$('div.popup').hide();
		 	$('div.blackbg').show();	
			$('div.loadupload').show();
		 },	     
	     success: function(data) {
		 	$('div.blackbg').hide();	
			$('div.loadupload').hide();	     	
	     	var x = 1;
	     	var transaction_id ;
	        $.each(data, function(index, element) {
	        	var atr = element.option;
	        	
	        	if(atr.Size != '' && atr.Size != null && atr.Size != '-' ){
	        		option = ' ( <strong>warna: '+atr.Color+', ukuran:'+atr.Size+' </strong>) ';
	        	}
	        	else{
	        		option = '';
	        	}

				if(element.transaction_parent != 0){
					trxid = element.transaction_parent;
				}
				else{
					trxid = trxid;
				}	        	

	        	
	        	$('#commisionlist tbody tr td#transaction_id_'+trxid+' ul.transdetailfull').append('<li>'+formatString(element.name)+'<strong> x '+element.quantity+'</strong> : <strong>Rp  '+formatNumber(element.price)+'</strong>'+option+'</li>');
	        		        	
	        	if(transaction_id != trxid){
	        		x = 1;
	        	}
	        	else{
	        		x++;
	        	}

	        	$('div.tablehidden_'+trxid).append('<tr class="even"><td>'+x+'</td><td><strong>'+formatString(element.name)+'</strong><strong> x '+element.quantity+'</strong> : <strong>Rp  '+formatNumber(element.price)+'</strong> '+option+'</td><td class="right">'+formatNumber(element.price)+' </td><td class="right"><strong>'+formatNumber(element.sub_total)+'</strong></td></tr>');


	        	transaction_id = trxid;
	        	
	        });

	        return data;
	     }
	 }).responseText);
}

function paginginit(blogid,page) {
	var paginglink = null;
	var pagetemp = null;
	var url = null;

	if(page == '') page = 1;
	
	textsearch = $('#search').val();
	/* preparing for textsearch and categoryfiltercategory = $('#filtercategory').val(); */	
	filterstatus = $('#filterstatus').val();
		
	if(textsearch != null && textsearch != ''){
		url = 'http://www.kaffah.biz/reqpost/orderpaginginit_commision_byself/'+blogid+'/s/'+textsearch;
	}
	else if(filterstatus != null && filterstatus != ''){
		url = 'http://www.kaffah.biz/reqpost/orderpaginginit_commision_byself/'+blogid+'/f/'+filterstatus;
	}
	else{	
		url = 'http://www.kaffah.biz/reqpost/orderpaginginit_commision_byself/'+blogid;
	}
	
	
	/* ambil paging */
	return JSON.parse($.ajax({
	 type: 'POST',
	 url: url,
	 dataType: 'json',
	 global: false,
	 async:false,
	 success: function(msg) {
	 	paginglink = '<ul>';

		$('#currpageorder').html(page);
		
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

		$('.forwardorder').attr('name',forward);
		$('.prevorder').attr('name',prev);

		
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
		$('div#numcountorder').html(paginglink);
	    return msg;
	 }
	}).responseText);
}


function getVal(what,val){
	return val.children('#hidden_'+what).val();
}

function formatString(string){
	return string.replace(/_/g, " ");
}

function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.")
}

var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();