<?php
namespace	core\Loader;

Class Autoloader
{
static $instance;
static $debug;
static $log_file;
static $needscan;
static $fullscan;
static $multiload;
static $class_ext;
static $tosearch;
static $loadlist;


	private function __clone(){}
	private function __construct(){
		self::$debug		=	array('display'=>false, 'log'=>true);
		self::$log_file		=	SYS_LOG;
		self::$needscan		=	false;		##	true ->будет рекурсивно искать во вложениях;	false->ограничется начальным списком
		self::$fullscan		=	false;		##	true ->будет искать все совпадения;				false->прекратит искать после первого совпадения
		self::$multiload	=	false;		##	true ->будет подключать все из массива путей;	false->подключит только первый елемент массива если их много
		self::$class_ext	=	'.php';
		self::$tosearch		=	array(
									#	'application/controllers/',
									#	'application/models/',
									#	'application/libs/',
									#	'module/',
									#	'core/'
									);
		self::$loadlist		=	array();
		
		autoloader::report('Запущен метод ['. __METHOD__ .']');
		autoloader::report('Создан экземпляр класса ['. __CLASS__ .']');
		autoloader::Register();
		
	}
	public function __destruct(){
		autoloader::report('Экземпляр класса ['. __CLASS__ .'] успешно уничтожен');
	}
	
	
	public static function Instance(){
		autoloader::report('Запрошен экземпляр класса ['. __CLASS__ .']');
		if( self::$instance===null)
			self::$instance = new autoloader();
	return	self::$instance;
	}
		
	private static function Register(){
		autoloader::report('Автозагрузчик успешно запущен и зарегистрирован в системе');
        return spl_autoload_register(array(__CLASS__, 'loadclass'));
    }
	
		
	private static function report($msg){
		$mem = memory_get_usage();
		if(self::$debug['log'])
			file_put_contents (self::$log_file,('['. $mem .' ('. round($mem/1024) .')] '. date('d.m.Y H:i:s')."\t=>\t" . $msg."\n"), FILE_APPEND | LOCK_EX);
		if(self::$debug['display'])
			echo '<p>['. $mem .' ('. round($mem/1024) .')] '.date('d.m.Y H:i:s') ."&nbsp &nbsp &nbsp &nbsp". $msg .'</p>';
	}

	
	
	

	
	#	принимает строку
	#	проверяет есть ли у строки разширение, если есть оставляет
	#	если нет добавляет по-умолчанию
	private static function checkExt($class,$cutExt=false){
		$class	=	str_replace('\\','/',$class);	##	разворачиваем сепараторы, для поиска пути
		$ext	=	mb_stristr($class,'.');
		if($cutExt===true){return mb_stristr($class,'.',true);}
		if(!$ext){
			$class	=	trim($class,'/');
			$class .=	self::$class_ext;
		}
		return $class;
	}
		
	
	#	принимает имя класа (как полное так и частичное)
	#	ищет файл в заданом массиве, если не находит и нужен полный поиск ищет по очереди всюду.
	#	если файл найден возвращает его путь.
	public static function find($class){
		if(!$class){return false;}
		$rel_path = null;
		$class = autoloader::checkExt($class);
		
		foreach(self::$tosearch as $path){
			autoloader::report('['. __FUNCTION__ .'][ПОИСК]  начинаем поиск `'.$class .'` по адрессу ['.$path.']');
			if(file_exists(ROOT.$path.$class)){
				autoloader::report('['. __FUNCTION__ .'][УСПЕХ]  файл `'. $path.$class .'` найден в корне');
				$rel_path = $path.$class;
				break;
			}elseif(self::$needscan AND $rel_path===null){ 
				autoloader::report('['. __FUNCTION__ .'][ПОИСК]  файл `'. $class .'` не найден по адрессу ['.$path.']');
				autoloader::report('['. __FUNCTION__ .'][ПОИСК]  начинаем рекурсивный поиск `'.$class .'` по адрессу ['.$path.']');
				$rel_path = autoloader::_recursive($class,$path);
				if($rel_path){
					break;
				}
			}else{
				autoloader::report('['. __FUNCTION__ .'][ОШИБКА] файл `'. $path.$class .'` не найден!');
			}
		}
		return $rel_path;
	}	
	
	private static function _recursive($class,$path='/'){
		look(array('имя'=>$class,'где'=>$path));
		$result = null;
		if($folder = opendir($path)){							#	Открываем
			while($dir_in = readdir($folder)){					#	Читаем
				if(strpos($dir_in,'.') === false){				#	Отсеиваем

					$dir_in.='/';
					$file = $path.$dir_in.$class;
					
					if(file_exists($file)){
						autoloader::report('['. __FUNCTION__ .'][УСПЕХ] файл `'. $path.$dir_in.$class .'` найден!');
						$result = $path.$dir_in.$class;
						if(!self::$fullscan) return $result;	#	если сканирование не требуется, прерываемся
					}
					else{
						autoloader::report('['. __FUNCTION__ .'][ПРОВАЛ] файл `'. $file .'` не найден!');
						$result = autoloader::_recursive($class,$path.$dir_in);
						if($result==true)return $result;
					}	
				}
			}
			closedir($folder);
			autoloader::report('['. __FUNCTION__ .'][ПРОВАЛ] рекурсивный поиск по адресу ['.$path.']  безуспешный ');
		}else{
			autoloader::report('['. __FUNCTION__ .'][ОШИБКА] не могу открыть папку ['.$path.'] ');
			$result = false;
		}
		return $result;
	}
	
	private static function _includer($class){
		
		autoloader::report('Запущен метод ['. __METHOD__ .'] запрошен класс ['.$class.']');
		$responce = false;
		$class = autoloader::checkExt($class);
		if(!file_exists($class)){
			autoloader::report('['. __FUNCTION__ .'][ПРОВАЛ]  файл '.$class.' не найден, запускаем поиск...');
			$found = autoloader::find($class);
			if($found){
				autoloader::report('['. __FUNCTION__ .'][УСПЕХ] найден файл '.$found);
				autoloader::_includer($found);
				$found = autoloader::checkExt($found,true);
				$responce = $found;
			}else{
				$responce = false;
			}
		}else{
			autoloader::report('['. __FUNCTION__ .'][УСПЕХ]  подключаем '.$class);
			include $class;
			$class = autoloader::checkExt($class,true);
			self::$loadlist[$class]= microtime(true);
			$responce = $class;
		}
		return $responce;
	}
	
	
	public static function loadclass($class,$path=null){
		if(!$class){return false;}
		if($path AND is_array($path)){
			self::$tosearch = $path;
		}else{
			self::$tosearch[]=$path;
		}		
		if(array_key_exists($class,self::$loadlist)){
			autoloader::report('['. __FUNCTION__ .'][УСПЕХ]  запрошеный файл `'.$class.'` уже подключен ранее. метка времени ['. self::$loadlist[$class] .']');
			return true;
		}
		
		$responce = autoloader::_includer($class);
		#look(array('name'=>$class,'responce'=>$responce));
		return $responce;
	}
}
