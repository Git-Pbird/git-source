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
		<meta name="keywords"	content="<?=$keyword?>" />
		<title><?=$title?></title>
	</head>

<body>
<div id='loader'><span></span></div>
<div id='mes-edit'></div>
	<!----------------------Top block (start)---------------->
	<div id='header_line'>
		<div class='floatLeft logo_block'>
			<a href='/admin' alt="<?=$title?> :: Компания малявочка">
				<img src='<?=DOMEN.THEMEDIR.$theme_name.'/img/logo.jpg'?>' />
			</a>
		</div>
		<div class='floatRight'><?=$auth_bar;?></div>
		<h1><?=$title;?></h1>
		
	</div>

	<nav class='dropdown lightgrey_menu'>
		<ul>
		<?foreach($top_menu as $item):?>
			<li <?if($item['sublight']==true) echo "class='sublight'";?>>
				<a href='/<?=$item['full_cach_url']?>'> <?=$item['title_in_menu']?></a>
			</li>
		<?endforeach;?>
		<li>
			<?if(isset($search_box)) echo $search_box;?>
		</li>
		</ul>
		
	</nav>
	<!----------------------Top block (end)------------------>
	<!-------------------Center block (start)---------------->
	<div id='center_line'>
		<div class='floatLeft  menu_block'>
			<?if(!empty($side_menu)):?>
			<img src='<?=DOMEN.'upload/mobile-menu-icon.png'?>'/><p>Меню сайта</p>
				<?foreach($side_menu as $one_menu):?>
					<ul> <p><?=$one_menu[0]['menu_title']?></p>
						<?foreach($one_menu as $item):?>
						<li <?if($item['sublight']==true) echo "class='sublight'";?>>
							<a href='/<?=$item['full_cach_url']?>'> <?=$item['title_in_menu']?></a>
						</li>
						<?endforeach;?>
					</ul>
				<?endforeach;?>
			<?endif;?>
		</div>
		
		<div class='content_block'>
			<?=$content?>
		</div>
	</div>
	<!-------------------Center block (end)------------------>
	<!-----------------Bottom block (start)------------------>
	<div id='bottom_line' class='clear'>3</div>
	<!-------------------Bottom block (end)------------------>
</body>

<bottom>
<?php
foreach ($script_bt as $script)	echo "\n\t<script src='{$script}' > </script>";
foreach ($style_bt as $style)	echo "\n\t<link  href=\"{$style}\" rel='stylesheet'/>";
?>
</bottom>