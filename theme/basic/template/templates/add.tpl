<h2> <?=$title_h2?></h2>
<? if ($errors) :?>
	<? foreach($errors as $error):?>
		<p class='thm-danger'><?echo $error;?></p>
	<?endforeach;?>
<? endif;?>
<form method='post'>
	<fieldset >
		<legend> добавление шаблона </legend>
		<div class='input-block'>		
			<div class='input-wrapper'>
				<label class='control-label' for='name'>Название шаблона</label>
				<input type='text' name='name' id='name' value="<?=$fields['name']?>">
			</div>
			<div class='input-wrapper'>
				<label for='path'>Выбрать путь</label>
				<select name='path' id='path'>
					<?foreach($templates as $template):?>
						<option value='<?=$template?>'><?=$template?></option>
					<?endforeach;?>
				</select>
			</div>
		</div>		
	</fieldset>
	
		
	</fieldset>
	<div class='btn-block'>
		<input type='submit' value='Создать шаблон' class='btn btn-success'>
		<input type='reset' value='Сбросить' class='btn btn-danger'>
	</div>
</form>