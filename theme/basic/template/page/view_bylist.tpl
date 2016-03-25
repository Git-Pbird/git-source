
<div>
	<h2>Список страниц (<?=$count?>)</h2>
	<table class="table table-hover table-bordered">
	<thead>
	<tr>
		<th class="numberlist">№</th>
		<th> Заголовок</th>
		<th> Имя в меню</th>
		<th> Просмотреть</th>
		<th> Редактировать</th>
		<th> Удалить</th>
		<th> Статус</th>
	</tr>
	</thead>
	<tbody>
		<? foreach ($map as $page): $i++; ?>
			<tr>
			<? $id=$page['page_id'] ?>
				<td><?=$id?></td>
				<td><?=$page['title_on_page'] ?></td>
				<td><?=$page['title_in_menu'] ?></td>
				<td><a href="<?=DOMEN.'page/'.$page['full_cach_url'] ?>" target="_blanc" class='thm-default'>
					<img class='icon-xsmall' src='<?=DOMEN.THEMEDIR.'basic/img/arr-blue.png'?>'>Просмотреть</a>
				</td>
				<td><a href="/Editor/edit/<?=$id ?>"	  class='thm-warning'> Редактировать 
					<img  class='icon-xsmall' src='<?=DOMEN.THEMEDIR.'basic/img/edit.png'?>'></a>
				</td>
				<td><a href="/Editor/delete/<?=$id ?>" class='thm-danger'> Удалить 
					<img  class='icon-xsmall' src='<?=DOMEN.THEMEDIR.'basic/img/trash.png'?>'> </a>
				</td>
				<td> 
					<? if ($page['active']):?>	<span class='thm-success'>
					<img  class='icon-xsmall' src='<?=DOMEN.THEMEDIR.'basic/img/1.png'?>'> активна</span> 
					<? else:?>					<span class='thm-inactive'>
					<img  class='icon-xsmall' src='<?=DOMEN.THEMEDIR.'basic/img/2.png'?>'> неактивна</span>
					<?endif;?>
				</td>
			</tr>
		<? endforeach; ?>
		
		<tr><td colspan='10'><?echo $nav_bar;?></td></tr>
	</tbody>
	</table>
	<br/>
	<p>	<a class="btn btn-default" href="/Editor/add/">Добавить новую страницу &raquo;</a></p>
		<a class="btn btn-default" href="/Editor/view/bytree"> Перейти к дереву страниц</a>
</div>