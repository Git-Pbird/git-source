<?php
namespace application\controllers;

use core\basic\AController	as	AController;
use	application\models		as	Model;
use	core\router\Router		as	Router;

Class Templates
	extends AController
{
	
	public function __construct(){		
		parent::__construct();
		$this->template->addStyle('common',true,true);
		$this->template->addStyle('menu',true,true);
		$this->template->set('main_tpl','wrappers/base_admin.tpl');
		$this->template->set('title','Админка');
	}
	public function add(){
		$this->template->set('title',':: Добавить шаблон',false);
		$error = array();
		$model = Model\Templates::Instance();
		if($this->isPost()){
			if($gid = $model->add($_POST)){
				Router::redirect();
			}
			$fields	= $_POST;
			$errors	= $model->errors();
		}
		
		$content = $this->template->render(	'templates/add.tpl',
									array(	'fields'=>$fields,
											'title_h2'=>'добавление шаблона',
											'errors'=>$errors,
											'templates'=>$model->read_templates(),
										));						
		$this->template->set('content',$content);
		$this->template->print_page();
	}
	
	
}
