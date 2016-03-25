<h3>Шаблон отображения результатов поиска пользователей</h3>

<?foreach($results as $line):?>
	<?look($line); echo '</hr>';?>
<?endforeach;?>