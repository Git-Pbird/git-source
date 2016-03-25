<h1><?=$title_h1;?></h1>

<ul class='sortable'>
	<?	foreach($menu as $one): ?>
		<li page_id='<?=$one['page_id']?>'><?=$one['title_in_menu']?></li>
	<?	endforeach;?>
</ul>

<form class='form_sort' method='post'>
	<input type='hidden' name='pages' value="" />
	<input type='hidden' name='menu_id' value="<?=$menu_id?>" />
	<input type='button' name='go' value='Сохранить' class='btn btn-success'/>
</form>