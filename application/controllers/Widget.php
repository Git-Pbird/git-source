<?
namespace application\controllers;

use core\basic\AController	as	AController;
use	core\router\Router		as	Router;
use	application\models		as	Model;

Class Widget
	extends AController
{
	public function __construct(){
		parent::__construct();
		self::$default_method	=	'index';
		$this->widget			=	'';
	}
	public function index(){}
	
	public function gallery(){
		$route	= Router::Instance()->getRoutes();
		$id		=	isset($route[0])?(int)$route[0]:null;
		if(!$id) return '';
		
		$this->widget = $this->template->render('widget/gallery.tpl',
										  array('images'=>Model\Gallery::Instance()->getImages($id)
											
												));
		$this->render($this->widget);
	}
	
	public function render(){
		echo $this->widget;
	}
	
	
}
