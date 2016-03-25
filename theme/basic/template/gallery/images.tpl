<h2> Галерея  - "<?=$gallery['title'];?>"</h2>
<div class='btn-block inline'>
	<a href="/gallery/upload/<?=$gallery['gallery_id'];?>" class='btn btn-default'>
		добавить картинки
	</a>
	<input type='button' id='btn_save' value='Сохранить сортировку' class='btn btn-success'>
</div><span id='msg_save' class='thm-success'> Сохранено ! </span>
	

<? if(count($images) > 0):?>
	<ul id='gallery_sortable'>
	<?foreach($images as $img):?>
		<li class='delimg' id_img="<?=$img['image_id']?>">
			<form method='post'>
				<input type='submit' class='delete' value=''>
				<input type='hidden' name='gallery_id'	value="<?=$img['gallery_id']?>">
				<input type='hidden' name='image_id'	value="<?=$img['image_id']?>">
			</form>
			<div class='btn-block'>
				<a href="/gallery/metaimg/<?=$img['image_id']?>"> редактировать картинку</a>
			</div>
			<img src="<?='/upload/gallery/min/'.  $img['path']?>">
		</li>
	<?endforeach;?>
	</ul>
<?else:?>
	<p>Картинок нет...</p>
	<a href="/gallery/upload/<?=$gallery['gallery_id'];?>" class='btn btn-default'>
		добавить новые ?
	</a>
<?endif;?>