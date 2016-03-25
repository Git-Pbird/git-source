$(document).ready(function(){
	$(function(){
		$('#tabs-1').show();
		
		$('#items li').click(function(){
			if( $(this).attr('class') == 'active')
			{
				return false;
			}
			var link = $(this).children().attr('href');					//	ссылка на блок текста вкладки для показа
			var prevActive = $('li.active').children().attr('href');	//	ссылка на блок пока еще активной вкладки
			
			$('li.active').removeClass('active');
			$(this).addClass('active');
			
			$(prevActive).fadeOut(300, function(){
				$(link).fadeIn(300);
			});
			return false;
		});
	});
});