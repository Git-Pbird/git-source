<?php
namespace	core\Router;
use	core\Loader				as	Loader;

Class Router
{
static	$instance;
static	$debug;
static	$log_file;
public	$uri_str;			//	string
public	$query_str;			//	string
public	$routes;			
public	$params;			//	параметры из строки запроса ГЕТ
public	$post;				//	массив ПОСТ
public	$controller_path;	//	array
public	$controller_name;	//	string
public	$controller	;		//	Obj
public	$default_controller;
public	$method_name;		//	string


	private function __clone(){}
	private function __construct(){
		self::$debug		=	array('display'=>false, 'log'=>true);
		self::$log_file		=	SYS_LOG;
		$this->default_controller	=	'Product'; #DEFAULT_CONTROLLER;
		$this->routes		=	array();			
		$this->params		=	array();
		$this->controller_path= array('application/controllers/','application/libs/');
		
		
		router::report('Запущен метод ['. __METHOD__ .']');
		router::report('Создан экземпляр класса ['. __CLASS__ .']');
	}
	function __destruct(){
		router::report('Экземпляр класса ['. __CLASS__ .'] успешно уничтожен');
	}
	
	
	public static function Instance(){
		router::report('Запрошен экземпляр класса ['. __CLASS__ .']');
		if( self::$instance===null)
			self::$instance = new Router();
	return	self::$instance;
	}
		
	
		
	private static function report($msg){
		$mem = memory_get_usage();
		if(self::$debug['log'])
			file_put_contents (self::$log_file,('['. $mem .' ('. round($mem/1024) .')] '. date('d.m.Y H:i:s')."\t=>\t" . $msg."\n"), FILE_APPEND | LOCK_EX);
		if(self::$debug['display'])
			echo '<p>['. $mem .' ('. round($mem/1024) .')] '.date('d.m.Y H:i:s') ."&nbsp &nbsp &nbsp &nbsp". $msg .'</p>';
	}

	
	
	public function	run($url=null)
	{
		//	Разбиваем УРЛ на составные, массив-путей и массив-параметров
		$this->parseURL($url); 
		//	Вычленяем контроллер, обычно первый елемент массива-путей
		$this->setController();
		//	запускаем контроллер
		$this->runController();
		//	Устанавливаем метод для полученого контроллера
		$this->setMethod(); 
		//	Сливаем массивы параметров в один массив
		$this->setParams();
		//	Сливаем все параметры из ПОСТ в переменную
	#	$this->setPOST();
		//	запускаем метод. с указаными параметрами
		$this->runMethod();
		
		
	}
 	 
	public function	parseURL($url=null){
	$result	=	false;
	
	$url = isset($url)?$url:$_SERVER['REDIRECT_URL'];
	
	
	if (isset($url))
		{
		$this->uri_str	=	$this->filter_uri($url);
		$this->routes	=	explode('/',trim($this->uri_str,'/'));
		$result	=	true;
		}
		
	if (isset($_SERVER['QUERY_STRING']) AND ($_SERVER['QUERY_STRING']) !='')
		{
		$this->query_str=	$this->filter_uri($_SERVER['QUERY_STRING']);
		$this->params	=	explode('?',str_replace("&", "?", $this->query_str));
		$result	=	true;
		}
	return	$result;
	}
	
	
	
	function	setController(){
		$call_controller = array_shift($this->routes);					#	вытягиваем первый елемент, и принимаем его как контроллер
		$path = Loader\autoloader::loadclass($call_controller,$this->controller_path);			#	пытаемся его загрузить, как контроллер
		if(!$path){														#	если загрузить не удалось и вернулся НУЛЛ
			array_unshift($this->routes,$call_controller);				#	заталкиваем его обратно в массив путей
			$call_controller = null;									#	обнуляем запускаемый контроллер
			$path= Loader\autoloader::loadclass($this->default_controller,$this->controller_path);	#	пытаемся загрузить контроллер по-умолчанию
			if(!$path){													#	если и контроллер по-умолчанию не найден
				Router::redirect('404',null,true);						#	запускаем 404-ю ошибку "не найдено"
			}else{
			#	$this->controller_name = $this->default_controller;
			}
		}else{
		#	$this->controller_name = $call_controller;
		}
		$this->controller_name = $path;
	}
	
	function	setMethod(){
		$obj = $this->controller;
		$call_method	 = array_shift($this->routes);					#	вытягиваем первый елемент, и принимаем его как метод
		if($call_method == NULL OR !method_exists($obj,$call_method)){	#	если метод не передан или его нет в классе
			array_unshift($this->routes,$call_method);					#	засовываем его обратно
			if(method_exists($obj,'getIndexMethod')){					#	проверяем есть ли метод по-умолчанию в классе как таковой
				$call_method = $obj::getIndexMethod();					#	если есть - выбираем его для запуска
			}else{														#	если нет
				Router::redirect('404'); 								#	запускаем 404-ю  
			}
		}
		$this->method_name = $call_method;					 			#	если все отработало, и 404-я не выскочила, присваиваем метод
	}
	
	function	runController(){
		$this->controller_name	=	str_replace('/','\\',$this->controller_name);	##	разворачиваем сепараторы, для запуска класс
		$this->controller	=	new	$this->controller_name();
	}

	function	runMethod(){
		$controller = $this->controller;
		$method		= $this->method_name;
		$controller->$method();
	}
	
	function	setParams()
	{
		if($this->params)
		{
			foreach ($this->params as $param)
			{
			$part	=	explode('=',$param);
				if(is_array($part) AND count($part)>=2)
					$param_params[$part[0]]	=	$part[1];
				else
					$param_params[$part[0]] = false;
			}
			
			if(count($param_params)>0)
			{
			$this->params	=	$param_params;
			return	true;
			}
				
		return	false;		
		}
	}
	
	public function getParams($array=true){
		return $this->params;
	}
	
	public function getRoutes($array=true){
		if(!$array)
			return implode('/',$this->routes);
		return $this->routes;
	}
	
	function setPOST()
	{
		$this->post = $this->filter_uri($_POST);
		return true;
	}
	
	function	filter_uri($str)
	{
		$bad	= array('$',		'(',		')',		'%28',		'%29');
		$good	= array('&#36;',	'&#40;',	'&#41;',	'&#40;',	'&#41;');
		return str_replace($bad, $good, $str);
	}
	
	public static function redirect($num='200',$url='/',$die=false){
		$num = (int)$num;
		$loc	=	'Location: '. $url;
		switch($num){
			case '301':	exit('ERROR 301 ');break;
			case '302': exit('ERROR 302 ');break;
			
			case '403': header($loc,true,403);include('/theme/errp/403.tpl');break;
			case '404': header($loc,true,404);include('/theme/errp/404.tpl');break;
			default: 	header($loc,true);break;
			
		}
		if($die) die();
		return false;
	}
	
	
}
