CKEDITOR.dialog.add('galleryDialog', function(editor) {
		$.ajax({
		  type: "POST",
		  url: "/ajax/getgallery",
		  success: function(msg){ 
			 
			  CKEDITOR.arrGallery = msg; 
			  arr = JSON.parse(CKEDITOR.arrGallery);
			  arr2 =[];
			  arr3 =[];
			  for (var i in arr)
			  {
					arr2.push([arr[i]['title'],arr[i]['gallery_id']]);
					arr3[arr[i]['gallery_id']] = arr[i]['title'];
			  }
		  },
		async: false
				  
		});
		
		return {
			title : 'Вставить галерею',
			minWidth : 400,
			minHeight : 200,
			contents :
				[
					{
						id : 'tab1',
						label : 'Gallerys',
						elements :
						[       
							{       
								type : 'select',
								id : 'gallery',
								label : 'Выберите галерею',
								items : arr2
							}
						]
					}
				],

			onOk: function () {
				var gallery_id		= this.getContentElement('tab1', 'gallery').getValue();
				
				var widget = editor.document.createElement('widget');
				widget.setAttribute('widget-type', 'gallery');
				widget.setText('[[--widget/gallery/' + gallery_id + '--]]');
				widget = editor.createFakeElement(widget, "cke_gallery", "widget", !0);
				widget.$.title = 'Галерея картинок : {'+arr3[gallery_id]+'}'; // можно поставить нормальный тайтл, чтобы видеть, что за галерея выбрана
				/* widget.on('dbclick', function(){
					// можно добавить открытие окна для смены галереи
				}); */
				editor.insertElement(widget);
			}
	};
});