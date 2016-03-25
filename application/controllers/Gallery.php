<?php
namespace application\controllers;

use core\basic\AController	as	AController;
use	application\models		as	Model;
use	core\router\Router		as	Router;

Class Gallery
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
		$this->template->set('title',':: Все альбомы',false);
		$model = Model\Gallery::Instance();
		
		$content = $this->template->render(	'gallery/view_all.tpl',
									array(	'title_h2'=>'Все галлереи',
											'gallery'=>$model->getAll()
										));
		$this->template->set('content',$content);
		$this->template->print_page();
	}
	
	public function add(){
		$this->template->set('title',':: Добавить альбом',false);
		$error = array();
		$model = Model\Gallery::Instance();
		if($this->isPost()){
			if($gid = $model->add_gallery($_POST)){
				Router::redirect(200,"/Gallery/images/$gid");
			}
			$fields	= $_POST;
			$errors	= $model->errors();
		}
		
		$content = $this->template->render(	'gallery/add.tpl',
									array(	'fields'=>$fields,
											'title_h2'=>'Введите данные альбома',
											'errors'=>$errors,
										));						
		$this->template->set('content',$content);
		$this->template->print_page();
	}
	
	public function edit(){
		 
	}
	
	public function upload(){
		$route	= 	Router::Instance()->getRoutes();
		$gid	=	isset($route[0])?(int)$route[0]:false;
		if(!$gid)	Router::redirect(200,'/Gallery');
		$model	=	Model\Gallery::Instance();
		
		$this->template->set('title',':: Добавление картинок на сайт',false);
		$this->template->addStyle('upload',true,true);
		$this->template->addScript('jQuery-1.11.3',true,true);
		$this->template->addScript('imageuploader',true,true);
		
		$content = $this->template->render('gallery/upload.tpl',array('gallery'=>$model->getByID($gid)));						
		$this->template->set('content',$content);
		$this->template->print_page();
	}
	
	public function images(){
		$this->template->set('title',':: Картинки',false);
		$route	= 	Router::Instance()->getRoutes();
		$gid	=	isset($route[0])?(int)$route[0]:false;
		if(!$gid)	Router::redirect(200,'/Gallery');
		$model	=	Model\Gallery::Instance();
		
		if($this->isPost()){
			$r = $model->dropImage($_POST['gallery_id'],$_POST['image_id']);
			Router::redirect(200,"/Gallery/images/$gid",true);
		}
		
		$content = $this->template->render(	'gallery/images.tpl',
									array(	'gallery'=>$model->getByID($gid),
											'images'=>$model->getImages($gid)
										));
		$this->template->addStyle('image',true,true);
		$this->template->addScript('jQuery-1.11.3',true,true);
		$this->template->addScript('jquery-ui',true,true);
		$this->template->addScript('imagesort',true,true);
		$this->template->addScript('imageuploader',true,true);
		$this->template->set('content',$content);
		$this->template->print_page();
	}
	
}
