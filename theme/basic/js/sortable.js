$(document).ready(function(){
	$('.sortable').sortable();
	$('.form_sort input[name=go]').click(function(e){
		var pages = [];
		$('.sortable li').each(function(){
			pages.push($(this).attr('page_id'));
		});
		$('input[name=pages]').val(pages.toString());
		$('.form_sort').submit();
	});
});