<div class='widget_gallery'>
<?foreach($images as $img):?>
	<div>
		<a href="/<?=IMG_BIG_DIR . $img['path']?>">
			<img src='/<?=IMG_SMALL_DIR . $img['path']?>'>
		</a>
	</div>
<?endforeach;?>
</div>