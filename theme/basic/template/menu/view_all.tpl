<h1><?=$title_h1;?></h1>
<h2> <a href='/Menu/add' class='btn btn-default'>Создать меню</a> </h3>

	<? if(count($menu)>0 ):?>
		<h3>Меню:</h3>
		<ol class='dbl-block'>
			<?	foreach($menu as $one): ?>
				<li>
					<a href='/menu/edit/<?=$one['menu_id']?>'><?=$one['menu_title']?></a>
					<!-- <div class='btn-block inline'> -->
						<a href='/menu/sorting/<?=$one['menu_id']?>' class='btn btn-default btn-small'>Сортировка</a>
					<!-- </div> -->
				</li>
			<?	endforeach;?>
		</ol>
	<? else:?>
		<h3>Нет ни одного меню.</h3>
	<? endif;?>
