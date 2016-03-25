CKEDITOR.plugins.add('gallery', {

	onLoad: function () {
		CKEDITOR.addCss("img.cke_gallery{background-image: url(" + CKEDITOR.getUrl(this.path + "images/icon.png") + ");background-position: center center;background-repeat: no-repeat;border: 1px solid #a9a9a9;width: 50px;height: 50px;}")
	},
	
	init: function (editor) {
		var command = editor.addCommand('galleryDialog', new CKEDITOR.dialogCommand('galleryDialog', {
			allowedContent: 'widget[!widget-type]',
			requiredContent: "widget"
		}));
		
		editor.ui.addButton('Gallery', {
		  label : 'Галерея',
		  command : 'galleryDialog',
		  icon: this.path + 'images/gallery.png',
	//	  toolbar: "insert,80"
		});
		
		if (editor.contextMenu) {
			editor.addMenuGroup('galleryGroup' );
			editor.addMenuItem('galleryItem', {
				label: 'Свойства галереи',
				icon: this.path + 'images/gallery.png',
				command: 'gallery',
				group: 'galleryGroup'
			});
			
			editor.contextMenu.addListener(function(element) {
				if (element.getAscendant('img', true) && element.hasClass('cke_gallery')) {
					return { galleryItem: CKEDITOR.TRISTATE_OFF };
				}
			});
		}

		CKEDITOR.dialog.add('galleryDialog', this.path + 'dialogs/gallery.js');
	},
	
	afterInit: function (editor) {
		var dataProcessor = editor.dataProcessor;
		(dataProcessor = dataProcessor && dataProcessor.dataFilter) && dataProcessor.addRules({
			elements: {
				widget: function (dataProcessor) {
					if (dataProcessor.attributes['widget-type'] == 'gallery')
						return editor.createFakeParserElement(dataProcessor, "cke_gallery", "widget", !0)
				}
			}
		})
	}
});