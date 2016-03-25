<?
namespace application\models;
use core\basic\AModel		as	AModel;

Class Page
	extends	AModel
{
private static $instance;
protected $table;
protected $pk;
	/**
	*	"protected", из-за конфликта видимости и наследования AModel::__construct и получения ключа $db...
	**/
	protected function __construct(){
		parent::__construct('pages','page_id');
	}
	
	public static function Instance(){
			if(	self::$instance	===	null)
				self::$instance	=	new Page();
		return	self::$instance;
	}
	
	public function getByURL($url){
		$sql='SELECT * FROM ?n WHERE full_cach_url=?s';
		return $this->db->getRow($sql,$this->table,$url);
	}
	
	public function getByParent($parent_id){
		$id = (int)$parent_id;
		$sql	=	'SELECT * FROM ?n WHERE ?u ORDER BY ?n,?n ASC';
		return $this->db->getAll($sql,$this->table,array('parent_page_id'=>$id),'child_sort',$this->pk);
	}
	
	
	
	
	
	
	
}

