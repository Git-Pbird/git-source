<div class='load'> </div>

<div class = 'nav_bar_numeric'>
	
	<? foreach ($pagination as $title => $href):?>
		<? $addclass = ($href=='')?' cpage':'';?>
		<? $href	 = ($href!='')?" href='{$href}'":'';?>
		<a class='nav_link_numeric <?echo $addclass;?>' <?echo $href;?>> <?echo $title;?> </a>
	<?endforeach;?>
	
</div>

