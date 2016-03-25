<?
namespace application\models;

Class Editor
	extends	Page
{
private static $instance;

	protected function __construct(){
		parent::__construct('pages','page_id');
	}
	
	public static function Instance(){		
			if(	self::$instance	===	null)
				self::$instance	=	new Editor();
		return	self::$instance;
	}
	
	public function add($fields){
		$fields['full_cach_url'] = $this->makeFURL($fields['parent_page_id'],$fields['url']);
		$fields['active'] = !isset($fields['active'])?0:1;
		return parent::add($fields);
	}
	
	public function edit($id,$fields){
		
		$fields['full_cach_url'] = $this->makeFURL($fields['parent_page_id'],$fields['url']);
		$fields['active'] = !isset($fields['active'])?0:1;
		parent::edit($id,$fields);
		$this->changeURL($id);
		return true;
	}
	
	
	
	public function make_tree($start_lvl=0){
		$map	=	array();
		
		$pages	=	$this->getByParent($start_lvl);
		
		if(!empty($pages)){
			foreach($pages as $page){
				$page['children'] = $this->make_tree($page['page_id']);
				$map[] = $page;
			}
		}
		return $map;
	}
	
	private function makeFURL($parent_id,$url){
		if($parent_id == 0) return $url;
		
		$page = $this->getByID($parent_id);
		return $page['full_cach_url'].'/'.$url;
	}
	
	private function changeURL($id_parent){
		$sql		=	'SELECT * FROM ?n WHERE parent_page_id = ?s';
		$children	=	$this->db->getAll($sql,$this->table,$id_parent);
		$page		=	array();
		
		foreach ($children as $child){
			$page['full_cach_url']	= $this->makeFURL($child['parent_page_id'],$child['url']);
			
			$sql   = 'page_id = ?i';
			$where = $this->db->parse($sql,$child['page_id']);
			$this->update($this->table,$page,$where);
			$this->changeURL($child['page_id']);
		}
	}
	
	public function sorting($id,$fields){
		$id;//не используем
		$pages	= explode(',',$fields);
		$obj	= array();
		for($i=0;$i<count($pages);$i++){
			$page_id	= (int)$pages[$i];
			$where		= array('page_id'=>$page_id);
			$obj['child_sort'] = $i;
			$sql		= "UPDATE ?n SET ?u WHERE ?u";
			$this->db->query($sql,'pages',$obj,$where);
		}
		return true;
	}
	
}

