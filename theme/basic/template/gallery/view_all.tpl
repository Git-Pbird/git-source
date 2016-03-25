<h2><?=$title_h2;?></h2>
<h3> <a href='/Gallery/add' class='btn btn-default'>Создать новую галлерею</a> </h3>

<? if(count($gallery)>0 ):?>
		<h3>Галереи:</h3>
		<ol class='dbl-block'>
			<?	foreach($gallery as $gal): ?>
				<li>
					<a href='/gallery/edit/<?=$gal['gallery_id']?>'><?=$gal['title']?></a>
						<div class='btn-block inline'>
						<a href='/gallery/upload/<?=$gal['gallery_id']?>' class='btn btn-default btn-small'>добавить изображения |</a>
						<a href='/gallery/edit/<?=$gal['gallery_id']?>' class='btn btn-default btn-small'>редактировать галерею |</a>
						<a href='/gallery/images/<?=$gal['gallery_id']?>' class='btn btn-default btn-small'>изменить изображения</a>
						</div>
				</li>
			<?	endforeach;?>
		</ol>
	<? else:?>
		<h3>Нет ни одного меню.</h3>
	<? endif;?>