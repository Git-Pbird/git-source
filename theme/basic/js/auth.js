$(document).ready(function(){

/*****************************************************************************/

$(function(){
	$('#login-button').click(function(){
		$('#user_info').fadeOut(500,function(){
			$('#login-form').fadeIn(500);
		});
	});
	$('.back-button').click(function(){
		var hide = $(this).closest('div').attr('id');
		$('#'+hide).fadeOut(500,function(){
			$('#user_info').fadeIn(500);
		});
	});
	$('#re-pass-button').click(function(){
		$('#login-form').fadeOut(500,function(){
			$('#re-pass-form').fadeIn(500);
		});
	});

	
	$('#logout-button').click(function(){
		$.ajax({
			url: '',
			type: 'POST',
			data: {logout:true},
			success:function(res){
				$('#mes-edit').text(res).delay(300).fadeIn(500,function(){
					$('#mes-edit').delay(500).fadeOut(300,function(){
						window.location = location.href;
					});
				})
			}
		});
	});
	
	
	$('#authorize-button').click(function(){
		var login,pass;
		login = $('#login-form > #login').val();
		pass = $('#login-form > #pass').val();
					
		$.ajax({
			url: '',
			type: 'POST',
			data: {login:login, pass:pass},
			success:function(res){
				$('#mes-edit').text(res).delay(300).fadeIn(500,function(){
					$('#mes-edit').delay(500).fadeOut(300,function(){
						window.location = location.href;
					});
				})
			}
		});
	});
	
	$('#send-pass').click(function(){
		var mail;
		mail = $('#re-pass-form > #mail').val();
		$.ajax({
			url: '',
			type: 'POST',
			data: {mail:mail, sendPass:true},
			success:function(res){
				$('#mes-edit').text(res).delay(300).fadeIn(500,function(){
					$('#mes-edit').delay(500).fadeOut(300,function(){
						window.location = location.href;
					});
				})
			}
		});
	});
	
	
	/**************/
		$('#in-register-button').hide();
		$('#register-form>p>input').blur(function(){
			var curItem	= $(this).attr('id');
			var value	= $.trim($(this).val());
			var parentP = $(this).closest('p');
			var span	= parentP.children('span');
			if(value){
				$(this).removeClass('incorrect').addClass('correct');
				span.removeClass('incorrect').addClass('correct');
				span.html(' Вроде верно... ');
			}else{
				$(this).removeClass('correct').addClass('incorrect');
				span.removeClass('correct').addClass('incorrect');
				span.html(' Что то тут не так ... ');
				if(curItem == 'email' || curItem == 'login' || curItem == 'password'){
					span.html('Обязательно к заполнению ! ');
				}
			}
			var login = $.trim($('#login_reg').val());
			var passw = $.trim($('#password_reg').val());
			var email = $.trim($('#email_reg').val());
			if(login && passw && email){
				$('#in-register-button').fadeIn(300);
			}else{
				$('#in-register-button').hide();
			}
		});
		
		
});
	

	

});