<?php
function print_tree($map,$pages,$shift=0){
	if(!empty($map)){
		foreach($map as $section){
			?><option value="<?=$section['page_id']?>"
				<?if(in_array($section['page_id'],$pages))
					echo 'selected';
				?>
			>
			<? for($i=0;$i<$shift;$i++)echo'&nbsp;';?>
			<?=$section['title_in_menu']?></option><?
			print_tree($section['children'],$pages,$shift+5);
		}
	}
}

?>

<h1> <?=$title_h1?></h1>
<? if ($errors) :?>
	<p class='thm-danger'>Заполните все поля</p>
<? endif;?>
<form method='post'>
	<fieldset >
		<legend  > Редактирование меню [<?=$fields['menu_title']?>] </legend>
		<div class='input-block'>
			<div class='input-wrapper'>
				<label for='menu_title'>Название меню</label>
				<input type='text' name='menu_title' id='menu_title' value="<?=$fields['menu_title']?>">
			</div>
			<div class='input-wrapper'>
				<label for='parent_page_id'>Добавить страницы</label>
				<select name='pages[]' id='pages' multiple>
					<?print_tree($map,$fields['pages'])?>
				</select>
			</div>
		</div>
		
	</fieldset>
	
	<div class='btn-block'>
		<input type='submit' value='Сохранить Меню' class='btn btn-success'>
		<input type='reset' value='Сбросить' class='btn btn-danger'>
	</div>
</form>