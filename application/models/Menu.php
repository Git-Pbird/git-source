<?
namespace application\models;
use core\basic\AModel		as	AModel;

Class Menu
	extends	AModel
{
private static $instance;

	static function Instance(){
			if(	self::$instance	===	null)
				self::$instance	=	new Menu();
		return	self::$instance;
	}
	
	protected function __construct(){
		parent::__construct('menu','menu_id');
	}
	
	public function getTopMenu(){
		$sql = 'SELECT * FROM ?n WHERE ?u AND ?u';
		$where1 = array('active'=>1);
		$where2 = array('parent_page_id'=>0);
		return $this->db->getAll($sql,'pages',$where1,$where2);
	}
	
	public function getM2Page($menu_id,$wat=''){
		$menu_id	=	(int)$menu_id;
		if(empty($wat)){
			$what = '*';
		}else{
			if(is_array($wat))
			$what = $this->db->parse('?a',$wat );
			$what = $this->db->parse('?s ',$wat );
		}
		$sql = 'SELECT	?p
				FROM	?n
				JOIN	?n
					using(page_id)
				JOIN	?n
					using(menu_id)
				WHERE	?n.?u
				ORDER BY ?n ASC';
		$where=array('menu_id'=>$menu_id);
		return $this->db->getAll($sql,$what,'page_in_menu','pages','menu','page_in_menu',$where,'num_sort');
	}
	
	public function getMenuList($page_id){
		$page_id= (int)$page_id;
		$sql = 'SELECT	?n
				FROM	?n
				WHERE	?u
				ORDER BY ?n ASC';
		$where=array('page_id'=>$page_id);
		return $this->db->getCol($sql,'menu_id','menu_in_page',$where,'num_sort');
	}
	
	public function addM2Page($page_id,$ids){
		if(in_array('',$ids))
			return false;
		for($i=0;$i<count($ids);$i++){
			$obj['page_id'] = $page_id;
			$obj['menu_id'] = $ids[$i];
			$obj['num_sort'] = $i;
			$this->insert('menu_in_page',$obj);
		}
		return true;
	}
	public function editM2Page($id,$fields){

		$id = (int)$id;
		$sql = 'page_id = ?i';
		$where = $this->db->parse($sql,$id);
		$this->delete('menu_in_page',$where);
			if($fields=== null OR in_array('',$fields))
				return true;
		return $this->addM2Page($id,$fields);
	}
	
	public function add($fields){
		$id = parent::add($fields);
		$obj = array('menu_id'=>$id);
		for($i=0;$i<count($fields['pages']);$i++){
			$obj['page_id'] = $fields['pages'][$i];
			$obj['num_sort'] = $i;
			$this->insert('page_in_menu',$obj);
		}
		return $id;
	}
	
	public function getByID($id){

		$menu	=	parent::getByID($id);
		
		$sql	=	'SELECT * FROM ?n WHERE ?u';
		$where	=	array('menu_id'=>$menu['menu_id']);
		$pages	=	$this->db->getAll($sql,'page_in_menu',$where);
		
		$array	=	array();
		foreach($pages as $page){
			$array[] = $page['page_id'];
		}
		
		$menu['pages'] = $array;
		return $menu;
	}
	
	public function edit($id,$fields){
		
		$id = (int)$id;
		$sql = 'menu_id = ?i';
		$where = $this->db->parse($sql,$id);
		$this->delete('page_in_menu',$where);
		parent::edit($id,$fields);
		
		$obj = array('menu_id'=>$id);
		
		for($i=0;$i<count($fields['pages']);$i++){
			$obj['page_id'] = $fields['pages'][$i];
			$obj['num_sort'] = $i;
			$this->insert('page_in_menu',$obj);
		}
		return true;
	}
	
	public function sorting($menu_id,$pages){
		$pages	= explode(',',$pages);
		$obj	= array();
		for($i=0;$i<count($pages);$i++){
			$page_id	= (int)$pages[$i];
			$where1		= array('menu_id'=>$menu_id);
			$where2		= array('page_id'=>$page_id);
			$obj['num_sort'] = $i;
			$sql		= "UPDATE ?n SET ?u WHERE ?n.?u AND ?u";
			$this->db->query($sql,'page_in_menu',$obj,'page_in_menu',$where1,$where2);
		}
		return true;
	}
	
	
	
	public function sublight($menu_arr,$url,$link_title=''){
		if(!$link_title) 
			$link_title	= 'full_cach_url';
		
		foreach($menu_arr as $key=>$val){
			$menu_arr[$key]['sublight'] = $this->is_active($url,$val[$link_title]);
		}
		return $menu_arr;
	}
	
	private function is_active($url,$link_url){
		if(strpos($url,$link_url) === 0){
			$smb1 = substr($url,strlen($link_url),1);
		//	$smb2 = trim(substr($url,strlen($link_url)+1,1));
			if($smb1 === false || $smb1 == '/') //&& $smb2 === false)
			{
				return true;
			}
		}
		return false;
	}
}

