$(document).ready(function(){
	var gallery_id = $('input[name=gallery_id]').val();
	var saved = true;
	
	$('#msg_save').hide();
	$('#btn_save').hide();
	
	
	$('#gallery_sortable').sortable({
		sort:function(){
			$('#msg_save').hide();
			$('#btn_save').fadeIn(500);
			saved = false;
		}
	});
	
	$('#btn_save').click(function(e){
		var images = [];
		$('#gallery_sortable li').each(function(){
			images.push($(this).attr('id_img'));
		});
		
		$.post('/Ajax/sortImages',{gallery_id:gallery_id,images:images},
				function(data){
					$('#msg_save').fadeIn(500);
					$('#btn_save').hide();
					saved = true;
				});
	});
	
	$('a').click(function(e){
		if(!saved && !confirm('Сортировка не сохранена. Точно уйти ?')){
			e.preventDefault();
		}			
	});
	
});