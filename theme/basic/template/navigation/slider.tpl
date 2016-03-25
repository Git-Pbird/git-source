<script type='text/javascript'>		
		var pages = <?php if(!isset($total_pages) OR !$total_pages) {echo 1;} else {echo $total_pages;} ?> ;
		var	current_page = <?php if(!isset($current_page) OR !$current_page) {echo 1;} else {echo $current_page;} ?> ;
</script>
<div class='load'> </div>
<div class = 'nav_bar'>
	<a id='prev'> </a>
		<div class = 'block'>
			<div id='slider'  style="border: 1px solid #82217A"> </div>
		</div>
	<a id='next'> </a>

</div>