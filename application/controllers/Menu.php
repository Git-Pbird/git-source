<?
namespace application\controllers;
use core\basic\AController	as	AController;
use	application\models		as	Model;
use	core\router\Router		as	Router;

Class Menu
	extends AController
{

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
		$this->template->set('title',':: Просмотр списка меню',false);
		$content = $this->template->render(	'menu/view_all.tpl',
									array(	'title_h1'=>'Список меню',
											'menu'=>Model\Menu::Instance()->getAll()
											));
		$this->template->set('content',$content);
		$this->template->print_page();
	}
	
	public function add(){
		$this->template->set('title',':: Добавление меню',false);
		$menu_model = Model\Menu::Instance();
		$page_model = Model\Editor::Instance();
		
		$fields = array('pages'=>array());
		$errors	= array();
		
		if($this->isPost()){
			if( $menu_model->add($_POST)){
				Router::redirect(200,'/menu');
			}
			$fields = $_POST;
			$errors	= $model->errors();
		}
		
		$content = $this->template->render(	'menu/add.tpl',
									array(	'title_h1'=>'Add menu',
											'fields'=>$fields,
											'errors'=>$errors,
											'map'=>$page_model->make_tree()
										));
										
		$this->template->set('content',$content);
		echo	$this->template->print_page();
	}
	
	public function edit(){
		$this->template->set('title',':: Редактирование Меню',false);
		
		$menu_model	= Model\Menu::Instance();
		$page_model = Model\Editor::Instance();
		$route	= Router::Instance()->getRoutes();
		
		$errors	= array();
		
			if($this->isPost()){
				if( $menu_model->edit($route[0],$_POST)){
					Router::redirect(200,'/Menu');
				}
				$fields	= $_POST;
				$errors	= $model->errors();
			}else{
				$fields	= $menu_model->getByID($route[0]);
			}
			$content = $this->template->render(	'menu/edit.tpl',
										array(	'title_h1'=>'EDIT menu',
												'fields'=>$fields,
												'errors'=>$errors,
												'map'=>$page_model->make_tree()
											));
											
		$this->template->set('content',$content);
		$this->template->print_page();
	}
	
	public function sorting(){
		$menu_model	=	Model\Menu::Instance();
		
		if($this->isPost()){
			if( $menu_model->sorting($_POST['menu_id'],$_POST['pages'])){
				Router::redirect(200,'/Menu',true);
			}
		}
			
		$this->template->set('title',':: Сортировка Меню',false);
		$this->template->addScript('jQuery-1.11.3',true,true);
		$this->template->addScript('jquery-ui',true,true);
		$this->template->addScript('sortable',true,true);
		$route	= Router::Instance()->getRoutes();
		$content = $this->template->render(	'menu/sort.tpl',
									array(	'title_h1'=>'SORT menu',
											'menu'=>$menu_model->getM2Page($route[0]),
											'menu_id'=>$route[0]
										));
											
		$this->template->set('content',$content);
		$this->template->print_page();
	}
	
	
	
	
}
