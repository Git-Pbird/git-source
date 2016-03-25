<!DOCTYPE html>
<html>
	<head>
		<?php
		foreach ($script_up as $script)	echo "\n\t<script src='{$script}' > </script>";
		foreach ($style_up as $style)	echo "\n\t<link  href='{$style}' rel='stylesheet'/>";
		?>
		
		<meta charset="<?=$charset?>"/>
		<meta http-equiv="Content-Type" content="text/html; charset=<?=$charset?>"/>
		<meta http-equiv="Content-language" content="ru-RU"/> 
		<meta http-equiv="Cache-Control" content="public"/>
		<!--------------------------------------------------	
		<meta http-equiv="Refresh" content="4, URL=http://"/>
		<meta http-equiv="Pragma" content="NO-CACHE"/> 
		<meta http-equiv="expires" content="Sun, 01 Jan 2013 07:01:00 GMT"/>
		---------------------------------------------------->
		<meta name="Author"	content="<?=AUTHOR?>"/>
		<meta name="Robots"	content="index, follow"/>
		<meta name="viewport"	content="<?=$viewport?>"/>
		<meta name="description"content="<?=$description?>" />
		<meta name="keywords"	content="<?=$keywords?>" />
		<title><?=$title?></title>
	</head>

<body>
	<!----------------------Top block (start)---------------->
	<div id='header_line'>
		<div class='floatLeft logo_block'>
			<a href='/' alt="<?=$title?> :: Компания малявочка">
				<img src='<?=DOMEN.THEMEDIR.$theme_name.'/img/logo.jpg'?>' />
			</a>
		</div>
		<h1><?=$title?></h1>
	</div>
	
	<nav class='dropdown lightgrey_menu'>
		<ul>
			<li> <a>Наполнение сайта</a>
				<ul>
					<li> <a>Редактор страниц</a>
						<ul>
							<li><a href='/Editor/add'>добавить</a></li>
							<li><a href='/Editor/view/bytree'>просмотреть все (древо)</a></li>
							<li><a href='/Editor/view/bylist'>просмотреть все (таблица)</a></li>
						</ul>
					</li>
					<li> <a>Редактор Меню</a>
						<ul>
							<li><a href='/Menu/add'>добавить</a></li>
							<li><a href='/Menu/view'>редактировать</a></li>
						</ul>
					</li>
					<li> <a>Редактор галереи</a>
						<ul>
							<li><a href='/Gallery/add'>добавить</a></li>
							<li><a href='/Gallery/view'>редактировать</a></li>
						</ul>
					</li>
					<li> <a>Редактор товара</a>
						<ul>
							<li><a href='/Product/add'>добавить</a></li>
							<li><a href='/Product/del'>--------</a></li>
							<li><a href='/Product/view'>редактировать</a></li>
						</ul>
					</li>
				</ul>
			</li>
			
			<li> <a>Авторизация</a>
				<ul>
					<li> <a>Пользователи</a>
						<ul>
							<li><a href='/Users/add'>добавить</a></li>
							<li><a href='/Users/view'>редактировать</a></li>
						</ul>
					</li>
				</ul>
			</li>
			<li> <a>Настройки сайта</a>
				<ul>
					<li> <a>Шаблоны</a>
						<ul>
							<li><a href='/Templates/add'>добавить</a></li>
							<li><a href='/Templates/view'>редактировать</a></li>
						</ul>
					</li>
				</ul>
			</li>
		</ul>
	</nav>
	<!----------------------Top block (end)------------------>
	<!-------------------Center block (start)---------------->
	<div id='center_line'>
		<?=$content?>
	</div>
	<!-------------------Center block (end)------------------>
	<!-----------------Bottom block (start)------------------>
	<div id='bottom_line'>3</div>
	<!-------------------Bottom block (end)------------------>
</body>

<bottom>
<?php
foreach ($script_bt as $script)	echo "\n\t<script src='{$script}' > </script>";
foreach ($style_bt as $style)	echo "\n\t<link  href=\"{$style}\" rel='stylesheet'/>";
?>
</bottom>