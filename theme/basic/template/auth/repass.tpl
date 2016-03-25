<p> Восcтановления пароля </p>
<?php if( $result ) :?>
	<p class = 'error' >
	<?php echo $result;?>
	</p>
	
<?php else:  ?>
	<div >
		<form action='/Auth/setNewPass' method='POST' id='new-pass-form'>
		<input type='text' id='new_pass' class='b-input' name='newPass' placeholder="Введите ваш новый пароль" > </input> <br/>
		<input type='hidden' id='hash' name='hash' value='<?php echo $hash;?>'> </input> <br/>
		<p><input type='submit' class='b-button' id='new-pass-button' value='Установить новый пароль' > </input></p>
		</form>
	</div>
<?php endif; ?>