
<?foreach($products as $product):?>
	<p><a href='<?=DOMEN.'p/'.$product['url']?>'><?=$product['title_h1']?> </a></p>
<?endforeach;?>