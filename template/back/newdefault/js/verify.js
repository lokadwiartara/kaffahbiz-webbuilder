$(document).ready(function(){
	var web_address = 'http://'+window.location.hostname ;
	var blogid = $('#hideblogid').val();		

	getaccount(blogid,web_address);
});

function getaccount(blogid,web_address){
	$.ajax('http://www.kaffah.biz/req/getaccount/'+blogid, {
		dataType : 'json',
		type : 'post',
		beforeSend: function(){
			$('div.popup').hide();		 	
		 	$('div.blackbg').show();	
			$('div.loadupload').show();	
		},
		success: function(data){	
			
			var date = data.tanggal_lahir.split('-');
			var fixdate = date[2]+'/'+date[1]+'/'+date[0];					
			var provinsi = data.provinsi;
			var kota = data.kota;			



			$('#formverifikasi #nama_lengkap').val(data.nama_depan+' '+data.nama_belakang);			
			$('#formverifikasi #no_handphone').val(data.handphone);
			$('#formverifikasi #no_telepon').val(data.telephone);
			$('#formverifikasi #provinsi').val(data.provinsi);			
			getprovinsi(provinsi,kota,blogid)
			$('#formverifikasi #alamat').val(data.alamat);
			
			$('#formverifikasi #facebook_id').val(data.facebook);
			$('#formverifikasi input#'+data.jenis_kelamin).attr('checked', true);
						

			$('#formverifikasi #tempat_lahir').val(data.tempat_lahir);
			$('#formverifikasi #day').val(date[2]);
			$('#formverifikasi #month option[value="'+date[1]+'"]').prop('selected', true);	
			$('#formverifikasi #year').val(date[0]);
			
			
			$('#formverifikasi #no_ktp').val(data.noktp);

			$('#formverifikasi #bank').val(data.bank);
			$('#formverifikasi #no_rek').val(data.no_rek);
			$('#formverifikasi #atas_nama').val(data.atas_nama);

			if(data.scanktp != ''){
				$('#formverifikasi #label_img_ktp').after('<a href="'+data.scanktp+'" class="uploadimg" target="_blank" id="link_img_ktp">'+data.scanktp.substring(7,70)+'...</a> <a href="" class="delimg" id="del_img_ktp">- Hapus</a>');
				$('#formverifikasi #img_ktp').remove();
				$('#formverifikasi #hide_img_ktp').val(data.scanktp);
			}

			if(data.scanid != ''){
				$('#formverifikasi #label_img_id').after('<a href="'+data.scanid+'" class="uploadimg" target="_blank" id="link_img_id">'+data.scanid.substring(7,70)+'...</a> <a href="" class="delimg" id="del_img_id">- Hapus</a>');
				$('#formverifikasi #img_id').remove();
				$('#formverifikasi #hide_img_id').val(data.scanid);
			}


			$('div.loadupload').hide();	
			$('div.blackbg').hide();				
		}
	});	
	

	new AjaxUpload('img_ktp', {
	action: '/reqpost/upload',
	name: 'userfile',
	responseType: "json",
	data: {'tag' : 'img_ktp'},
	onSubmit: function(file, extension) {
		alert('Mohon menunggu, jika terlalu lama loading silahkan refresh halaman ini lalu upload kembali...');
		$('div.blackbg').show();
		$('div.loadupload').show();	
		$('div.popup').hide();							
	},
	onComplete: function(file, response) {
		
		$('div.loadupload').hide();	
		if($('#notpopupverify').val() == 0){
			$('div.popup').show();
		}
		else{
			$('div.blackbg').hide();
		}
																					

		if(response.success == 'TRUE'){	
			$('#img_ktp').remove();
			$('br.del_img_ktp').remove();	
			$('#label_img_ktp').after('<a href="'+response.url+'" class="uploadimg" target="_blank" id="link_img_ktp">'+response.url.substring(7,50)+'...</a> <a href="" class="delimg" id="del_img_ktp">- Hapus</a><br class="del_img_ktp" />');							
			$('#hide_img_ktp').val(response.url);
										

		}						
	}
});	


new AjaxUpload('img_id', {
	action: '/reqpost/upload',
	name: 'userfile',
	responseType: "json",
	data: {'tag' : 'img_id'},
	onSubmit: function(file, extension) {
		alert('Mohon menunggu, jika terlalu lama loading silahkan refresh halaman ini lalu upload kembali...');
		$('div.blackbg').show();
		$('div.loadupload').show();	
		$('div.popup').hide();							
	},
	onComplete: function(file, response) {
		
		$('div.loadupload').hide();	
		if($('#notpopupverify').val() == 0){
			$('div.popup').show();
		}
		else{
			$('div.blackbg').hide();
		}


		if(response.success == 'TRUE'){	
			$('#img_id').remove();
			$('br.del_img_id').remove();	
			$('#label_img_id').after('<a href="'+response.url+'" class="uploadimg" target="_blank" id="link_img_id">'+response.url.substring(7,50)+'...</a> <a href="" class="delimg" id="del_img_id">- Hapus</a><br class="del_img_id" />');							
			$('#hide_img_id').val(response.url);
										

		}						
	}
});	

	$(document).on('click', 'a#del_img_id', function(eve){
		eve.preventDefault();
		$('#link_img_id').remove();
		$('#img_id').remove();
		$('#hide_img_id').remove();		
		
		$('#label_img_id').after('<input type="file" class="imagefile imagefileid" name="userfile" id="img_id" />'+
								'<input type="hidden" id="hide_img_id" name="img_id" />');
		$(this).remove();

		new AjaxUpload('img_id', {
			action: '/reqpost/upload',
			name: 'userfile',
			responseType: "json",
			data: {'tag' : 'img_id'},
			onSubmit: function(file, extension) {
				alert('Mohon menunggu, jika terlalu lama loading silahkan refresh halaman ini lalu upload kembali...');
				$('div.blackbg').show();
				$('div.loadupload').show();	
				$('div.popup').hide();							
			},
			onComplete: function(file, response) {
				
				$('div.loadupload').hide();	
						if($('#notpopupverify').val() == 0){
							$('div.popup').show();
						}
						else{
							$('div.blackbg').hide();
						}
																			

				if(response.success == 'TRUE'){	
					$('#img_id').remove();
					$('br.del_img_id').remove();	
					$('#label_img_id').after('<a href="'+response.url+'" class="uploadimg" target="_blank" id="link_img_id">'+response.url.substring(7,50)+'...</a> <a href="" class="delimg" id="del_img_id">- Hapus</a><br class="del_img_id" />');
					
					$('#hide_img_id').val(response.url);
												

				}						
			}
		});			
	});			
}


function getprovinsi(provinsi,kotachoice,blogid) {

 return JSON.parse($.ajax({
     type: 'POST',
     url: 'http://www.kaffah.biz/req/getcity/'+blogid,
     dataType: 'json',
	 data: {provinsi: provinsi},
     global: false,
     async:false,
     success: function(data) {
	 	$('#provinsi_kota').children().remove();
				
		/* get data */
		for(var key in data){
			$('#formverifikasi #provinsi_kota').append('<option value="'+key+'">'+data[key]+'</option>');
			$('#formverifikasi #provinsi_kota option[value="'+kotachoice+'"]').prop('selected', true);
		}
     }
 }).responseText);
}

