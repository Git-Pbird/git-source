<?
namespace application\controllers;

use core\basic\AController	as	AController;
use	application\models		as	Model;
use	core\router\Router		as	Router;
use	application\libs		as	Lib;

Class Page
	extends AController
{

private $model;
private $default_page;

	private function init(){		
	}
	
	public function __construct(){
		parent::__construct();
		self::$default_method	=	'view';
		$this->default_page		=	'home_me';
		
		$this->template->set('main_tpl','wrappers/base_front.tpl');
		$this->template->set('title','Мой сайт',true);
	}
	
	public function __destruct(){}
	
	public function view(){
		
		$url	=	Router::Instance()->getRoutes(false);
	
		if(!$url)
			$url = $this->default_page;
		
		$model	=	Model\Page::Instance();
		$menu	=	Model\Menu::Instance();
		$auth	=	new User();
		$search	=	new Lib\Search();
		
		$page	=	$model->getByURL($url);
		$tpl	=	Model\Templates::Instance()->getByID($page['template_id']);
		if(empty($page))
			Router::Instance()->redirect(404,null,true); #можно сделать через внутриний метод, для оставления урла и вложенности шаблона
		$menu_for_page	=	$menu->getMenuList($page['page_id']);
		foreach($menu_for_page as $menu_id){
			if($menu_id!=0){	#	если id не равен 0 достаем информацию о содержании меню
				$side_menu[]	=	$menu->sublight($menu->getM2Page($menu_id),$url);
			}
			else{				#	если id равен 0, значит меню иэрархическое. Достаем всех дочек страницы.
				$child = $model->getByParent($page['page_id']);
				if(!empty($child)){
					$side_menu[]=	$menu->sublight($child,$url);
				}
			}		
		}
		
		$top_menu 	= $menu->sublight($menu->getTopMenu(),$url);
		$auth_bar 	= $auth->getLogInForm();
		$search_box	= $search->getsearchForm();
		
		$this->template->addStyle('common', true, true);
		$this->template->addStyle('menu',	true, true);
		$this->template->set('main_tpl',	'wrappers/'.$tpl['path']);
		$this->template->set('title',		':: '.$page['title_on_page'], false);
		$this->template->set('keyword',		$page['keyword'], true);
		$this->template->set('description',	$page['description'], true);
		$this->template->set('top_menu',	$top_menu,true);
		$this->template->set('side_menu',	$side_menu,true);
		$this->template->set('auth_bar',	$auth_bar,true);
		$this->template->set('search_box',	$search_box,true);
		$this->template->set('content',		$this->template->render('page/content.tpl',$page));
		$this->template->print_page();
	}
	
}
