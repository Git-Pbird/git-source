<?
namespace application\controllers;

use core\basic\AController	as	AController;
use	application\models		as	Model;
use	core\router\Router		as	Router;
use	application\libs		as	Lib;

Class Product
	extends AController
{

private $model;

	
	public function __construct(){
		parent::__construct();
		self::$default_method	=	'main';
		$this->model	=	Model\Product::Instance();
		$this->menu		=	Model\Menu::Instance();
		$this->router	=	Router::Instance();
	}
	
	private function init(){
	}
	
	public function __destruct(){}
	
	private function basic(){	
		
		
		$auth	=	new User();
		$search	=	new Lib\Search();

		$top_menu 	= $this->menu->getTopMenu();
		$auth_bar 	= $auth->getLogInForm();
		$search_box	= $search->getsearchForm();
		
		$this->template->set('main_tpl','wrappers/base_front.tpl');
		$this->template->set('title','Интернет-магазин',true);
		$this->template->addStyle('common', true, true);
		$this->template->addStyle('menu',	true, true);
	#	$this->template->set('title',		':: '.$page['title_on_page'], false);
		$this->template->set('keyword',		'', true);	//	нужно взять для всего сайта в Шаблонизаторе, а тут !не! переопределять
		$this->template->set('description',	'', true);	//	нужно взять для всего сайта в Шаблонизаторе, а тут !не! переопределять
		$this->template->set('top_menu',	$top_menu,true);
		$this->template->set('auth_bar',	$auth_bar,true);
		$this->template->set('search_box',	$search_box,true);
	}
	
	public function main(){
		$this->basic();
		$tpl		=	Model\Templates::Instance()->getByID(1);
		$content_arr=	$this->model->getAll();
		$content	=	$this->template->render('product/content.tpl',array('products'=>$content_arr));
		
		$this->template->set('content',		$content);
		$this->template->print_page();
	}
	
	public function add(){
		$this->template->set('main_tpl','wrappers/base_admin.tpl');
		$this->template->set('title',':: Добавление товара',false);
		$this->template->addScript('jQuery-1.11.3',true,true);
		$this->template->addStyle('menu',true,true);
		
		
		$fields = array('active'=>'on');
		$errors	= array();
		
		if($this->isPost()){
			if($id = $this->model->add($_POST)){
				Router::redirect(200,"/Product/addimg/$id");
			}
			$fields	= $_POST;
			$errors	= $this->model->errors();
		}
		
		$this->template->addStyle('common',true,true);
		$this->template->addScript('ckeditor/ckeditor',true,true);
		$this->template->addScript('CK_init',true,true);
		
		$content = $this->template->render(	'product/add.tpl',
									array(	'title_h1'=>'H1 header add product',
											'fields'=>$fields,
											'errors'=>$errors,											
											'templates'=>Model\Templates::Instance()->getAll(),
										));
										
		$this->template->set('content',$content);
		$this->template->print_page();
	}
	
	public function addimg(){
		$this->template->set('main_tpl','wrappers/base_admin.tpl');
		$this->template->addStyle('menu',true,true);
		
		$route	= 	Router::Instance()->getRoutes();
		$pid	=	isset($route[0])?(int)$route[0]:false;
		if(!$pid)	Router::redirect(200,'/Product/add');
		
		look($pid);
		
		$this->template->set('title',':: Добавить картинки',false);
		$this->template->addStyle('upload',true,true);
		$this->template->addScript('jQuery-1.11.3',true,true);
		$this->template->addScript('imageuploader',true,true);
		
		$content = $this->template->render('product/upload.tpl',array('id'=>$pid));						
		$this->template->set('content',$content);
		$this->template->print_page();
	}
	
	public function p(){
		$this->basic();
		$url = $this->router->getRoutes();
		
		$data		= $this->model->getByURL($url[0]);
		$content	= $this->template->render('product/single_content.tpl',array('data'=>$data));
		
		$this->template->addStyle('single_good', true, true);
		$this->template->addScript('tabs', true, true);
		$this->template->set('content',	$content);
		$this->template->print_page();
	}
	
	
}
