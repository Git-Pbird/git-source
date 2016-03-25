<h2>Добавление изображений</h2>

	<div id='drop-files' ondragover='return false'>
		<p>перетащите файлы сюда</p>
		<br/>
		<form id='frm'>
			<input type='file' name='file' id='uploadbtn' multiple>
			<input type='hidden' name='product_id' id='id' data-type="product" value="<?=$id;?>">
		</form>
	</div>
	<!-- область предпросмотра -->
	<div id='uploaded-holder'>
		<div id='dropped-files'>
			<!-- кнопки загрузить и удалить, а так же количество файлов-->
			<div id='upload-button' class='btn-block'>
				<span>0 файлов</span>
				<button type='button' class='btn btn-success upload'>Загрузить</button>
				<button type='button' class='btn btn-danger delete'>Удалить все</button>
				<!-- прогресс бар загрузки -->
				<div id='loading'>
					<div id='loading-bar'>
						<div class='loading-color'></div>
					</div>
					<div id='loading-content'></div>
				</div>
			</div>
		</div>
	</div>
	<div class='clear'></div>
	<!-- список загруженых файлов -->
	<div id='file-name-folder'>
		<ul id='uploaded-files'>
			<h2>загруженые файлы :</h2>
		</ul>
	</div>
