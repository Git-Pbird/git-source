
<h1> <?=$title_h1?></h1>
<? if ($errors) :?>
	<? foreach($errors as $error):?>
		<p class='thm-danger'><?echo $error;?></p>
	<?endforeach;?>
<? endif;?>
<form method='post'>
	<fieldset >
		<legend  > Адресс страницы </legend>
		<div class='input-block'>
			

			<div class='input-wrapper'>
				<label for='url'>Адресс страницы </label>
				<span id='url_place'><?=DOMEN;?></span>
				<input type='text' name='url' id='url' value="<?=$fields['url']?>">
				<span class='hint'>*оставьте пустым для автоматической генерации</span>
			</div>
		</div>
	</fieldset>
	
	<fieldset >
		<legend> Мета блок </legend>
		<div class='input-block'>
			<div class='input-wrapper'>
				<label class='control-label' for='metakey'>Ключевые слова</label>
				<input type='text' name='keyword' id='metakey' value="<?=$fields['keyword']?>">
			</div>
			<div class='input-wrapper'>
				<label class='control-label' for='metadescription'>Описание (снипет)</label>
				<input type='text' name='description' id='metadescription' value="<?=$fields['description']?>">
			</div>
			<div class='input-wrapper'>
				<label class='control-label' for='marking'>Артикуль</label>
				<input type='text' name='marking' id='marking' value="<?=$fields['marking']?>">
			</div>
		</div>
	</fieldset>
	
	<fieldset >
		<legend> Редактирование заголовков </legend>
		<div class='input-block'>		
			<div class='input-wrapper'>
				<label class='control-label' for='title_on_page'>Заголовок страницы **</label>
				<input type='text' name='title_on_page' id='title_on_page' value="<?=$fields['title_on_page']?>">
				
			</div>
			<div class='input-wrapper'>
				<label  for='menuheader'>Заголовок меню</label>
				<input type='text' name='title_in_menu' id='menuheader' value="<?=$fields['title_in_menu']?>">
			</div>
			<div class='input-wrapper'>
				<label class='control-label' for='h1header'>Заголовок H1 на странице</label>
				<input type='text' name='title_h1' id='h1header' value="<?=$fields['title_h1']?>">
			</div>
		</div>		
	</fieldset>
	
	<fieldset >
		<legend> Формирование цены </legend>
		<div class='input-block'>		
			<div class='input-wrapper'>
				<label class='control-label' for='price_active'>текущая цена товара</label>
				<input type='text' name='price_active' id='price_active' value="<?=$fields['price_active']?>">
			</div>
			
		</div>		
	</fieldset>
	
	<fieldset >
		<legend> Текстовое содержание </legend>
		<div class='input-block'>		
			<div class='input-wrapper'>

				<textarea class='editme'  rows='5' name='content' id='content' ><?=$fields['content']?></textarea>
				</div>
		</div>		
	</fieldset>
	
	<fieldset >
		<legend> Параметры отображения товара </legend>
		<div class='input-block'>	
			<div class='input-wrapper'>
				<label for='template_id'>Выбрать шаблон отображения</label>
				<select name='template_id' id='template_id'>
					<?foreach($templates as $template):?>
					<option value='<?=$template['template_id']?>' <?if ($template['template_id'] == $template_id)echo 'selected'; ?>><?=$template['name']?></option>
					<?endforeach;?>
				</select>
			</div><br/>
			
			<div class='input-wrapper'>
				<label class='control-label' for='active'>Показывать товар</label>
				<input type='checkbox' name='active' id='active' <?if(isset($fields['active'])) echo 'checked';?>>
			</div>
			
			
		</div>
		
	</fieldset>
	<div class='btn-block'>
		<input type='submit' value='Добавить товар' class='btn btn-success'>
		<input type='reset' value='Сбросить' class='btn btn-danger'>
	</div>
</form>