<?
namespace application\controllers;

use core\basic\AController		as	AController;
use	application\models			as	Model;

Class User
	extends AController
{
private $model;

	public function __construct(){
		parent::__construct();
		$this->model	=	Model\User::Instance();
	}
	
	public function register(){
		$menu	=	Model\Menu::Instance();
		
		
		$this->template->set('main_tpl','wrappers/base_front.tpl');
		$this->template->set('title','Авторизация',true);
		if($_POST){
			$res = $this->model->register($_POST);
			if($res){
				Router::Instance()->redirect(200,'/',true);
			}
			else{
				exit('Ошибка сервера. Перезагрузите Вашу страницу!');
			}
		}
		
		$top_menu = $menu->getTopMenu();
		$auth_bar = $this->getLogInForm();
		
		$this->template->addStyle('common', true, true);
		$this->template->addStyle('menu',	true, true);
		$this->template->set('title',		':: Регистрация', false);
		$this->template->set('keyword',		'', true);
		$this->template->set('description',	'', true);
		$this->template->set('top_menu',	$top_menu,true);
		$this->template->set('side_menu',	'',true);
		$this->template->set('auth_bar',	$auth_bar,true);
		$this->template->set('content',		$this->template->render('auth/register.tpl',array()));
		$this->template->print_page();
	}
	
	public function repasslink(){
		if(isset($this->routes[1]))
		{
			$hash = $this->routes[1];
			$result		=	$this->model->access_change($hash);
		}
		else
		{
		$hash = '';
		$result = 'Не корректно указана Хэш ссылка ';
		}
			$meta		=	$this->model->getMeta($this->getCurrentPage(),'page');	#core_model
			
			$side_menu	=	$this->getSideMenu();	# core_controller
			$top_menu	=	$this->getTopMenu();	# core_controller
			
			$content	=	$this->render('auth/repass.tpl', array('result'=>$result, 'hash'=>$hash));
			
			echo $this->render('main_view.tpl',	array(	'scripts'	=>	$this->scripts,
														'styles'	=>	$this->styles,
														'meta'		=>	$meta,
														'side_menu'	=>	$side_menu,
														'top_menu'	=>	$top_menu,
														'content'	=>	$content
													));
	}
	
	public function setNewPass(){
		$newpass= trim(htmlspecialchars($_POST['newPass']));
		$hash	= trim(htmlspecialchars($_POST['hash']));
			if($this->model->updatePass($newpass,$hash))
			{
				$this->model->clearHash($hash);
				header("Location:".DOMEN );
				exit;
			}
			else
			{
				header("Location:".DOMEN );exit;
			}
	}
	
	public function checkAction(){
				
		if(isset($_POST['login'])){
			if($this->model->login($_POST['login'],$_POST['pass']))
				{exit('Вы успешно авторизировались');}
			else
				{exit('Ошибка авторизации');}
		}
		
		if(isset($_POST['logout'])){
			$this->model->logout();
			exit('Вы успешно вышли');
		}

		if(isset($_POST['sendPass'])){
			if($answer = $this->model->sendPassByMail($_POST['mail']))
				{
				echo $answer;
				exit;
				}
			else
				{exit('Ошибка отправки письма');}
		}

		return $this->getForm();
	}
	
	public function getLogInForm(){
		return $this->checkAction();
	}
	
	public function checkAccess($user,$resource,$action){
		return $this->model->checkAccess($user,$resource,$action);
	}
	
	public function getForm(){
		$info = $this->model->Get();
		$this->model->can('DEL_PAGE');
		$this->template->addStyle('auth', true, true);
		$this->template->addScript('jQuery-1.11.3',true,true);
		$this->template->addScript('auth', true, true);
		$loginform = $this->template->render('auth/login_form.tpl', $info);
		return $loginform;
	}
}
