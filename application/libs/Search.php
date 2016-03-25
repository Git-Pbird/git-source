<?
namespace application\libs;

use core\basic\AController	as	AController;
use	core\router\Router		as	Router;
use	core\database\database	as	DB;
use application\controllers	as	Cntr;
use application\models		as	Model;

Class Search
	extends AController
{
	protected $db;			// модуль для работы с бд
	
	public function __construct(){
		parent::__construct();
		self::$default_method	=	'run';
		$this->db				=	DB::Instance();
		$this->search_map		=	include_once(SEARCH_PATH);
	}
	
	public function run(){
		if(!$this->isPost())
			Router::Instance()->redirect();
		
		$this->switchMethod();
	}
	
	public function getSearchForm(){
		return $this->template->render('search/search_form.tpl',array());
	}
	
	private function switchMethod(){
		if (isset($_POST['searchIT']))
			$this->search(($_POST['searchIT']));
		
	}
	
	
	public function search($raw_string){
		if($raw_string == ''){
			// поидеи, отсеивается на Свитчере.
			$this->errors[] = 'EMTPY SEARCH BOX';
			Router::Instance()->redirect();
		}
		
		$result		= array();
		$string		= preg_replace('/[|%_\']+/','',$raw_string);
		$this->str	= htmlspecialchars($raw_string);
		
		foreach($this->search_map as $key=>$table){
			if(count($table['fields'] > 0)){
				
				// ищем в указаных полях
				$where = $this->create_condition($table['fields'], $this->str);
				
				// если есть дополнительное условие в "карте"
				$where2= ($table['where'] != '' )
							? ' AND '.$table['where'] 
							:'';
				// тянем из БД результаты
				$sql = 'SELECT * FROM ?n WHERE (?p) ?p';
				$result[$key] = $this->db->getAll($sql,$key,$where,$where2);
			}	
		}
		$this->result = $result;
		$this->viewResults();
	}
	
	private function getTemplate($table){
		return $this->search_map[$table]['template'];
	}
	
	private function create_condition($fields,$string){
		
		$sets = array();
		foreach($fields as $field){
			$sets[] = $this->db->parse('?n LIKE ?s',$field,'%'.$string.'%'); 
		}
		return implode(' OR ', $sets);
	}
	
	
	
	private function viewResults(){
		$this->template->set('main_tpl','wrappers/base_front.tpl');
		$this->template->set('title','Результат поиска',true);
		
		$menu	=	Model\Menu::Instance();
		$auth	=	new Cntr\User();
		
		$top_menu 	= $menu->sublight($menu->getTopMenu(),$url);
		$auth_bar 	= $auth->getLogInForm();
		$search_box	= $this->getsearchForm();
		
		
		foreach($this->result as $table => $value){
			if(count($value))
				$templates[] = $this->template->render($this->getTemplate($table),array('results'=>$value));
		}
		
		$content = $this->template->render(	'search/search_result.tpl',
									array(	'templates'=>$templates,
											'errors'=>$this->errors,
											'search'=>$this->str,
										));
		
		$this->template->addStyle('common', true, true);
		$this->template->addStyle('menu',	true, true);
		$this->template->set('title',		':: '.$this->str, false);
		$this->template->set('keyword',		'', true);
		$this->template->set('description',	'', true);
		$this->template->set('top_menu',	$top_menu,true);
	#	$this->template->set('side_menu',	$side_menu,true);
		$this->template->set('auth_bar',	$auth_bar,true);
		$this->template->set('search_box',	$search_box,true);
		$this->template->set('content',		$content);
		$this->template->print_page();
	}
	
}