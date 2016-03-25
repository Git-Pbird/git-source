<?
namespace application\models;
use core\basic\AModel		as	AModel;

Class User
	extends	AModel
{
	
private $User		=	array();				//	массив данных пользователя из БД	
private $uid;
private $sid;

public $default_avatar	=	'default/d_avatar.jpg';
public $blocked_avatar	=	'default/b_avatar.png';

public $UP 			=	array();				//	Массив разрешений для пользователя
private static $instance;	
	
	/**
	*	"protected", из-за конфликта видимости и наследования AModel::__construct и получения ключа $db...
	**/
	public static function Instance(){
			if(	self::$instance	===	null)
				self::$instance	=	new User();
		return	self::$instance;
	}
	private function __clone(){}

	protected function __construct(){
		parent::__construct('user','user_id');
		#	достать id пользователя по данным сессии
		#	проверить по БД соответствие ИД,Имени,Пароля
		#	
		#	запустить "авторизацию" если совпадают
		#	запустить "выход" если не совпадают
		#
		#	если сесия пустая занести данные по-умолчанию
		
			$this->init();	
	}
	
	protected function init(){
			$this->uid = null;
			$this->sid = null;
			$this->ClearSession();
	}
	
	public function Get($user_id=null){
		#	Достаем данные пользователя из БД
		#	Если указан ИД-то используем его
		#	Если ИД - не указан, берем из сессии.
		#	Если ИД не найден, возвращаем пустой массив
		#
		#	Возвращает массив.
		
		if ($user_id === null)
			$user_id = $this->GetUid();	//	пробуем достать из сессии
			
		if ($user_id === null)
			return array();
		
		$sql	= "	SELECT	user_id	AS id,
							login,
							pass,
							alias,
							active
					FROM	users
					WHERE	user_id = ?i";
		$User	= $this->db->getRow($sql,$user_id);
		
		$User['authorized'] = true;
		$User['avatar']		= $this->getAvatar();
		
		return $User;
	}
	
	private function getByLogin($login){
		$sql	= "	SELECT	user_id	AS id,
							login,
							pass,
							alias,
							active
					FROM	users
					WHERE	login = ?s";
		$User	= $this->db->getRow($sql,$login);
		return $User;
	}
	
	public function logout(){
		setcookie('login',   '', time()-1);
		setcookie('pass','', time()-1);
		unset($_COOKIE['login']);
		unset($_COOKIE['pass']);
		unset($_SESSION['sid']);
		
		$this->sid = null;
		$this->uid = null;
	}
	
	public function login($raw_login,$raw_pass,$remember=true){
		
		$user = $this->getByLogin($raw_login);
		
		if($user == null)
			return false;
		
		$user_id = $user['id'];
		
		// проверяем пароль
		if($user['pass'] != md5($raw_pass))
			return false;
		
		// запоминаем логин и пароль
		if($remember){
			$expire = time() + 3600 *24 *100; 
			setcookie('login', $login, $expire);
			setcookie('pass', md5($pass),$expire);
		}
		
		// открываем сессию
		$this->sid = $this->OpenSession($user_id);
		return true;
	}
	
	private function OpenSession($user_id){
		
		// генерация SID
		$sid = $this->GenerateStr(10);
		
		// записываем SID в БД
		$now = date('Y-m-d H:i:s');
		$session = array();
		$session['user_id']			= $user_id;
		$session['sid']				= $sid;
		$session['session_start']	= $now;
		$session['session_modify']	= $now;
		
		$this->insert('session',$session);
		
		// регестрирцем сессию в PHP
		$_SESSION['sid'] = $sid;
		
		// возвращаем SID
		return $sid;
	}	
	
	private function ClearSession(){
		$min = date('Y-m-d H:i:s', time() -60*20);
		$sql = '?n < ?s';
		$where = $this->db->parse($sql,'session_modify',$min);
		$this->delete('session',$where);	
	}
	
	private function GenerateStr($length = 32){
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
		$code = "";
		$clen = strlen($chars) - 1;  

		while (strlen($code) < $length) 
            $code .= $chars[mt_rand(0, $clen)];  

		return $code;
	}
	
	private function getUid(){
		
		// проверяем КЭШ
		if($this->uid != null)
			return $this->uid;
		
		// берем из текущей сессии
		$sid = $this->GetSid();
		
		if($sid == null)
			return null;
		
		$sql = 'SELECT	user_id
				FROM	session
				WHERE	sid = ?s';
		$res = $this->db->getOne($sql,$sid);
		
		// если сессии нет - значит пользователь не авторизован
		if(!$res)
			return null;
		
		// если есть
		$this->uid = $res;
		return $this->uid;
	}
	
	private function getSid(){
		
		// Проверяем КЭШ
		if($this->sid != null)
			return $this->sid;
		
		// ищем SID в сессии
		$sid = $_SESSION['sid'];
		
		// Если нашли, пробуем обновить "последнее посещение"
		// Проверяем есть ли там сессия вообще
		if($sid != null){
			$session = array();
			$session['session_modify'] = date('Y-m-d H:i:s');
			
			$sql	= '?n = ?s';
			$where	= $this->db->parse($sql,'sid',$sid);
			$rows	= $this->update('session',$session,$where);
		
			if($rows == 0){
				$sql = 'SELECT count(*) FROM session WHERE sid = ?s';
				$res = $this->db->getOne($sql,$sid);
				if ($res == 0)
					$sid = null;
			}
		}
		
		// нет сессии, ищем логин и пароль(мд5) в куках
		if($sid == null && isset($_COOKIE['login'])){
			$user = $this->GetByLogin($_COOKIE['login']);
			if($user != null && $user['pass'] == $_COOKIE['pass']){
				$sid = $this->OpenSession($user['id']);
			}
		}
		//запоминаем КЭШ
		if ($sid != null)
			$this->sid = $sid;
		
		// возвращаем sid
		return $sid;		
	}
	
	private function getAvatar($user_id=null){
		$avatar = null;
		$Avatar_path = DOMEN . AVATARS_PATH;
		if(!$user_id)
			$user_id=$this->UserId;
			
			if($user_id)
			{
				$sql =  "SELECT	user_img
						FROM	user
						WHERE	user_id = ?i";
				$avatar = $this->Db->getOne($sql,$user_id);
				$UserAvatar = $Avatar_path .'/'. $user_id .'/'. $avatar;
			}
		if($avatar==null)
			$UserAvatar	=	$Avatar_path . $this->default_avatar;
		if($user_id AND !$this->isActive($user_id))
			$UserAvatar	=	$Avatar_path . $this->blocked_avatar;
	return $UserAvatar;
	}
	
	public function can($resource,$user_id=null){

		# Если нет пользователя проверяем для текущего пользователя
		# Возвращает bool
		
		// достаем ИД разрешения (ресурса)
		$pid	= $this->getPermissionID($resource);
		
		// достаем все что разрешено пользователю
		$up		= $this->getUserPermissions();
		
		// проверяем разрешено ли пользователю это действие
		$can	=	in_array($pid,$up);
	
		return $can;
	}
	
	public function getUserPermissions($user_id = null){
		#получить список всех разршений для указаного пользователя
		$UP = array();
		
		if ($user_id == null)
			$user_id = $this->GetUid();
		
		if($user_id == null)
			return $UP;
		
		$role_ids = $this->getRoleID($user_id);
		
		if($role_ids == null)
			return $UP;
		
		
		$sql = 'SELECT	permission_id
				FROM	permission2role
				WHERE	role_id IN (?a)
					AND	active = 1 ;';
		$this->UP = $this->db->getCol($sql,$role_ids);
		return $this->UP;
	}
	
	private function getRoleID($user_id = null){
		if ($user_id == null)
			$user_id = $this->GetUid();
		
		if($user_id == null)
			return null;
		
		$sql = 'SELECT	role_id
				FROM	role2users
				WHERE	user_id = ?i
					AND	active = 1 ;';
		$roles = $this->db->getCol($sql,$user_id);
		return $roles;
	}
	
	private function getPermissionID($permission = null){
		if($permission == null)
			return null;
		
		$sql = 'SELECT	permission_id
				FROM	permission
				WHERE	name = ?s';
		return $this->db->getOne($sql,$permission);
	}

	
	
	
	
	
	
	
	
	
	
	
	public function sendPassByMail($raw_mail){
		$email = trim($raw_mail);
		if( empty($email) )
		{
			return "Вы не указали адресс куда отправить ссылку";
		}
		else
		{
			$sql = "SELECT	user_id
					FROM	user
					WHERE	email = ?s ;";
			$result = $this->Db->getOne($sql,$email);
			if(!$result)
			{
			return 'Указаная почта не зарегестрирована';
			}
			else
			{
				$expire = time()+3600;
				$hash	= md5($expire.$email);
				$sql	= "INSERT INTO ?n (hash,expire,email)
									VALUES(?s, ?i, ?s);";
				$ins	= $this->Db->query($sql,'fogot',$hash,$expire,$email);
				if($ins AND $this->Db->affectedRows($ins))
				{
					#если добавлена ссылка на востановление
					$link	= DOMEN . 'Auth/repasslink/' . $hash;
					$subject= 'Востановление пароля на сайте '.DOMEN;
					$body	= "По ссылке <a href='{$link}'> {$hash} </a> Вы найдете страницу с формой, где сможете ввести новый пароль. Ссылка активна в течении часа!";
					$headers= "FROM: ". strtoupper($_SERVER['SERVER_NAME']) ."\r\n";
					$headers.="Content-type:text/html; charset=utf-8";
					
					mail($email,$subject,$body,$headers);
					return 'Успешно отправлено по адрессу: '.$email;
				}
				else
				{
					return 'Ошибка работы с базой востановления';
				}
			}
		}
	return false;
	}
	
	public function access_change($hash){
		#проверка пользователя на право изменения пароля__halt_compiler
		$hash = trim($hash); 
		if(empty($hash)){ return ' Ссылка не корректна ';}
		else
		{
			$sql = "SELECT	hash,expire
					FROM	fogot
					WHERE	hash = ?s ;";
			$chk = $this->Db->getRow($sql,$hash);
			if(!$chk)	{ return 'Ссылка не корректна, возможно она устарела.<br/>Пожалуйста пройдите процедуру востановления пароля заново.<br/>Помните ссылка будет действительная только один час'; }
			$now = time();
			# Если ссылка устарела
			if($chk['expire'] - $now < 0 )
				{ return 'Ссылка устарела! <br/>Пожалуйста пройдите процедуру востановления пароля заново.<br/>Помните ссылка будет действительная только один час';  }
		}
	}
	
	public function updatePass($newPass,$hash){
		$newPass = md5($newPass);
		$sql = "SELECT	email
				FROM	fogot
				WHERE	hash = ?s ;";
		$email=$this->Db->getOne($sql,$hash);
		$sql = "UPDATE ?n SET ?n = ?s WHERE email = ?s ;";
		$upd = $this->Db->query($sql,'user','user_pass',$newPass, $email);
		if($this->Db->affectedRows($upd))
			return true;
			return false;
	}
	
	public function clearHash($hash){
		$time= time()-3600;
		$sql = "DELETE	FROM fogot
				WHERE	email = (SELECT email FROM fogot WHERE hash = ?s);";
		$del = $this->Db->query($sql,$hash);
	}
	
	public function register($raw_post_data){
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	private function isActive($user_id=null){
		$active = false;
		/* if(!$user_id)
			$user_id = $this->UserId;
		if($user_id)
		{
			$sql = "SELECT	user_active
					FROM	user
					WHERE	user_id= ?i";
			$active = (bool)$this->Db->getOne($sql,$user_id);
		} */
	
	return	$active;
	}
	
	public function checkUserExists($UserAlias,$UserPass){
	
	#look($_SESSION);
	#look($UserAlias);
	#look($UserPass);
	
		$Exists = false;
		$sql = "SELECT	COUNT(user_id)
				FROM	user
				WHERE	user_alias	= ?s
					AND user_pass	= ?s;";
		$Exists = (bool)$this->Db->getOne($sql,$UserAlias,$UserPass);	#look($UserExists);
	return $Exists;
	}
		
	function create($name,$pass,$pact=null,$squad=2,$current=false)
	{return;
		/*
		Добавление в БД данных и
		Занесение данных пользователя в сессию если параметр (текущий==true)
		перенаправление на главную.
		*/
	}
	function erase($user_id)
	{return;
		/*
		
		удалить из БД пользователя с ИД
			из таблицы пользователи
			из таблицы группы(если глава)
			из таблицы пользователи_В_группе (UIS)
		В таблице комментарием (если есть) заменить ИД на искуственый ИД=0 с искуствеными данными
		*/
	}
	function block($user_id)
	{return;
		
		/*
		заблокировать пользователя
		Дать права гость
		Установить метку active = false в таблице user
		*/
	}
	function allow($user_id,$resource)
	{return;
		/*
		Проверить в таблице User_permission запись где user_id = $id
			если запись есть  и метка= true вернуть сообщение что запись уже есть
			иначе заменить метку на true
		Если записи нет
		Добавить запись в БД в таблицу User_permission
		ID пользователя и имя ресурса. с пометкой True
		*/
	}
	function deny($user_id,$resource)
	{return;
		/*
		Проверить в таблице User_permission запись где user_id = $id
			если запись есть  и метка= false вернуть сообщение что запись уже есть
			иначе заменить метку на false
		Если записи нет
		Добавить запись в БД в таблицу User_permission
		ID пользователя и имя ресурса. с пометкой false
		*/
	}
	function showAll()
	{return;
		
		$all=array();
		/*
		показать всех пользователей
		*/

		if($this->getNavigator()->UsersCount)							//	есть ли количество пользователей
			$count = $this->getNavigator()->UsersCount;
		else
		{
			$count = "	SELECT	COUNT(*)
						FROM	user";	
			$count =	$this->DBconn->query($count,'NUM','NO');		//	посчитать пользователей
			$this->getNavigator()->UsersCount = $count[0][0];
		}
		$LIMIT = $this->getNavigator()->getLimit('User');
		$allusers ="SELECT	U.user_alias,U.user_active,U.user_img,U.user_id,
							S.squad_title_rus AS squad_title,
							UIS.squad_id
					FROM	user AS U
					LEFT OUTER JOIN userInSquad AS UIS	ON U.user_id=UIS.user_id AND UIS.userInSquad_active = 1
					LEFT OUTER JOIN squad AS S 			ON UIS.squad_id=S.squad_id
					";
		if($LIMIT)
			$allusers.="$LIMIT";
		$all['VAL'] =	$this->DBconn->query($allusers,'ASSOC','NO');

		$all['path']		=	'app/view/Auth/list.tpl';
		$all['CSS']			=	false;
		$all['css_class']	=	"class='user_list'";
	
	
	for($i=0;$i<count($all['VAL']);$i++)
	{
		if(	$all['VAL'][$i]['squad_title']==null)					//	проверяем группу
			{
			$all['VAL'][$i]['squad_title']	=	'Пользователь';
			$all['VAL'][$i]['squad_id']		=	2;
			}
		/*-----------------------------------------------*/
		if(	$all['VAL'][$i]['user_img']==null OR $all['VAL'][$i]['user_img']==" ")					//	проверяем аватар
			$all['VAL'][$i]['user_img']		=	'avatar.jpg';
		else
			$all['VAL'][$i]['user_img']		= 	$all['VAL'][$i]['user_id'].'/'.	$all['VAL'][$i]['user_img'];
			
		/*-----------------------------------------------*/
		if(	$all['VAL'][$i]['user_active']!=1)						//	проверяем активность
			{
			$all['VAL'][$i]['squad_title']	=	'Заблокироване';
			$all['VAL'][$i]['squad_id']		=	3;
			$all['VAL'][$i]['user_img']		=	'blocked.png';
			}
	}

	return $all;
	}
	
	function checkAccess($user_id=null,$resource=null,$action=null){
		$access = false;
		/*
		если user_ID НУЛЛ то $this->UserID
		если ресурс и действие НУЛЛ запустить getUserPermission
		если ресурс НУЛЛ а дествие есть - достать все ресурсы которые разрешено пользователю под это действие
		если ресурс не НУЛЛ а дествие НУЛЛ достать все действие доступные пользователю с ресурсом
		если есть ресурс и действие - вернуть тру если совпадает с БД и фолс если нет.
		*/
	
		if(!$user_id)	$user_id = $this->UserId;
		
		$permissions = $this->GetUserPermission();
		
		if(!$resource)
		{
			if(!$action)
			{
				#	если ресурс и действие НУЛЛ
				#	вернуть массив всех допустимых действий для текущего пользователя
				
				$sql = "SELECT	permission_resource AS resource, permission_value AS action
						FROM	permission
						WHERE	permission_id IN ( ?a );";
				$result = $this->Db->query($sql,$permissions);
				$return = array();
				while($row = $this->Db->fetch($result))
				{
				$return[$row['resource']][$row['action']] = true;
				}
			return $return;
			}
			else
			{
				#если ресурс НУЛЛ а дествие есть - достать все ресурсы которые разрешено пользователю под это действие
				
				$sql = "SELECT	permission_resource AS resource
						FROM	permission
						WHERE	permission_id IN ( ?a )
							AND	permission_value = ?s;";
				return $this->Db->getCol($sql,$permissions,$action);
			}
		}
		else
		{
			if(!$action)
			{
				#если ресурс не НУЛЛ а дествие НУЛЛ достать все действие доступные пользователю с ресурсом
				$result = array();
				$sql = "SELECT	permission_value AS action
						FROM	permission
						WHERE	permission_id IN ( ?a )
							AND	permission_resource = ?s;";
				$permission = $this->Db->getCol($sql,$permissions,$resource);
				foreach($permission as $perm)
				{
					$result[$perm] = true;
				}
			return $result;
			}
			else
			{
				#если есть ресурс и действие -	true если совпадает с БД и false если нет.
				
				$sql = "SELECT	COUNT(permission_id)
						FROM	permission
						WHERE	permission_id IN ( ?a )
							AND permission_resource = ?s
							AND	permission_value	= ?s;";
				return (bool)$this->Db->getOne($sql,$permissions,$resource,$action);
			}
		}
	
	return $access;
	}
	
	function GetallUserPermission($user_id = null){
		#получить список всех разршений для указаного пользователя
		if(!$user_id) $user_id = $this->UserId;
		$sql = 'SELECT	permission_id
				FROM	user_permission
				WHERE	user_id = ?i
					AND	UP_active = 1 ;';
		$s = $this->Db->getCol($sql,$user_id);
	return $s;
	}
	
	public function getUserAlias($user_id=null){
	if($user_id)
		{
		$sql = "SELECT	user_alias
				FROM	user
				WHERE	user_id= ?i";
		$alias = $this->Db->getOne($sql,$user_id);
		if($alias)
			$user_alias = $alias;
		else
			$user_alias = 'Пользователя не существует';
		}
	else
		$user_alias = $this->UserAlias;
	return $user_alias;
	}
	
	public function getUserInfo($user_id=null){
	$user = array();
		/*
		если user_ID НУЛЛ то $this->UserID
		Возвращает массив с полной информацией по пользователю
		*/
	if(!$user_id)
		$user_id=$this->UserId;
		
	$user['alias']		=	$this->getUserAlias($user_id);
	$user['avatar']		=	$this->getAvatar($user_id);
	$user['active']		=	$this->isActive($user_id);
	$user['authorized']	=	$this->Authorized;
	
	return $user;
	}
	
	
				
	
}
