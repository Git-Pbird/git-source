<?
namespace core\basic;
use	core\database\database		as DB;
use	core\validation\validation	as Validation;

abstract Class AModel
{

	protected $table;		// имя талицы
	protected $pk;			// первичный ключ
	protected $db;			// модуль для работы с бд
	protected $errors;		// список ошибок
	private   $valid;		// модуль валидации
	public	  $now;			// текущая метка времени
	protected $limit;
	
	protected function __construct($table, $pk){
		$this->table	=	$table;
		$this->pk		=	$pk;
		$this->limit	=	'';
		$this->errors	=	array();
		$this->valid	=	null;
		$this->db		=	DB::Instance();
		$this->now		=	date('Y-m-d H:i') . ':00';
	}

	private function load_validation(){
		if ($this->valid == null)
			$this->valid = new Validation($this->table);
		return $this->valid;
	}
	public function resetValidation(){
		$this->valid->reset();
	}
	
	public function errors(){
		return $this->errors;
	}
	
	public function getAll(){
		$sql	=	'SELECT * FROM ?n ?p';
		return $this->db->getAll($sql,$this->table, $this->limit);
	}
	
	public function setLimit($start = null, $quantity = null){
		
		if ($start == null && $quantity == null){
			$limit = '';
		}else{
			if ($start == null)
				$start = 1;
			if ($quantity == null)
				$quantity = 10;
		
			$start	=	($start - 1) * $quantity;
			if ($start > $this->count)
				$start = $this->count - $quantity;
			$limit	=	$this->db->parse(" LIMIT ?i , ?i",$start,$quantity);
		}
		$this->limit = $limit;
	}
	
	public function getAllCount(){
		if ($this->count)
			return $this->count;
		
		$sql = 'SELECT count(*) FROM ?n';
		$this->count = $this->db->getOne($sql,$this->table);
		return $this->count;
	}
	
	public function getByID($id){
		$id		=	(int)$id;
		$sql	=	'SELECT * FROM ?n WHERE ?u';
		return	$this->db->getRow($sql,$this->table,array($this->pk=>$id));
	}
	
	public function add($fields){
		#$this->errors	= array();					// обнуляем список ошибок
		$valid			= $this->load_validation();	// подгружаем модуль валидации
		$valid->reset();							//	обнуляем ошибки
		
		$valid->execute($fields);
		
		if($valid->good()){
			return $this->Insert($this->table, $valid->getObj());
		}
		$this->errors = $valid->errors();
		return false;
	}
	
	public function edit($pk, $fields){
		#$this->errors = array();  		   // обнуляем список ошибок
		$valid = $this->load_validation(); // подгружаем модуль валидации
		$valid->reset();				//	обнуляем ошибки
	
		$valid->execute($fields, $pk);
		
		if($valid->good()){
			$ins = $this->Update($this->table, $valid->getObj(), "{$this->pk} = '$pk'");
			$res = ($ins)?$ins:true;
			return $res;
		}
		$this->errors = $valid->errors();
		return false;
	}
	
	protected function insert($table,$obj){
		$colums = array_keys($obj);
		$values = array_values($obj);
		
		$sql = "INSERT INTO ?n (?t) VALUES (?a)";
		$this->db->query($sql,$table,$colums,$values);
		return $this->db->lastInsert();
	}
	
	protected function update($table,$fields,$where){
		$sql     = "UPDATE ?n SET ?u WHERE ?p"; 
		$this->db->query($sql,$table,$fields,$where);
		return $this->db->lastInsert();
	}
	
	protected function delete($table,$where){
		$sql     = "DELETE FROM ?n WHERE ?p";
		$this->db->query($sql,$table,$where);
		return true;
	}
	
	
	
	
	
	
	
}
