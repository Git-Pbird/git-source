<?php
namespace application\models;

use core\basic\AModel		as	AModel;

Class Templates
	extends	AModel
{
private static $instance;

	protected function __construct(){
		parent::__construct('templates','template_id');
	}
	
	static function Instance(){		
			if(	self::$instance	===	null)
				self::$instance	=	new Templates();
		return	self::$instance;
	}
	
	public function read_templates(){
		$files = scandir(THEMEDIR.THEMENAME.'/template/wrappers/');
		foreach($files as $key=>$list_item){
			if($list_item == '.' OR $list_item== '..'){
				unset($files[$key]);
			}
		}
		return $files;
	}
}

