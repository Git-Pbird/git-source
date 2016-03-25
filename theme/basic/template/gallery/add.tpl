<h2> <?=$title_h2?></h2>
<? if ($errors) :?>
	<? foreach($errors as $error):?>
		<p class='thm-danger'><?echo $error;?></p>
	<?endforeach;?>
<? endif;?>
<form method='post'>
	<fieldset >
		<legend> Редактирование заголовков </legend>
		<div class='input-block'>		
			<div class='input-wrapper'>
				<label class='control-label' for='title'>Название галереи</label>
				<input type='text' name='title' id='title' value="<?=$fields['title']?>">
			</div>
			<!-- <div class='input-wrapper'>
				<label  for='sys_name'>Имя в системе (короткое!)</label>
				<input type='text' name='sys_name' id='sys_name' value="<?=$fields['sys_name']?>">
			</div> -->
		</div>		
	</fieldset>
	
		
	</fieldset>
	<div class='btn-block'>
		<input type='submit' value='Создать галерею' class='btn btn-success'>
		<input type='reset' value='Сбросить' class='btn btn-danger'>
	</div>
</form>