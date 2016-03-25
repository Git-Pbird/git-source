
window.onload = function() {
		
        CKEDITOR.replaceAll('editme');
		CKEDITOR.config.filebrowserUploadUrl = '/ajax/ckupload/';
	//	CKEDITOR.config.filebrowserBrowseUrl = '/ajax/ckupload/';
		CKEDITOR.config.toolbarGroups = [
			{ name: 'document',    groups: [ 'mode', 'document', 'doctools' ] },
			{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
			{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ,'colors'] },
			{ name: 'links' },
			{ name: 'insert' },
	//		{ name: 'forms' },
			{ name: 'tools' },
			{ name: 'others' },
			{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
			{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align' ] },
			{ name: 'styles' },

	//		{ name: 'about' }
		];
		CKEDITOR.config.extraPlugins = 'gallery';
    };