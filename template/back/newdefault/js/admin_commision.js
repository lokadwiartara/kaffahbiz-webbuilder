$(document).ready(function(){
	
	/* variable init */
	var status = null ;
	var blogid = $('#hideblogid').val();
	var limit = $('#limit').val();
	var page = 1;	

	loadorder(blogid,status,limit,page);
	buttonclick(blogid,status,limit,page);
	statusfilter(blogid,status,limit,page);
	paginginit(blogid,page);
	linkinit(blogid,status,limit,page);
	searching(blogid,status,limit,page);
	editcommision(blogid,limit,page);
});

function editcommision(blogid,limit,page){
	$(document).on('click', 'a.editcomm', function(eve){
		eve.preventDefault();

		var id = $(this).attr('id').replace('edit_','');

		var vall = $('div.formhidden_'+id);	
		var user_id_parent = getVal('user_id_parent',vall);	

		var rekening = getrekening(user_id_parent);

		$('div.blackbg').show();	
		$('div.loadupload').show();
		
		$('div.popup').css('width','60%').css('height','70%').css('margin-top','5%');
		$('div.wrappop').html('<h2 class="headpop">Form Pengeditan Komisi Reseller + KS (KaffahStore)</h2><div class="overflowform">'+
			'<form id="formcommision_edit" method="post">'+
			'<label for="reseller">Reseller</label>'+
			'<input type="text" class="input_text long_text disable" name="reseller" id="reseller" value="'+getVal('reseller',vall)+'">'+
			'<br>'+
			'<label for="Rekening">Rekening</label>'+
			'<input type="text" class="input_text long_text disable" name="rekening" id="rekening" value="'+rekening.bank+' / '+rekening.atas_nama+' / '+rekening.no_rek+'">'+
			'<br>'+
			'<label for="konsumen">Konsumen</label>'+
			'<input type="text" class="input_text long_text disable" name="konsumen" id="konsumen" value="'+getVal('konsumen',vall)+'">'+
			'<br>'+
			'<label for="produk">Produk</label>'+
			'<input type="text" class="input_text long_text disable" name="produk" id="produk" value="'+getVal('produk',vall)+'">'+
			'<br>'+
			'<label for="komisi_reseller">Komisi Reseller</label>'+
			'<input type="text" class="input_text long_text disable" name="komisi_reseller" id="komisi_reseller" value="'+getVal('komisi_reseller',vall)+'">'+
			'<br>'+
			'<label for="komisi_ks">Komisi KS</label>'+
			'<input type="text" class="input_text long_text disable" name="komisi_ks" id="komisi_ks" value="'+getVal('komisi_ks',vall)+'">'+
			'<br>' + 
			'<label for="status_komisi">Status Komisi</label>'+
			'<input type="text" class="input_text long_text" name="status_komisi" id="status_komisi" value="'+getVal('status_komisi',vall)+'">'+
			'<p class="note">Format status komisi : SUDAH_DIBAYAR # BANK # NO.REK # ATAS NAMA</p>'+			
			'<input type="hidden" name="transaction_id" value="'+id+'">'+			
			'<input type="hidden" name="product_id" value="'+getVal('produkid',vall)+'">'+			
			'<input type="submit" value="Update Komisi!" class="btnAct addSubmit">'

			);

		$('div.loadupload').hide();
		$('div.blackbg').fadeIn(0);
		$('div.popup').slideDown(0);

	});


	$(document).on('submit', '#formcommision_edit', function(eve){
		eve.preventDefault();		
		var web_address = 'http://'+window.location.hostname ;
		$.ajax('http://www.kaffah.biz/reqpost/updatecommision_byreseller/'+blogid, {
			dataType : 'html',
			type : 'POST',
			data: $('#formcommision_edit :input').serialize(),
			beforeSend: function(){
				$('div.popup').hide();
			 	$('div.blackbg').show();	
				$('div.loadupload').show();
			},
			 
			success: function(msg){
			 	$('div.blackbg').hide();	
				$('div.loadupload').hide();
				loadorder(blogid,status,limit,page);
				$('a.close').click();
			},
		});


		$('div.popup').slideUp('fast');
		$('div.blackbg').fadeOut('fast');
		$('div.wrappop').children('div.overflowform').remove();
		$(this).die('click');
		
	});
}

function getrekening(userid){
	return JSON.parse($.ajax({
	     type: 'POST',
	     url: 'http://www.kaffah.biz/reqpost/getrekeninguser/'+userid,
	     dataType: 'json',
		 data : {userid:userid},
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
	        return data;
	     }
	 }).responseText);
}

function buttonclick(blogid,status,limit,page){

	var temp = null;

	$('#checkall').on('click', function() {
		if($(this).is(":checked") == true){
			$('.checkinput').prop('checked', true);	
		}
		else{
			$('.checkinput').prop('checked', false);
		}
	    
	});

	$('#postaction ul li').on('click', function(){
		var action = $(this).attr('id');

		var numberOfChecked = $('input:checkbox:checked').length;
		if(numberOfChecked > 0){
			if(action == 'massprint_order'){
				$('div.popup').css('width','640px').css('height','auto').css('margin-top','5%');
				$('div.wrappop').html('<h2 class="headpop">Konfirmasi Cetak Transaksi</h2><p>Anda akan mencetak semua transasksi terpilih ke dalam bentuk document, yang siap diprint sebagai alamat kirim.</p><a id="confirm_massprint_yes" class="confirmbox confirm_yes">Ya</a><a id="confirm_massprint_no" class="confirmbox confirm_no">Tidak</a> ');
				$('div.blackbg').fadeIn('fast');
				$('div.popup').slideDown('fast');
			}
			
			else if(action == 'masspending_order'){
				$('div.popup').css('width','640px').css('height','auto').css('margin-top','5%');
				$('div.wrappop').html('<h2 class="headpop">Konfirmasi Pending Transaksi</h2><p>Apakah Anda ingin menjadikan pending semua transaksi yang dipilih </p><a id="confirm_masspending_yes" class="confirmbox confirm_yes">Ya</a><a id="confirm_masspending_no" class="confirmbox confirm_no">Tidak</a> ');
				$('div.blackbg').fadeIn('fast');
				$('div.popup').slideDown('fast');				
			}
			
			else if(action == 'masstransfer_order'){
				$('div.popup').css('width','640px').css('height','auto').css('margin-top','5%');
				$('div.wrappop').html('<h2 class="headpop">Konfirmasi Transfer</h2><p>Apakah Anda ingin merubah status transaksi yang dipilih menjadi sudah di transfer?</p><a id="confirm_masstransfer_yes" class="confirmbox confirm_yes">Ya</a><a id="confirm_masstransfer_no" class="confirmbox confirm_no">Tidak</a> ');
				$('div.blackbg').fadeIn('fast');
				$('div.popup').slideDown('fast');					
			}
			
			else if(action == 'masssend_order'){
				$('div.popup').css('width','640px').css('height','auto').css('margin-top','5%');
				$('div.wrappop').html('<h2 class="headpop">Konfirmasi Pengiriman Order</h2><p>Apakah Anda ingin merubah status transaksi yang dipilih menjadi sudah dikirim?</p><a id="confirm_masssend_yes" class="confirmbox confirm_yes">Ya</a><a id="confirm_masssend_no" class="confirmbox confirm_no">Tidak</a> ');
				$('div.blackbg').fadeIn('fast');
				$('div.popup').slideDown('fast');				
			}

			else if(action == 'massfailed_order'){
				$('div.popup').css('width','640px').css('height','auto').css('margin-top','5%');
				$('div.wrappop').html('<h2 class="headpop">Konfirmasi Gagal Transaksi</h2><p>Apakah Anda ingin menggagalkan transaksi yang dipilih </p><a id="confirm_massfailed_yes" class="confirmbox confirm_yes">Ya</a><a id="confirm_massfailed_no" class="confirmbox confirm_no">Tidak</a> ');
				$('div.blackbg').fadeIn('fast');
				$('div.popup').slideDown('fast');					
			}

			else if(action == 'massdel_order'){
				$('div.popup').css('width','640px').css('height','auto').css('margin-top','5%');
				$('div.wrappop').html('<h2 class="headpop">Konfirmasi Hapus Transaksi</h2><p>Apakah Anda ingin menghapus transaksi yang dipilih </p><a id="confirm_massdel_order_yes" class="confirmbox confirm_yes">Ya</a><a id="confirm_massdel_no" class="confirmbox confirm_no">Tidak</a> ');
				$('div.blackbg').fadeIn('fast');
				$('div.popup').slideDown('fast');			
			}			
		}

		else{
				$('div.popup').css('width','640px').css('height','auto').css('margin-top','5%');
				$('div.wrappop').html('<h2 class="headpop">Konfirmasi Aksi</h2><p>Mohon maaf, belum ada transaksi yang Anda pilih, silahkan memilih terlebih dahulu, baru melakukan aksi transaski. </p><a class="confirmbox confirm_no">Baik!</a> ');
				$('div.blackbg').fadeIn('fast');
				$('div.popup').slideDown('fast');				
		}

	
	});

	$(document).on('click', 'a#confirm_massdel_order_yes', function(eve){
		eve.preventDefault();
		$(this).attr('disabled','disabled');
		var web_address = 'http://'+window.location.hostname ;

		$.ajax('http://www.kaffah.biz/reqpost/mass_delete_transaction/'+blogid, {
			dataType : 'json',
			type : 'POST',
			data: $('input:checkbox:checked').serialize(),
			beforeSend: function(){
				$('div.popup').hide();
			 	$('div.blackbg').show();	
				$('div.loadupload').show();
			},
			 
			success: function(msg){
			 	$('div.blackbg').hide();	
				$('div.loadupload').hide();				
				if(msg.status == true){
					$('#checkall').prop('checked', false);	
					$('a.close').click();
					loadorder(blogid,status,limit,page);					
				}
				else{

				}								
			},
		});		

	})

	$(document).on('click','a.confirm_yes', function(eve){	
		eve.preventDefault();
		$(this).attr('disabled','disabled');
		var tra_stats = $(this).attr('id');
		var web_address = 'http://'+window.location.hostname ;

		if(tra_stats == 'confirm_masspending_yes'){
			stats = 'pending';
		}
		
		else if(tra_stats == 'confirm_masstransfer_yes'){
			stats = 'sudah_transfer';
		}
		
		else if(tra_stats == 'confirm_masssend_yes'){
			stats = 'sudah_dikirim';
		}
		
		else if(tra_stats == 'confirm_massfailed_yes'){
			stats = 'gagal';
		}		

		else{
			stats = null;
		}

		if(stats != null){
			$.ajax('http://www.kaffah.biz/reqpost/mass_update_transaction/'+blogid+'/'+stats, {
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
					 	$('div.blackbg').hide();	
						$('div.loadupload').hide();
						$('a.close').click();
						loadorder(blogid,status,limit,page);					
					}
					else{

					}
					
				},
			});			
		}

	});


	$(document).on('click','a.delorder', function(eve){	
		eve.preventDefault();
		temp = $(this).attr('id');
		$(this).attr('disabled','disabled');
		$('div.popup').css('width','640px').css('height','auto').css('margin-top','5%');
		$('div.wrappop').html('<h2 class="headpop">Konfirmasi Hapus Transaksi</h2><p>Apakah Anda ingin menghapus transaksi ?</p><a id="confirm_del_yes" class="confirmbox confirm_yes">Ya</a><a class="confirmbox confirm_no">Tidak</a> ');
		$('div.blackbg').fadeIn('fast');
		$('div.popup').slideDown('fast');	
			
	});

	$(document).on('click', 'a#confirm_del_yes', function(eve){
		var web_address = 'http://'+window.location.hostname ;
		
		$.ajax('http://www.kaffah.biz/reqpost/delete_transaction/'+blogid, {
			dataType : 'json',
			type : 'POST',
			data: {transaction_id:temp},
			beforeSend: function(){
				$('div.popup').hide();
			 	$('div.blackbg').show();	
				$('div.loadupload').show();
			},
			 
			success: function(msg){
			 	$('div.blackbg').hide();	
				$('div.loadupload').hide();
				$('a.close').click();
				loadorder(blogid,status,limit,page);
				temp = null;
			},
		});
				

	});


	$(document).on('click','a.confirm_no', function(eve){	
		eve.preventDefault();
		$('a.close').click();
	});


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
}

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

function linkinit(blogid,status,limit,page){
	/* ini digunakan untuk menjelaskan bahwa page yang di akses di adalah : */
	$(document).on('click','#numcountorder ul li a, .forwardorder, .prevorder', function(eve){
		eve.preventDefault();		
		var valname = $(this).attr('name');

		$.getJSON('http://www.kaffah.biz/reqpost/pagingorder/'+valname, { }, function(data) {
			$('#page').val(data.pageordercurrent);			
			page = $('#page').val();

			loadorder(blogid,status,limit,page);
		});			
		
		$('#numcountorder ul').hide();
		$(this).die('click');		
	});
}

function getVal(what,val){
	return val.children('#hidden_'+what).val();
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
		url = 'http://www.kaffah.biz/reqpost/getorder_byreseller_commision/'+blogid+'/'+limit+'/'+offset+'/s/'+textsearch;
	}
	else if(filterstatus != null && filterstatus != ''){
		url = 'http://www.kaffah.biz/reqpost/getorder_byreseller_commision/'+blogid+'/'+limit+'/'+offset+'/f/'+filterstatus;
	}
	else{
		url = 'http://www.kaffah.biz/reqpost/getorder_byreseller_commision/'+blogid+'/'+limit+'/'+offset;
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
								$('<td>').html('<input type="checkbox" name="orderid[]" value="'+trxid+'" class="checkinput" />'),
								$('<td>').attr('class', 'nama').html('<h3>'+element.name_parent+'</h3>'+element.domain_name_parent.replace('www.','')+'<br />'+element.handphone_parent),	
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
								$('<td>').attr('class', 'commfee').html(formatNumber(element.commision_ks * element.quantity)),
								$('<td>').attr('class', 'commision').html(formatString(element.commision_status)+'<div class="edit"><a id="edit_'+trxid+'" class="editcomm" title="" href="#editcomm" >Edit</a> | '+
									'<a id="del_'+trxid+'" class="delcomm" title="" href="#delcomm">Hapus</a></div>' +
									'<div class="formhidden_'+trxid+' hidden">'+
										'<input type="hidden" name="reseller" id="hidden_reseller" value="'+element.name_parent+' / '+ element.domain_name_parent.replace('www.','') +' / '+ element.handphone_parent +'" />'+
										'<input type="hidden" name="konsumen" id="hidden_konsumen" value="'+element.nama_lengkap+' (INVOICE #'+trxid+')" />'+
										'<input type="hidden" name="produk" id="hidden_produk" value="'+formatString(element.name)+' X '+element.quantity+' = '+ formatNumber(element.price * element.quantity) +'" />'+
										'<input type="hidden" name="produkid" id="hidden_produkid" value="'+element.product_id+'" />'+
										'<input type="hidden" name="komisi_reseller" id="hidden_komisi_reseller" value="@'+formatNumber(element.commision_reseller) +' x '+ formatNumber(element.quantity) +' = '+ formatNumber(element.commision_reseller * element.quantity)+'" />'+
										'<input type="hidden" name="komisi_ks" id="hidden_komisi_ks" value="@'+formatNumber(element.commision_ks) +' x '+ formatNumber(element.quantity) +' = '+ formatNumber(element.commision_ks * element.quantity)+'" />'+
										'<input type="hidden" name="status_komisi" id="hidden_status_komisi" value="'+formatString(element.commision_status)+'" />'+
										'<input type="hidden" name="user_id_parent" id="hidden_user_id_parent" value="'+element.user_id_parent+'" />'+
									'</div>'



									)
								
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
	var url = 'http://www.kaffah.biz/reqpost/getdetailtransaction_byreseller/'+blogid		

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
		url = 'http://www.kaffah.biz/reqpost/orderpaginginit_commision/'+blogid+'/s/'+textsearch;
	}
	else if(filterstatus != null && filterstatus != ''){
		url = 'http://www.kaffah.biz/reqpost/orderpaginginit_commision/'+blogid+'/f/'+filterstatus;
	}
	else{	
		url = 'http://www.kaffah.biz/reqpost/orderpaginginit_commision/'+blogid;
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

function searching(blogid,status,limit,page){
	var web_address = 'http://'+window.location.hostname ;
	
	$(document).on('keyup','#search', function(){

		delay(function(){
	      loadorder(blogid,status,limit,page);
	    }, 800 );	
	});		
}

function statusfilter(blogid,status,limit,page){
	/*  ketika kategori di hover */
	$('div.postfilter').hover(function(){
		$(this).children('#postfilter').show();
	}, function(){
		$(this).children('#postfilter').hide();
	});
	
	/* ketika category filternya di klik */
	$(document).on('click','#postfilter ul li a.statusfilter', function(eve){	
		eve.preventDefault();
		var valuea = $(this).attr('name');
		$('#filterstatus').val(valuea);
		filter = $('#filterstatus').val();
		loadorder(blogid,status,limit,page);
		$('em.postfilter').html($(this).html());
		$('#search').val('');
		$('#postfilter').hide();
		$(this).die('click');
		return false;
	});
}

function formatString(string){
	return string.replace(/_/g, " ");
}

function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.")
}

function rekening(blogid,id){
	var vall = $('div.formhidden_'+id);	
	var url = 'http://www.kaffah.biz/req/getsettingconfigstore/'+blogid;
	var selectrekening = '<select name="transfer_destination" id="transfer_destination" class="action edit">'+
			'<option value="0">Pilih rekening</option>';
	
	var destination = getVal('transfer_destination',vall);										
	var config =  JSON.parse($.ajax({
	 type: 'POST',
	 url: url,
	 dataType: 'json',
	 global: false,
	 async:false,
	 success: function(data) {	 		
	 }
	}).responseText);	

	for(var key in config){
		totalrek = config['no_rek'].length;				
		if(key == 'jenis_rekening'){
			var y = 1;
			for(var x = 0;x<totalrek;x++){		
				if(config['jenis_rekening'][x]!=null){		
					var rekk = config['jenis_rekening'][x]+'_'+config['no_rek'][x];					
					if(rekk == destination){
						selectrekening = selectrekening + '<option selected value="'+config['jenis_rekening'][x]+'_'+config['no_rek'][x]+'">'+formatString(config['jenis_rekening'][x])+' - '+config['no_rek'][x]+'</option>';
					}
					else{
						selectrekening = selectrekening + '<option value="'+config['jenis_rekening'][x]+'_'+config['no_rek'][x]+'">'+formatString(config['jenis_rekening'][x])+' - '+config['no_rek'][x]+'</option>';
					}
					
							
					
				}
			}
		}
	}

	selectrekening = selectrekening + '</select>';

	return selectrekening;
}

function getVal(what,val){
	return val.children('#hidden_'+what).val();
}

var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();