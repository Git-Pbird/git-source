
<h3>Результаты поиска : <?=$search;?></h3>
<? if ($errors) :?>
	<? foreach($errors as $error):?>
		<p class='thm-danger'><?=$error;?></p>
	<?endforeach;?>
<? endif;?>

<?foreach($templates as $template):?>
	<?=$template;?>
<?endforeach;?>