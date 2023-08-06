$(document).ready(function(){	
	updateqty();
	removeqty();
	jnetax();
	getcity();
	initpopup();
	showdetail();
	removetransaction();
	searching();
	menudropdown();	
});


function menudropdown(){
	$('#dropddown_module').change(function(eve){
		var valurl = $(this).val();
		window.location = valurl;

	});
}

function removetransaction(){
	$('a.reject_trasanction').click(function(eve){
		eve.preventDefault();		
		var status = window.confirm("Apakah Anda akan menggagalkan transaksi ?");
		if(status == true){
			var id = $(this).attr('id');			
			/* disinilah di hapusnya */

			$.ajax('/req/deltransaction/', {
				dataType : 'html',
				type : 'post',
				data : {transaction_id: id},
				beforeSend: function(){
				},
				success: function(data){				
					if(data == true){
						$(this).parents('tr').remove();
						location.reload();						
					}
				}
			});	
	
		}

	});
}

function searching(){
	$('.kaffah_search_form').submit(function(){
		var action = $(this).attr('action');
		
		var cari = $('.kaffah_search_keyword').val();
		var values = cari.split(' ');
		
		for(x=0;x<values.length;x++){
			var cari = cari.replace(' ','_');
		}
		
		var fix_action = action.replace('/','/pencarian/'+cari);
		
		$('.kaffah_search_form').attr('action', fix_action);
		return;
	});
}

function showdetail(){
	$('a.show_list').click(function(eve){
		eve.preventDefault();
		$(this).siblings('div.detail_order_list').slideToggle('fast');
	});
}

function initpopup(){
	$('body .popup_dialog').remove();
	$('body').prepend('<div class="wrap_dialog"><div class="popup_dialog"></div></div>');
}

function getcity(){
	$('#provinsi').change(function(){
		var provinsi = $(this).val();
		
		/* get ajax from provinsi yang di klik */
		$.ajax('/req/getcity/', {
			dataType : 'json',
			type : 'post',
			data : {provinsi: provinsi},
			beforeSend: function(){
				$('#provinsi_kota').addClass('loading').text('');

			},
			success: function(data){				
				/* remove provinsi kota form */
				$('#provinsi_kota').removeClass('loading');
				$('#provinsi_kota').children().remove();
				
				/* get data */
				for(var key in data){
					$('#provinsi_kota').append('<option value="'+key+'">'+data[key]+'</option>');
				}	
			}
		});	
	
	});	
}

function jnetax(){
	var web_address = 'http://'+window.location.hostname ;
	$('#jnetax').keyup(function(){
		var kec = $(this).val();
		var x = 0;



		delay(function(){
			var kota_ongkir = getsetting('kota_ongkir');
			$('#uljne li').remove();
			$.getJSON( web_address+"/assets/json/"+kota_ongkir.detail+".json", function( data ) {
				$.each(data, function(i, v) {
			        if (v.kecamatan.search(new RegExp(kec,"i")) != -1 ) {
				        if(x < 20){				        	
				        	$('#uljne').append('<li>'+v.kecamatan+'</li>');
				        }

				        else{
				        	return 0;
				        }

			           	x += 1;

			        }			        				        

	   			 });
			});

			$('.jnelisttax').fadeIn('fast');
	    }, 900 );	
	});

	$('.jnetaxdetail table tbody').delegate("tr td a.choice_tax", "click", function(eve){
		eve.preventDefault();
		var tax = $(this).attr('title').split('_');		
		
		/* ongkir kirim */
		$.ajax('/reqpost/jnefixtax', {
			dataType : 'json',
			type : 'POST',
			data: {jne:tax[0], kec:tax[1], price:tax[2]},
			beforeSend: function(){
			},
			 
			success: function(msg){	
				
				$('td.jne_cost').html('Rp '+(msg.totalongkir/1000).toFixed(3)).fadeOut().fadeIn();
				$('h4.sum_product').fadeOut().text('Rp '+(msg.total_transfer/1000).toFixed(3)).fadeIn();			
			},
		});		

		$('tr.trtaxdetail').slideUp('fast');	
		$('.jnetaxdetail').slideUp('fast');				
	});

	$( "#uljne" ).delegate( "li", "click", function() {
		var kecamatanval = $(this).html();
		var x = 0;
		$('#jnetax').val(kecamatanval);
		$('.jnetaxdetail table tbody tr').remove();


		var kota_ongkir = getsetting('kota_ongkir');
		
		$.getJSON( web_address+"/assets/json/"+kota_ongkir.detail+".json", function( data ) {
			$.each(data, function(i, v) {
		        if (v.kecamatan.search(new RegExp(kecamatanval,"g")) != -1 ) {		        			       
		        	 if((x < 2) && (v.kecamatan == kecamatanval)){
		        	 	$('#kecamatanjne').text(v.kecamatan);

		        	 	$('.jnetaxdetail table tbody')
		        	 	.append(
		        	 		'<tr><td>Reguler (REG)</td><td>1000 gr</td><td>'+(v.tarif_reg/1000).toFixed(3)+'</td><td><a href="" class="choice_tax" name="JNE_REG" title="REG_'+v.kecamatan+'_'+v.tarif_reg+'">Pilih JNE REG</a></td></tr>'
		        	 		+ '<tr class="even"><td>Ongkos Kirim Ekonomis (OKE)</td><td>1000 gr</td><td>'+(v.tarif_oke/1000).toFixed(3)+'</td><td><a href="" name="JNE_OKE" class="choice_tax" title="OKE_'+v.kecamatan+'_'+v.tarif_oke+'">Pilih JNE OKE</a></td></tr>'		        	 		
		        	 		);

		        	 	if(v.tarif_yes > 0){
		        	 		$('.jnetaxdetail table tbody').append('<tr><td>Yakin Esok Sampai (YES)</td><td>1000 gr</td><td>'+(v.tarif_yes/1000).toFixed(3)+'</td><td><a href="" name="JNE_YES" title="YES_'+v.kecamatan+'_'+v.tarif_yes+'" class="choice_tax">Pilih JNE YES</a></td></tr>');
		        	 	}
		        	 }

			        else{
			        	return 0;
			        }	

			        x += 1;	        	 
		        }		


	          		        	        				        

   			 });
		});
	
		$('tr.trtaxdetail').show();
		$('.jnetaxdetail').slideDown('fast');
		$('.jnelisttax').fadeOut('fast');
	});	
}

function updateqty(){

	$('.qtyinput').keyup(function(){
		
		var rowid = $(this).attr('id');		
		var val = $(this).val();
		var delay=1000;

		$.ajax('/reqpost/updateqty', {
			dataType : 'json',
			type : 'POST',
			data: {rowid:rowid, qty:val},
			beforeSend: function(){
			 	// $('#bodywrap').css('background', '#fff url("'+web_address+'/assets/images/ajax-loader.gif") no-repeat center 55px');
			},
			success: function(msg){				
				$('#'+rowid).parent('td').siblings('td.total_product').text(msg.subtotal).fadeOut().fadeIn();
				setTimeout(function(){
					$('#jnetax').val('');
					$('td.jne_cost').html('Rp 0.00').fadeOut().fadeIn();					
				  	$('h4.sum_product').fadeOut().text(msg.total).fadeIn();
				}, delay); 	

				initsession(false);
							
			},
		});				
	});
}

function removeqty(){

	$('.delcart').click(function(eve){
		eve.preventDefault();
		$(this).parents('table tr').hide('slow');

		var rowid = $(this).attr('name');		
		var delay=1000;

		$.ajax('/reqpost/updateqty', {
			dataType : 'json',
			type : 'POST',
			data: {rowid:rowid, qty:0},
			beforeSend: function(){
			 	// $('#bodywrap').css('background', '#fff url("'+web_address+'/assets/images/ajax-loader.gif") no-repeat center 55px');
			},
			success: function(msg){				
				 $('h4.sum_product').fadeOut().text(msg.total).fadeIn();
				 initsession(false);

			},
		});		

	});
}

var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();

function getallsession() {
	var web_address = 'http://'+window.location.hostname ;
	 return JSON.parse($.ajax({
	     type: 'GET',
	     url: web_address+'/req/getallsession',
	     dataType: 'json',
	     global: false,
	     async:false,
	     success: function(data) {
	     	
	        return data;
	     }
	 }).responseText);
}


function getsetting(detail) {
	var web_address = 'http://'+window.location.hostname ;
	 return JSON.parse($.ajax({
	     type: 'GET',
	     url: web_address+'/req/getsetting/'+detail,
	     dataType: 'json',
	     global: false,
	     async:false,
	     success: function(data) {
	     	
	        return data;
	     }
	 }).responseText);
}
