<?php
	//если в массиве только одно значения запись должна быть в таком виде 'role' => array('field')
	return array(
		'pages' => array(
			'fields' => array('parent_page_id', 'url', 'full_cach_url', 'title_in_menu', 'title_on_page', 'title_h1', 'keyword', 'description', 'content','active','template_id'), // поля, чтобы выкинуть из массива $_POST лишние элементы
			'not_empty' => array('parent_page_id', 'url', 'full_cach_url', 'title_in_menu', 'title_on_page', 'title_h1'), // не могут быть пустыми
			'html_allowed' => array('content'), // для них не применяем htmlspecialchars
			'unique' => array ('title_in_menu', 'full_cach_url'), // не даём занести в базу, если такие уже есть
			'min_length' => array(
								'title_in_menu' => '3', 
								'title_on_page' => '3', 
								'title_h1' => '3'
								),
			'max_length' => array(
								'title_in_menu' => '30', 
								'title_on_page' => '128',
								'title_h1' => '128',
								'keyword' => '256',
								'description' => '256',
								'content' => '3000'
								),
			'range' => array(
						//количество символв в диапазоне от до
						'url' => array('3', '20')
						),
	#####	#'not_url' => array('url'),
			/*
			//правило для проверки соответствия одного поля другому
			//equals записывается только в таком виде ключ-проверочное поле, значение проверяемое поле
			'equals' => array(
						'field1' => 'field2',
						'field3' => 'field4'
						), */
			/*
			//для каждой таблицы можно добавить новое правило
			'rule' => array(),*/
			
			// специальные правила для полей типа пароля итп
			'special_rules' => array(
									//значения этих полей не должно быть одинаковым
									'illegal_entry' => array('parent_page_id', 'page_id')
									),
			'labels' => array(
				/* 
				//здесь можно присвоить полю человекопонятное название
				'field' => '"Название"', */
				'url'			 => '"Адрес страницы"',
				'title_in_menu'	 => '"Заголовок в меню"',
				'title_on_page'	 => '"Заголовок в title страницы"',
				'title_h1'		 => '"Заголовок H1 на странице"',
				'keywords'		 => '"Ключевые слова"',
				'description'	 => '"Описание страницы"',
				'content'		 => '"Контент"',
				'parent_page_id' => '"Родительский Раздел"'
			),
			//первичный ключ таблицы
			'pk' => 'page_id'
		),
		'menu' => array(
			'fields' => array('menu_title'), 
			'not_empty' => array('menu_title'),
			//массив 'html_allowed' нужно объявлять обязательно, даже если он пустой
			'html_allowed' => array(),
			'unique' => array ('menu_title'),
			'range' => array(
						'title' => array('3', '20')
						),
			'labels' => array(
				/* 
				//здесь можно присвоить полю человекопонятное название
				'field' => '"Название"', */
				'title' => '"Название меню"'
			),
			'pk' => 'menu_id'
		),
		'users' => array(
			'fields' => array('login', 'pass', 'alias'), 
			'not_empty' => array('login', 'pass', 'alias'),
			'unique' => array ('login'),
		#	'authorized' => array('login'),
			'html_allowed' => array(),
			'labels' => array(
				/* 
				//здесь можно присвоить полю человекопонятное название
				'field' => '"Название"', */
				'login' => '"Логин"',
				'pass' => '"Пароль"',
				'alias' => '"Имя"'
			),
			'pk' => 'user_id'
		),
		'gallery' => array(
			'fields' => array('title'), 
			'not_empty' => array('title'),
			'html_allowed' => array(),
			'unique' => array ('title'),
			'max_length' => array(
								'title' => '20'
								),
			'labels' => array(
				/* 
				//здесь можно присвоить полю человекопонятное название
				'field' => '"Название"', */
				'title' => '"Название"'
			),
			'pk' => 'gallery_id'
		),
		'images' => array(
			'fields' => array('path','img_title','alt','created','active'), // поля, чтобы выкинуть из массива $_POST лишние элементы
			'not_empty' => array('path'), // не могут быть пустыми
		#	'html_allowed' => array(''), // для них не применяем htmlspecialchars
			'unique' => array ('path'), // не даём занести в базу, если такие уже есть
			'min_length' => array(
								'path' => '1'
								),
			'max_length' => array(
								'path' => '256'
								),
			'labels' => array(
				'path' => '"path (путь к картинке / название)"'
			),
			'pk' => 'image_id'
		),
		'templates' => array(
			'fields' => array('name', 'path'), 
			'not_empty' => array('name', 'path'),
			'html_allowed' => array(),
			'unique' => array ('name', 'path'),
			'pk' => 'template_id',
			'labels' => array(
				'name' => '"Название шаблона"',
				'path' => '"Путь"'
			)
		),
		'products' => array(
			'fields' => array('marking','keyword', 'description', 'url', 'title_on_page', 'title_in_menu', 'title_h1', 'content', 'active', 'view', 'template_id', 'create_date', 'modify_date', 'priority', 'price_active', 'price_prev', 'currency_id'), // поля, чтобы выкинуть из массива $_POST лишние элементы
			'not_empty' => array('url','title_on_page','template_id'), // не могут быть пустыми
			'html_allowed' => array('content'), // для них не применяем htmlspecialchars
			'unique' => array ('url', 'marking'), // не даём занести в базу, если такие уже есть
			'min_length' => array(
								'title_on_page' => '3', 
								'title_in_menu' => '3', 
								'title_h1' => '3'
								),
			'max_length' => array(
								'title_on_page' => '100',
								'title_in_menu' => '100',
								'title_h1' => '100',
								'keyword' => '256',
								'description' => '256',
								'content' => '3000'
								),
			'range' => array(
						//количество символв в диапазоне от до
						'url' => array('3', '128')
						),
	
			/*
			//правило для проверки соответствия одного поля другому
			//equals записывается только в таком виде ключ-проверочное поле, значение проверяемое поле
			'equals' => array(
						'field1' => 'field2',
						'field3' => 'field4'
						), */
			/*
			//для каждой таблицы можно добавить новое правило
			'rule' => array(),
			*/
			
			// специальные правила для полей типа пароля итп
			'special_rules' => array(
									//значения этих полей не должно быть одинаковым
									'illegal_entry' => array()
									),
			'labels' => array(
				/* 
				//здесь можно присвоить полю человекопонятное название
				'field' => '"Название"', */
				'marking'			 =>	'"Артикуль товара"',
				'price_active'	 =>	'"Текущая ценна товара"',
				'url'			 => '"Адрес страницы"',
				'title_on_page'	 => '"Заголовок в title страницы"',
				'title_in_menu'	 => '"Заголовок в для ссылок (меню)"',
				'title_h1'		 => '"Заголовок H1 на странице"',
				'keyword'		 => '"Ключевые слова"',
				'description'	 => '"Описание страницы"',
				'content'		 => '"Контент"',
				'parent_page_id' => '"Родительский Раздел"'
			),
			//первичный ключ таблицы
			'pk' => 'product_id'
		)
		
		
	);
	
