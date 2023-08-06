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
	editorder(blogid,limit,page);
});

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


	$(document).on('click','#orderlist tbody tr td em.klikdetil', function(eve){
		eve.preventDefault();
		$(this).parents('#orderlist tbody tr td').siblings().children('div.detailpop').fadeOut();
		$(this).parents('#orderlist tbody tr').siblings().children('td').children('div.detailpop').fadeOut();
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
		$.each(transaction, function(index, element) { transaction_id.push(element.transaction_id); });
		gettransactiondetail(transaction_id,blogid,status,limit,page);		
	}


 	$('div.blackbg').hide();	
	$('div.loadupload').hide();

	$(document).on('mouseover','#orderlist tbody tr', function(eve){	
		eve.preventDefault();
		$(this).children('td').children('div.edit').show();
	});

	$(document).on('mouseleave','#orderlist tbody tr', function(eve){	
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

function editorder(blogid,limit,page){
	$(document).on('click', 'a.editorder', function(eve){
		eve.preventDefault();		

		var title = $(this).attr('title');
		var vall = $('div.formhidden_'+title);		
		var reseller = $('#reseller').val();

		$('div.blackbg').show();	
		$('div.loadupload').show();



		if(reseller != ''){
			var transfer_val = 'konfirmasi_transfer';
			var transfer_text = 'Konfirmasi Transfer';			
		}
		else{
			var transfer_val = 'sudah_transfer';
			var transfer_text = 'Sudah Transfer';
		}

		if(getVal('transaction_status',vall) == 'sudah_transfer' && reseller != ''){
			var text_transaction_status = "konfirmasi_transfer"
		}
		else{
			var text_transaction_status = 'sudah_transfer';
		}
		


		$('div.popup').css('width','60%').css('height','70%').css('margin-top','5%');
		$('div.wrappop').html('<h2 class="headpop"></h2><div class="overflowform">'+
			'<form id="formorder_edit" method="post">'+
			'<label for="nama_lengkap">Nama Lengkap</label>'+
			'<input type="text" class="input_text long_text" name="nama_lengkap" id="nama_lengkap" value="'+getVal('nama_lengkap',vall)+'">'+
			'<br>'+	

			'<label for="email">Email</label>'+
			'<input type="text" class="input_text long_text" name="email" id="email" value="'+getVal('email',vall)+'">'+
			'<br>'+		

			'<label for="no_handphone">No. HP</label>'+
			'<input type="text" class="input_text long_text" name="no_handphone" id="no_handphone" value="'+getVal('no_handphone',vall)+'">'+
			'<br>'+

			'<label for="no_telepon">No. Telp</label>'+
			'<input type="text" class="input_text long_text" name="no_telepon" id="no_telepon" value="'+getVal('no_telepon',vall)+'">'+
			'<br>'+	

			'<label for="kota">Kota</label>'+
			'<input type="text" class="input_text long_text" name="kota" id="kota" value="'+formatString(getVal('kota',vall))+'">'+
			'<br>'+	

			'<label for="provinsi">Provinsi</label>'+
			'<input type="text" class="input_text long_text" name="provinsi" id="provinsi" value="'+formatString(getVal('provinsi',vall))+'">'+
			'<br>'+

			'<label for="alamat">Alamat</label>'+
			'<textarea class="long_text" name="alamat" id="alamat">'+getVal('alamat',vall)+'</textarea>'+
			'<br>'+
				
			'<label for="pesanan">Detil Pesanan</label>'+
			'<p class="headtime">Waktu Transaksi : <strong>26/06/2015</strong></p> '+
			'<table class="detail_transaction"><thead><tr><th width="5%">No</th><th width="65%">Nama</th></th><th width="15%">@Harga</th><th width="15%">Jumlah</th></tr></thead><tbody>'+			
			'<tr><td colspan="3" class="right total">Total</td><td class="right total" id="total">'+formatNumber(getVal('total',vall))+'</td></tr>'+
			'<tr class="even"><td colspan="3" class="right">Ongkos Kirim</td><td class="right"><strong id="total_tax">'+formatNumber(getVal('total_tax',vall))+'</strong></td></tr>'+
			'<tr><td colspan="3" class="right total">Total Bayar</td><td class="right total" id="all_total">'+formatNumber(getVal('all_total',vall))+'</td></tr>'+
			'</tbody></table><br>'+

				
			'<label for="status">Status Pesanan</label>'+
			'<select name="status" class="action edit" id="status">'+
			'<option value="pending" selected="selected">Pending</option>'+
			'<option value="'+transfer_val+'">'+transfer_text+'</option>'+
			'<option value="gagal">Gagal</option>'+
			'<option value="sudah_dikirim">Sudah dikirim</option>'+
			'<option value="sudah_direkap">Sudah direkap</option>'+
			'</select>	<br>'+	

			'<label for="all_total">Jumlah Transfer</label>'+
			'<input type="text" class="input_text long_text numeric" name="all_total" id="all_total" value="'+formatNumber(getVal('all_total',vall))+'">'+
			'<br>'+


			'<label for="status">Transfer Ke</label>'+rekening(blogid,title)+'<br>'+


			'<label for="tracking_number">No. Tracking/Resi</label>'+
			'<input type="text" class="input_text long_text" name="tracking_number" id="tracking_number" value="'+getVal('tracking_number',vall)+'">'+
			'<p class="note">No ini digunakan untuk pengecekan keberadaan paket/ status paket</p>'+



			'<input type="hidden" name="transaction_id" value="'+title+'">'+
			'<input type="hidden" name="reg_chc" value="no">'+
			'<input type="submit" value="Update Pesanan!" class="btnAct addSubmit">'+
			'</form>'+

			'</div><br />');
	
		

		$('#status option[value='+text_transaction_status+']').prop('selected', true);			
			

		$('div.wrappop').children('h2.headpop').append('Form Pengeditan Invoice #'+title);

		var tablehidden = $('div.tablehidden_'+title).html();

		$('table.detail_transaction tbody').prepend(tablehidden);

		$('div.loadupload').hide();
		$('div.blackbg').fadeIn(0);
		$('div.popup').slideDown(0);
		$(this).die('click');
	});

	$(document).on('submit', '#formorder_edit', function(eve){
		eve.preventDefault();			
		var status = null;	
		var web_address = 'http://'+window.location.hostname ;
		$.ajax('http://www.kaffah.biz/reqpost/updatetransaction/'+blogid, {
			dataType : 'html',
			type : 'POST',
			data: $('#formorder_edit :input').serialize(),
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

	$('a.close').click(function(eve){
		eve.preventDefault();
		$('div.popup').slideUp('fast');
		$('div.blackbg').fadeOut('fast');
		$('div.wrappop').children('div.overflowform').remove();
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
		url = 'http://www.kaffah.biz/reqpost/getorder/'+blogid+'/'+limit+'/'+offset+'/s/'+textsearch;
	}
	else if(filterstatus != null && filterstatus != ''){
		url = 'http://www.kaffah.biz/reqpost/getorder/'+blogid+'/'+limit+'/'+offset+'/f/'+filterstatus;
	}
	else{
		url = 'http://www.kaffah.biz/reqpost/getorder/'+blogid+'/'+limit+'/'+offset;
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
			$('#orderlist tbody tr').remove();
			if(data != null){
				$.each(data, function(index, element) {
					var datetime = element.transaction_time.split(' ');
					var date = datetime[0].split('-');
					var fixdate = date[2]+'/'+date[1]+'/'+date[0];			

					odd = x % 2;
					if(odd == 1){trclass = 'even';}
					else{trclass = 'odd';}			

						$('#orderlist').find('tbody')
						.append($('<tr>').attr('class', trclass)
							.append(
								$('<td>').html('<input type="checkbox" name="orderid[]" value="'+element.transaction_id+'" class="checkinput" />'),
								$('<td>').attr('class', 'invoice').html('<a id="'+element.transaction_id+'" class="editorder" title="'+element.transaction_id+'" href="#editorder" >'+element.transaction_id+'</a>'+
									'<div class="edit"><a id="'+element.transaction_id+'" class="editorder" title="'+element.transaction_id+'" href="#editorder" >Edit</a> | '+
									'<a id="'+element.transaction_id+'" class="delorder" title="'+element.transaction_id+'" href="#delorder">Hapus</a></div>'+
									'<div class="formhidden_'+element.transaction_id+' hidden">'+
										'<input type="hidden" name="fixdate" id="hidden_fixdate" value="'+fixdate+'" />'+
										'<input type="hidden" name="nama_lengkap" id="hidden_nama_lengkap" value="'+element.nama_lengkap+'" />'+
										'<input type="hidden" name="email" id="hidden_email" value="'+element.email+'" />'+
										'<input type="hidden" name="no_handphone" id="hidden_no_handphone" value="'+element.no_handphone+'" />'+
										'<input type="hidden" name="no_telepon" id="hidden_no_telepon" value="'+element.no_telepon+'" />'+
										'<input type="hidden" name="kota" id="hidden_kota" value="'+element.kota+'" />'+
										'<input type="hidden" name="provinsi" id="hidden_provinsi" value="'+element.provinsi+'" />'+
										'<input type="hidden" name="alamat" id="hidden_alamat" value="'+element.alamat+'" />'+
										'<input type="hidden" name="transaction_status" id="hidden_transaction_status" value="'+element.transaction_status+'" />'+
										'<input type="hidden" name="total" id="hidden_total" value="'+element.total+'" />'+
										'<input type="hidden" name="random" id="hidden_random" value="'+element.random+'" />'+
										'<input type="hidden" name="tax" id="hidden_tax" value="'+element.tax+'" />'+
										'<input type="hidden" name="total_tax" id="hidden_total_tax" value="'+element.total_tax+'" />'+
										'<input type="hidden" name="tax_type" id="hidden_tax_type" value="'+element.tax_type+'" />'+

										'<input type="hidden" name="all_total" id="hidden_all_total" value="'+element.all_total+'" />'+

										'<input type="hidden" name="transfer_destination" id="hidden_transfer_destination" value="'+element.transfer_destination+'" />'+
										'<input type="hidden" name="tracking_number" id="hidden_tracking_number" value="'+element.tracking_number+'" />'+
									'</div>'+
									'<div class="tablehidden_'+element.transaction_id+' hidden">'+
									
									'</div>'
									),

								$('<td>').attr('class', 'nama').html(
										'<h3>'+element.nama_lengkap+'</h3>'+fixdate+'<em class="info_detil normal klikdetil shipdetil">(klik untuk detil)</em>'+
										'<div class="detailpop"><a href="#" class="close">X</a>'+
											'<strong class="name">'+element.nama_lengkap+' (invoice #'+element.transaction_id+')</strong>'+
												'Alamat :<br><strong>'+element.alamat+', '+
												formatString(element.kota)+', '+formatString(element.provinsi)+'</strong><br>'+
												'No HP : <strong>'+formatString(element.no_handphone)+'</strong> <br>'+										
												'No Telp : <strong>'+formatString(element.no_telepon)+'</strong><br>'+										
												'Email : <strong>'+formatString(element.email)+'</strong>'+								
										'</div>'
									),						
								$('<td>').html('<em class="info_detil bold">'+formatString(element.kota)+', <br />'+formatString(element.provinsi)+'</em>'),
								$('<td>').attr('class', 'trx').attr('id', 'transaction_id_'+element.transaction_id).html(
										
										'<em class="transaction_d"><ul class="transdetail"></ul></em>'+
										'<em class="transaction_d normal klikdetil">(klik untuk detil)</em>'+
										'<div class="detailpop"><a href="#" class="close">X</a>'+
										'<strong class="name">'+element.nama_lengkap+' (invoice #'+element.transaction_id+')</strong>'+
										'Melakukan pembelian :'+
										'<ul class="transdetailfull">'+
										
										'</ul>'+'<br>Jumlah : <strong>'+formatNumber(element.total)+'</strong><br>'+
										'Ongkos Kirim : <strong>'+formatNumber(element.total_tax)+'</strong> (JNE REG) <br>'+
										'Angka Unik : <strong>'+formatNumber(element.random)+'</strong><br>'+
										'Total pembayaran : <strong>'+formatNumber(element.all_total)+'</strong><br>'+
										'Waktu Transaksi : <strong>'+fixdate+'</strong><br>'+
										'Status : <strong>'+ formatString(element.transaction_status)+'</strong>'+
										'</div>'

									),
								$('<td>').html(formatNumber(element.all_total)),
								$('<td>').html(formatString(element.transaction_status))
								
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
	var url = 'http://www.kaffah.biz/reqpost/getdetailtransaction/'+blogid		

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

	        	$('#orderlist tbody tr td#transaction_id_'+element.transaction_id+' ul.transdetail').append('<li>'+formatString(element.name)+'<strong> x '+element.quantity+'</strong> </li>');
	        	$('#orderlist tbody tr td#transaction_id_'+element.transaction_id+' ul.transdetailfull').append('<li>'+formatString(element.name)+'<strong> x '+element.quantity+'</strong> : <strong>Rp  '+formatNumber(element.price)+'</strong>'+option+'</li>');
	        		        	
	        	if(transaction_id != element.transaction_id){
	        		x = 1;
	        	}
	        	else{
	        		x++;
	        	}

	        	$('div.tablehidden_'+element.transaction_id).append('<tr class="even"><td>'+x+'</td><td><strong>'+formatString(element.name)+'</strong><strong> x '+element.quantity+'</strong> : <strong>Rp  '+formatNumber(element.price)+'</strong> '+option+'</td><td class="right">'+formatNumber(element.price)+' </td><td class="right"><strong>'+formatNumber(element.sub_total)+'</strong></td></tr>');


	        	transaction_id = element.transaction_id;
	        	
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
		url = 'http://www.kaffah.biz/reqpost/orderpaginginit/'+blogid+'/s/'+textsearch;
	}
	else if(filterstatus != null && filterstatus != ''){
		url = 'http://www.kaffah.biz/reqpost/orderpaginginit/'+blogid+'/f/'+filterstatus;
	}
	else{	
		url = 'http://www.kaffah.biz/reqpost/orderpaginginit/'+blogid;
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

var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();