
<div>
	<select name='perpage' id='perpage'>
		<?php
		foreach ($option_pp as $option)
		{
		$s='';
		if($option == $per_page) $s = 'selected';
		echo "<option {$s} value='{$option}'> по {$option} на страницу</value>";
		}
		?>
	</select>
</div>
