<?
namespace application\controllers;
use core\basic\AController		as	AController;
use	application\models			as	Model;
use	core\view\Template			as	Template;

Class Admin
	extends AController
{


	private function init(){		
	}
	
	public function __construct(){
		parent::__construct();
		self::$default_method	=	'view';
		
		$this->template->addStyle('common',true,true);
		$this->template->addStyle('menu',true,true);
		$this->template->set('main_tpl','wrappers/base_admin.tpl');
		$this->template->set('title','Админка');
	}
	
	public function __destruct(){}
	
	public function view(){
		$this->template->set('title',':: Просмотр',false);
		$this->template->addStyle('widget',true,true);
		
		$content = $this->template->render(	'admin_title.tpl',
									array(	'widget_gall'=>''));
		$this->template->set('content',$content);
		
		$this->template->print_page();
	}
	

	
}
