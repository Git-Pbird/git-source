		<div id='auth_bar'> 
		
			<?php if($authorized):?>
			<img class='floatLeft' id='user_avatar' src='<?php echo $avatar;?>' />
			<?php endif;?>
			
			<div id='user_info'>
				<p id='HRU'> Вы зашли как: <span> <?php echo $alias;?> </span></p>
					<?php if(!$authorized):?>
						<p id = 'login-button'		class='btn btn-default'> Войти  </p>
						<a id = 'register-button'	class='btn btn-default' href='/User/register' > Регистрация </a>
					<?php else:?>
						<p id = 'logout-button' class='btn btn-default'> Выйти  </p>
					<?php endif;?>
				
			</div>

			
			<div id='login-form' class='hide'>
				<input class='tiny' type='text'		placeholder="Введите Ваше имя"		id='login'> </input> <br/>
				<input class='tiny' type='password' placeholder="Введите Ваш пароль"	id='pass'> </input>
					<p class='btn btn-default btn-tiny'	id = 'authorize-button' > Войти </p>
					<p class='back-button btn btn-default btn-tiny'> Отмена </p>
					<p class='btn btn-default btn-tiny'	id = 're-pass-button'  > Забыли пароль? </p>
			</div>
			
			<div id='re-pass-form' class='hide'>
				<input class='tiny' type='text' placeholder="Введите ваш е-mail" id='mail'> </input> <br/>
					<p class='btn btn-default btn-tiny'	id='send-pass' > Отправить </p>
					<p class='back-button btn btn-default btn-tiny'> Отмена </p>
			</div>
		</div>