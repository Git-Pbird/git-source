<h1><?=$title_h1;?></h1>

<div>
	<?php
		function print_map($map){
			if(!empty($map)){
				echo "<ul class='map'>";
				foreach($map as $page){
					echo "<li><a href=\"/Editor/edit/{$page['page_id']} \">{$page['title_in_menu']}</a>";
					print_map($page['children']);
					echo '</li>';
				}
				echo '</ul>';
			}
		}
		print_map($map);
	?>
</div>
<br/>
	<p>	<a class="btn btn-default" href="/Editor/add/">Добавить новую страницу &raquo;</a></p>
		<a class="btn btn-default" href="/Editor/view/bylist"> Перейти к списку страниц</a>