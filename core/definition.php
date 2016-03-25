<?php
	/*	
	 *	----------------
	 *	КОНСТАНТЫ ПУТЕЙ
	 *	----------------
	 *	ВНИМАНИЕ:	Не допускайте наличия ведущего слэша.
	 *				И проверяйте наличие замыкающего слэша.
	 */
if(!defined('DOMEN'))		define ('DOMEN', "http://afore.prj/");

if(!defined('SYSPATH'))		define('SYSPATH',	ROOT.'core/');
if(!defined('APPPATH'))		define('APPPATH',	ROOT.'application/');
if(!defined('DOCPATH'))		define('DOCPATH',	ROOT.'document/');
if(!defined('SESSPATH'))	define('SESSPATH',	DOCPATH.'session/');
if(!defined('UPLOADPATH'))	define('UPLOADPATH',	'upload/');
if(!defined('GALLERYPATH'))	define('GALLERYPATH',	UPLOADPATH.'gallery/');
if(!defined('AVATARS_PATH'))define('AVATARS_PATH',	UPLOADPATH.'avatars/');
if(!defined('RULES_PATH'))	define('RULES_PATH',	SYSPATH.'maps/rules.php');
if(!defined('MESSAGE_PATH'))define('MESSAGE_PATH',	SYSPATH.'maps/messages.php');
if(!defined('SEARCH_PATH'))	define('SEARCH_PATH',	SYSPATH.'maps/search.php');
	/**
	 *	Константы путей ТЕМЫ
	**/	
if(!defined('THEMENAME'))	define('THEMENAME',	'basic'); // Стоит убрать и перенести в контроллер View
if(!defined('THEMEDIR'))	define('THEMEDIR',	'theme/');
if(!defined('THEMEPATH'))	define('THEMEPATH',	DOMEN.THEMEDIR);
if(!defined('STYLEPATH'))	define('STYLEPATH',	DOMEN.'theme/');

//if(!defined('CSSPATH'))	define('CSSPATH',	DOMEN.'theme/');
//if(!defined('IMGPATH'))	define('IMGPATH',	THEMEPATH.'img/');

	/*
	 *	----------------
	 *	КОНСТАНТЫ ФАЙЛОВ
	 *	----------------
	 *	ВНИМАНИЕ:	Проверяйте наличие ведущего слэша.
	 */
// нужно перенести в клас загрузчика  а потом в конфигуратор
if(!defined('DEFAULT_CONTROLLER'))		define('DEFAULT_CONTROLLER','Page');
//if(!defined('DEFAULT_CONTROLLER_PATH'))	define('DEFAULT_CONTROLLER_PATH','application/controller/');
	 
if(!defined('SYS_LOG'))		define('SYS_LOG',	DOCPATH.'reports/system.log');
if(!defined('DB_LOG'))		define('DB_LOG',	DOCPATH.'reports/db.log');
if(!defined('USER_LOG'))	define('USER_LOG',	DOCPATH.'reports/user.log');

if(!defined('DB_STORE'))	define('DB_STORE',		DOCPATH.'DB/');

	/**
	 *	Константы для баз данных
	**/	
if(!defined('DB_NAME'))		define('DB_NAME',	'afore_db_prj');
if(!defined('DB_USER'))		define('DB_USER',	'root');
if(!defined('DB_PASS'))		define('DB_PASS',	'');
if(!defined('DB_HOST'))		define('DB_HOST',	'localhost');
if(!defined('DB_CHARSET'))	define('DB_CHARSET','UTF-8');
if(!defined('FETCH_MODE'))	define('FETCH_MODE','ASSOC');


	/**
	 *	Константы представления
	**/
if(!defined('AUTHOR'))	define('AUTHOR','AUTHOR');
if(!defined('WIDGET_REPLACE_PATTERN')) define('WIDGET_REPLACE_PATTERN','|<widget(.+?)>\[\[--(.+?)--\]\]</widget>|');
