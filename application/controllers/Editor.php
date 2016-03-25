<?
namespace application\controllers;

use	application\models	as	Model;
use	core\router\Router	as	Router;
use	application\libs	as	Lib;

Class Editor
	extends Page
{
 
	public function __construct(){
		parent::__construct();
		self::$default_method	=	'view';
		
		$this->template->addScript('jQuery-1.11.3',true,true);
		$this->template->addStyle('common',true,true);
		$this->template->addStyle('menu',true,true);
		
		$this->template->set('main_tpl','wrappers/base_admin.tpl');
		$this->template->set('title','Админка');
	}
	
	public function __destruct(){}
	
	private function _getModel(){
		return Model\Editor::Instance();
	}
	
	public function bylist($model){
		$this->template->addStyle('navigation',true,true);
		
		$params		= Router::Instance()->getParams();
		$paginator	= new Lib\Paginator();
		
		$cpage		= $paginator->setCurentPage($params['page']);
		$quant		= $paginator->setPerPage(5);
		$count		= $model->getAllCount();
		$nav_bar	= $paginator->getNumeric($count);
		
		$model->setLimit($cpage,$quant);
		
		return	$content = $this->template->render(	'page/view_bylist.tpl',
											array(	'title_h1'=>'Все страницы',
													'map'=>$model->getAll(),
													'nav_bar'=>$nav_bar
												));
	}
	
	public function bytree($model){	
		return	$content = $this->template->render(	'page/view_bytree.tpl',
											array(	'title_h1'=>'Все страницы',
													'map'=>$model->make_tree()
												));
		
	}
	
	public function view(){
		$route	= Router::Instance()->getRoutes();
		$model	= $this->_getModel();
		$this->template->set('title',':: Просмотр списка страниц',false);
		switch($route[0]){
			case 'bytree':	$content = $this->bytree($model);
				break;
			case 'bylist':	
			default:		$content = $this->bylist($model);
				break;
		}
		
		$this->template->set('content',$content);
		$this->template->print_page();
	}
	
	public function add(){
		$this->template->set('title',':: Добавление страницы',false);
		$model = Model\Editor::Instance();
		$menu  = Model\Menu::Instance();
		
		$menus	= array();
		$fields = array('active'=>'on');
		$errors	= array();
		
		if($this->isPost()){
			if (isset($_POST['menus']))
				$menus	=	$_POST['menus'];
			
			if( $pid= $model->add($_POST) AND $menu->addM2Page($pid,$menus)){
				Router::redirect(200,'/Editor');
			}
			$fields	= $_POST;
			$errors	= $model->errors();
		}
		
		$this->template->addStyle('common',true,true);
		$this->template->addScript('ckeditor/ckeditor',true,true);
		$this->template->addScript('CK_init',true,true);
		
		$content = $this->template->render(	'page/add.tpl',
									array(	'title_h1'=>'H1 header add page',
											'fields'=>$fields,
											'errors'=>$errors,
											'map'=>$model->make_tree(),
											'menu'=>Model\Menu::Instance()->getAll(),
											'templates'=>Model\Templates::Instance()->getAll(),
											'menus'=>$menus
										));
										
		$this->template->set('content',$content);
		$this->template->print_page();
	}
	
	public function edit(){
		$this->template->set('title',':: Редактирование',false);
		
		$model	= Model\Editor::Instance();
		$menu	= Model\Menu::Instance();
		$route	= Router::Instance()->getRoutes();
		$errors	= array();
		
			if($this->isPost()){
				$menus		=	$_POST['menus'];
				$page_sort	=	$_POST['pages'];
				if( $model->edit($route[0],$_POST) AND
					$menu->editM2Page($route[0],$menus) AND
					$model->sorting($route[0],$page_sort)){
					Router::redirect(200,'/Editor');
				}
				$fields	= $_POST;
				$errors	= $model->errors();
			}else{
				$fields	= $model->getByID($route[0]);
				$menus	= $menu->getMenuList($route[0]);
			}
		$this->template->addScript('jQuery-1.11.3',true,true);
		$this->template->addScript('jquery-ui',true,true);
		$this->template->addScript('sortable',true,true);

			$this->template->addScript('ckeditor/ckeditor',true,true);
			$this->template->addScript('CK_init',true,true);
			$content = $this->template->render(	'page/edit.tpl',
										array(	'title_h1'=>'H1 header EDIT page',
												'fields'=>$fields,
												'errors'=>$errors,
												'map'=>$model->make_tree(),
												'children'=>$model->getByParent($route[0]),
												'menu'=>Model\Menu::Instance()->getAll(),
												'menus'=>$menus
											));
											
		$this->template->set('content',$content);
		$this->template->print_page(false);
	}
	
	
	
	
}
