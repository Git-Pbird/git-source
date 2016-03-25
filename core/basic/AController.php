<?
namespace core\basic;

use	core\view\Template			as	Template;

abstract Class AController{

static $default_method	=	'default_method';
	
	public function __construct(){
		$this->template	=	Template::Instance();
		$this->template->addStyle('preset',	true, true);
		$this->template->addStyle('basic',	true, true);
	}
	
	public static function getIndexMethod(){
		return self::$default_method;
	}
	
	public function default_method(){
		
	}
	
	public function isPost(){
		if(is_array($_POST) AND !empty($_POST))
			return true;
		return false;
	}
}
