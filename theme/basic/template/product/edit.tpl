<?php
function print_tree($map,$page_id,$shift=0){
	if(!empty($map)){
		foreach($map as $section){?>
			<option value="<?=$section['page_id']?>" 
				<?	if($page_id == $section['page_id']) echo 'selected';?>
			>
				<? for($i=0;$i<$shift;$i++)echo'&nbsp;';?>
				<?=$section['title_in_menu']?>
			</option>
			<?print_tree($section['children'],$page_id,$shift+5);
		}
	}
}
?>

<h1> <?=$title_h1?></h1>
<? if ($errors) :?>
	<? foreach($errors as $error):?>
		<p class='thm-danger'><?echo $error;?></p>
	<?endforeach;?>
<? endif;?>
<form method='post'  class='form_sort' >
	<fieldset >
		<legend  > Адресс страницы </legend>
		<div class='input-block'>
			<div class='input-wrapper'>
				<label for='parent_page_id'>Выбрать родителя</label>
				<select name='parent_page_id' id='parent_page_id'>
					<option value='0'>Без раздела</option>
					<?print_tree($map,$fields['parent_page_id'])?>
				</select>
			</div>

			<div class='input-wrapper'>
				<label for='url'>Адресс страницы </label>
				<span id='url_place'><?=DOMEN;?></span>
				<input type='text' name='url' id='url' value="<?=$fields['url']?>">
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
		</div>
	</fieldset>
	
	<fieldset >
		<legend> Редактирование заголовков </legend>
		<div class='input-block'>		
			<div class='input-wrapper'>
				<label class='control-label' for='title_on_page'>Заголовок страницы</label>
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
		<legend> Содержание страницы </legend>
		<div class='input-block'>		
			<div class='input-wrapper'>
				
				<textarea class='editme' rows='5' name='content' id='content' ><?=$fields['content']?></textarea>
			</div>
		</div>		
	</fieldset>
	
	<fieldset >
		<legend> Параметры страницы </legend>
		<div class='input-block'>		
			<div class='input-wrapper'>
				<label class='control-label' for='active'>Показывать страницу</label>
				<input type='checkbox' name='active' id='active' <?if(isset($fields['active']) AND $fields['active']!=0) echo 'checked';?>>
			</div>
			<!-- <div class='input-wrapper'>
				<label for='menu_id'>Выбрать меню</label>
				<select name='menu_id' id='menu_id'>
					<option value='0'>Без меню</option>
					<?#foreach($menu as $item):?>
					<option value='<?#=$item['menu_id']?>' <?#if($item['menu_id']==$fields['menu_id'])echo 'selected'; ?>><?#=$item['menu_title']?></option>
					<?#endforeach;?>
				</select>
			</div> -->
			<!-- <div class='input-wrapper'>
				<label for='menu_type'>Использовать меню</label>
				<select name='menu_type' id='menu_type'>
					<option value='0'>Без меню</option>
				</select>
			</div> -->
			<div class='input-wrapper'>
				<label for='menus'>Выбрать меню</label>
				<select name='menus[]' id='menus' multiple>
					<option value='0' <?if(in_array(0,$menus))echo 'selected'; ?>>Иэрархическое (дочернии страницы)</option>
					<?foreach($menu as $item):?>
					<option value='<?=$item['menu_id']?>' <?if(in_array($item['menu_id'],$menus))echo 'selected'; ?>><?=$item['menu_title']?></option>
					<?endforeach;?>
				</select>
			</div>
		</div>	
	</fieldset>
	<fieldset >
		<legend> Сортировка дочерних элементов</legend>
		<ul class='sortable'>
			<?	foreach($children as $one): ?>
				<li page_id='<?=$one['page_id']?>'><?=$one['title_in_menu']?></li>
			<?	endforeach;?>
		</ul>
		<input type='hidden' name='pages' value="" />
	</fieldset>
	
	<div class='btn-block'>
		
		<input type='button' name='go' value='Сохранить' class='btn btn-success'>
		<input type='reset' value='Сбросить' class='btn btn-danger'>
	</div>
</form>