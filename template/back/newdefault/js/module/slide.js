$(document).ready(function(){
	var blogid = $('#hideblogid').val();
	var limit = $('#limit').val();
	var page = $('#page').val();
	initslide();
	uploadimage(blogid);
	delimage(blogid);
	delslide(blogid);
});

function delslide(blogid){
	
	$(document).on('click', 'a.delbttn', function(eve){
		eve.preventDefault();
		var name = $(this).attr('id').replace('delbtn_','');
		$('#slide_module'+name).remove();
		$('.head_'+name).remove();
		$(this).remove();
	});	
}


function uploadimage(blogid){

	$(document).on('click', 'input.triggchange', function(eve){
		eve.preventDefault();
		var name = $(this).attr('id').replace('change_', '');
		$('#'+name).click();
	});
	
	$(document).on('change', 'input.imagefile', function(eve){
		var name =  $(this).attr('id');
		$('#change_'+name).val($(this).val()).attr('disabled', 'disabled').addClass('disabledtext');
	});
	
	$(document).on('click','input.triggclick', function(eve){
		eve.preventDefault();
		var name = $(this).attr('id').replace('click_', '');
		
		alert('Mohon menunggu, jika terlalu lama loading silahkan refresh halaman ini, atau simpan terlebih dahulu lalu upload kembali...');

		$.ajaxFileUpload({
			url         	: '/reqpost/upload', 
			secureuri      : false,
			fileElementId  : name,
			dataType    	: 'json',
			data        	: {'blogid' : blogid },
			success: function (data){
				if(data.success == 'TRUE'){
					$('#label_'+name).after('<a href="'+data.url+'" class="uploadimg" target="_blank" id="link_'+name+'">'+data.url+'</a> <a href="" class="delimg" id="del_'+name+'">- Hapus</a>');
					$('#change_'+name).remove();
					$('#click_'+name).remove();
					$('#hide_'+name).val(data.url);
				}
			}
		}); 		
	
	});
}

function initslide(){
	$(document).on('click','a#tambahslide', function(eve){
		eve.preventDefault();
		var totalslide = $('.slide_module').length;
		var slidenow = totalslide + 1;
		var position = $('#position').val();
		var slide = $('#'+position).val();
		var atr = slide.split(',');

		if(slide.length > 0){

			$('.slide_wrapper').prepend('<h4 class="head_'+slidenow+'">Slide ke '+slidenow+'</h4> <a href="" id="delbtn_'+slidenow+'" class="delbttn">(klik untuk hapus)</a><div class="slide_module" id="slide_module'+slidenow+'"></div>');

			
			for(var x=0;x<atr.length;x++){
				var singleatr = atr[x].split('=');
				

				if(singleatr[1] == 'iteration'){			
				}

				else if(singleatr[1] == 'textarea'){
					$('.slide_wrapper #slide_module'+slidenow).append('<label class="forframe" for="judul">'+formatString(singleatr[0])+'</label><textarea name="'+singleatr[0]+'[]" class="desc"></textarea>');	
				}

				else if(singleatr[1] == 'text'){
					$('.slide_wrapper #slide_module'+slidenow).append('<label class="forframe" for="judul">'+formatString(singleatr[0])+'</label><input id="judul" name="'+singleatr[0]+'[]" class="input_text" type="text">');	
				}

				else if(singleatr[1] == 'image'){
					$('.slide_wrapper #slide_module'+slidenow).append(
					'<label for="label" class="forframe" id="label_'+singleatr[0]+'_'+singleatr[1]+'_'+slidenow+'">'+formatString(singleatr[0])+'</label>'+
					'<input type="file" style="display:none" class="imagefile" name="userfile" id="'+singleatr[0]+'_'+singleatr[1]+'_'+slidenow+'" />'+
					'<input type="button" value="Pilih Gambar" id="change_'+singleatr[0]+'_'+singleatr[1]+'_'+slidenow+'" class="triggchange btnform" />'+
					'<input type="submit" value="Mulai Upload" id="click_'+singleatr[0]+'_'+singleatr[1]+'_'+slidenow+'" class="triggclick btnform" />'+
					'<input type="hidden" value="" id="hide_'+singleatr[0]+'_'+singleatr[1]+'_'+slidenow+'" name="'+singleatr[0]+'[]" />'+
					'<br />'
						);
				}
				
			}
		}

		else{
			alert('Mohon maaf, Anda tidak bisa menggunakan module slider pada "'+position.replace('_',' ')+'" ini. Silahkan gunakan modul yang lain.');
		}

		$(this).die('click');

	});		
}

function delimage(blogid){
	$(document).on('click', 'a.delimg', function(eve){
		eve.preventDefault();
		var name = $(this).attr('id').replace('del_', '');
		var nameforhide = name.split('_');
		$('#link_'+name).remove();
		$('#change_'+name).remove();
		$('#click_'+name).remove();
		$('#hide_'+name).remove();
		
		$('#label_'+name).after('<input type="file" style="display:none" class="imagefile" name="userfile" id="'+name+'" />'+
								'<input type="button" value="Pilih Gambar" id="change_'+name+'" class="triggchange btnform" />'+
								'<input type="submit" value="Mulai Upload" id="click_'+name+'" class="triggclick btnform" />'+
								'<input type="hidden" id="hide_'+name+'" name="'+nameforhide[0]+'[]" />'+
								'<br />');
		$(this).remove();
	});
}


function formatString(string){
	return string.replace(/_/g, " ");
}