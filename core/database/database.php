<?php
namespace	core\database;

Class	Database
{
static	$debug		=	true;
static	$_instance	=	null;
public	$option_pdo	=	array();	//	array
protected	$_DB	=	null;		//	PhpDataObject сам обьект
private	$FetchMode;


	private function __clone(){}
	private function __construct()
	{
#		addLog(self::$debug,SYS_LOG,"[DB] Подключен класс работы с Базой данных");
#		addLog(self::$debug,DB_LOG,"Загружен встроеный драйвер работы с базой данных для \"{$this->driver['driver_name']}\"" );
		$this->setConnection('mysql');
	}
	
	function	setConnection($driver_name)
	{
		$driver_name = strtolower($driver_name);
		switch($driver_name)
			{
			case "sqlite":
						$dsn = "sqlite:".DOCPATH."/"."{DB_NAME}".'.db';
					break;
			case "mysql":
						$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
					break;
			case "mysqli":
					break;
			case "":
					break;		
			case "":
					break;
			default:
					die("База Данных не определена, проверьте наличие драйвера драйвер");
					break;
			}
			
		$option_pdo['PDO::ATTR_DEFAULT_FETCH_MODE'] = $this->setFetchMode();
		$this->_DB = new \PDO($dsn, DB_USER, DB_PASS, $option_pdo);
		
	}
		
	function setFetchMode($mode='')
	{
		if (!$mode)
		{
			$fetch_mode = FETCH_MODE;
		}
		else
		{
			$fetch_mode = trim(strtoupper($mode));
		}
		
		switch($fetch_mode)
			{
			case "ASSOC":
					$this->FetchMode = 'ASSOC';
					break;
			case "NUM":
					$this->FetchMode = 'NUM';
					break;
			case "BOTH":
					$this->FetchMode = 'BOTH';
					break;
			default:
				$this->error("Неверно указан тип вызова FETCH_MODE, передается : " . $mode);
			}
			
		return $this->FetchMode;
	
	}
	
	static function Instance()
	{
#		addLog( self::$debug,SYS_LOG,"[DB] Запрошен экземпляр класса Базы данных ". __CLASS__);
			if(	self::$_instance	===	null)
				self::$_instance	=	new Database();
		return	self::$_instance;
	}
	
	/**
	 * Examples:
	 * $db->query("DELETE FROM table WHERE id=?i", $id);
	 *
	 * @param string $query - an SQL query with placeholders
	 * @param mixed  $arg,... unlimited number of arguments to match placeholders in the query
	 * @return resultource|FALSE
	 */
	public function query()
	{
		return $this->rawQuery($this->prepareQuery(func_get_args()));
	}
	
	/**
	 * Function to fetch single row. 
	 * 
	 * @param resultource $result - PDO result
	 * @param int $mode - optional fetch mode, PDO::FETCH_NUM|PDO::FETCH_ASSOC|PDO::FETCH_BOTH, default DEFAULT_FETCH_MODE
	 * @return array|FALSE
	 */
	public function fetch($result)
	{
		switch($this->FetchMode)
			{
			case "ASSOC":
					return	$result->fetch(\PDO::FETCH_ASSOC);
					break;
			case "NUM":
					return	$result->fetch(\PDO::FETCH_NUM);
					break;
			case "BOTH":
					return	$result->fetch(\PDO::FETCH_BOTH);
					break;
			default:
				break;
			}
		
	}
	
	/**
	 * Function to get number of affected rows. 
	 * 
	 * @return int
	 */
	public function affectedRows($result)
	{
		return $result->rowCount();
	}

	/**
	 * Conventional function to get last insert id. 
	 * 
	 * @return int
	 */
	public function lastInsert()
	{
		return $this->_DB->lastInsertId();
	}

	/**
	 * Function to get number of rows in the result set. 
	 * 
	 * @param resultource $result
	 * @return int
	 */
	public function numRows($result)
	{
		return count($result);
	}

	/**
	 * Function to free the result set. 
	 */
	public function free($result)
	{
		$result->closeCursor();
	}
	
	/**
	 * Helper function to get scalar value right out of query and optional arguments
	 * 
	 * Examples:
	 * $name = $db->getOne("SELECT name FROM table WHERE id=1");
	 * $name = $db->getOne("SELECT name FROM table WHERE id=?i", $id);
	 *
	 * @param string $query - an SQL query with placeholders
	 * @param mixed  $arg,... unlimited number of arguments to match placeholders in the query
	 * @return string|FALSE either first column of the first row of result set or FALSE if none found
	 */
	public function getOne()
	{
		$query = $this->prepareQuery(func_get_args());
		if ($result = $this->rawQuery($query))
		{
			$row = $this->fetch($result);
			if (is_array($row))
			{
				return reset($row);
			}
			$this->free($result);
		}
		return FALSE;
	}
	
	/**
	 * Helper function to get single row right out of query and optional arguments
	 * 
	 * Examples:
	 * $data = $db->getRow("SELECT * FROM table WHERE id=1");
	 * $data = $db->getOne("SELECT * FROM table WHERE id=?i", $id);
	 *
	 * @param string $query - an SQL query with placeholders
	 * @param mixed  $arg,... unlimited number of arguments to match placeholders in the query
	 * @return array|FALSE either associative array contains first row of resultset or FALSE if none found
	 */
	public function getRow()
	{
		$query = $this->prepareQuery(func_get_args());
		if ($result = $this->rawQuery($query))
		{
			$return = $this->fetch($result);
			$this->free($result);
			return $return;
		}
		return FALSE;
	}
	
	/**
	 * Helper function to get single column right out of query and optional arguments
	 * 
	 * Examples:
	 * $ids = $db->getCol("SELECT id FROM table WHERE cat=1");
	 * $ids = $db->getCol("SELECT id FROM tags WHERE tagname = ?s", $tag);
	 *
	 * @param string $query - an SQL query with placeholders
	 * @param mixed  $arg,... unlimited number of arguments to match placeholders in the query
	 * @return array|FALSE either enumerated array of first fields of all rows of resultset or FALSE if none found
	 */
	public function getCol()
	{
		$return   = array();
		$query = $this->prepareQuery(func_get_args());
		if ( $result = $this->rawQuery($query) )
		{
			while($row = $this->fetch($result))
			{
				$return[] = reset($row);
			}
			$this->free($result);
		}
		return $return;
	}
	
	/**
	 * Helper function to get all the rows of resultset right out of query and optional arguments
	 * 
	 * Examples:
	 * $data = $db->getAll("SELECT * FROM table");
	 * $data = $db->getAll("SELECT * FROM table LIMIT ?i,?i", $start, $rows);
	 *
	 * @param string $query - an SQL query with placeholders
	 * @param mixed  $arg,... unlimited number of arguments to match placeholders in the query
	 * @return array enumerated 2d array contains the resultset. Empty if no rows found. 
	 */
	public function getAll()
	{
		$return	= array();
		$query	= $this->prepareQuery(func_get_args());
		if ( $result = $this->rawQuery($query) )
		{
			while($row = $this->fetch($result))
			{
				$return[] = $row;
			}
			$this->free($result);
		}
		return $return;
	}
	
	/**
	 * Helper function to get all the rows of resultset into indexed array right out of query and optional arguments
	 * 
	 * Examples:
	 * $data = $db->getInd("id", "SELECT * FROM table");
	 * $data = $db->getInd("id", "SELECT * FROM table LIMIT ?i,?i", $start, $rows);
	 *
	 * @param string $index - name of the field which value is used to index resulting array
	 * @param string $query - an SQL query with placeholders
	 * @param mixed  $arg,... unlimited number of arguments to match placeholders in the query
	 * @return array - associative 2d array contains the resultset. Empty if no rows found. 
	 */
	public function getInd()
	{
		$args  = func_get_args();
		$index = array_shift($args);
		$query = $this->prepareQuery($args);

		$return= array();
		if ( $result	= $this->rawQuery($query) )
		{
			while($row	= $this->fetch($result))
			{
				$return[$row[$index]] = $row;
			}
			$this->free($result);
		}
		return $return;
	}
	
	/**
	 * Helper function to get a dictionary-style array right out of query and optional arguments
	 * 
	 * Examples:
	 * $data = $db->getIndCol("name", "SELECT name, id FROM cities");
	 *
	 * @param string $index - name of the field which value is used to index resulting array
	 * @param string $query - an SQL query with placeholders
	 * @param mixed  $arg,... unlimited number of arguments to match placeholders in the query
	 * @return array - associative array contains key=value pairs out of resultset. Empty if no rows found. 
	 */
	public function getIndCol()
	{
		$args  = func_get_args();
		$index = array_shift($args);
		$query = $this->prepareQuery($args);

		$return= array();
		if ( $result	= $this->rawQuery($query) )
		{
			while($row	= $this->fetch($res))
			{
				$key = $row[$index];
				unset($row[$index]);
				$return[$key] = reset($row);
			}
			$this->free($result);
		}
		return $return;
	}
	
	/**
	 * Function to parse placeholders either in the full query or a query part
	 * unlike native prepared statements, allows ANY query part to be parsed
	 * 
	 * useful for debug
	 * and EXTREMELY useful for conditional query building
	 * like adding various query parts using loops, conditions, etc.
	 * already parsed parts have to be added via ?p placeholder
	 * 
	 * Examples:
	 * $query = $db->parse("SELECT * FROM table WHERE foo=?s AND bar=?s", $foo, $bar);
	 * echo $query;
	 * 
	 * if ($foo) {
	 *     $qpart = $db->parse(" AND foo=?s", $foo);
	 * }
	 * $data = $db->getAll("SELECT * FROM table WHERE bar=?s ?p", $bar, $qpart);
	 *
	 * @param string $query - whatever expression contains placeholders
	 * @param mixed  $arg,... unlimited number of arguments to match placeholders in the expression
	 * @return string - initial expression with placeholders substituted with data. 
	 */
	public function parse()
	{
		return $this->prepareQuery(func_get_args());
	}
	
	/**
	 * function to implement whitelisting feature
	 * sometimes we can't allow a non-validated user-supplied data to the query even through placeholder
	 * especially if it comes down to SQL OPERATORS
	 * 
	 * Example:
	 *
	 * $order = $db->whiteList($_GET['order'], array('name','price'));
	 * $dir   = $db->whiteList($_GET['dir'],   array('ASC','DESC'));
	 * if (!$order || !dir) {
	 *     throw new http404(); //non-expected values should cause 404 or similar response
	 * }
	 * $sql  = "SELECT * FROM table ORDER BY ?p ?p LIMIT ?i,?i"
	 * $data = $db->getArr($sql, $order, $dir, $start, $per_page);
	 * 
	 * @param	string $iinput	- field name to test
	 * @param	array  $allowed	- an array with allowed variants
	 * @param	string $default	- optional variable to set if no match found. Default to false.
	 * @return	string|FALSE	- either sanitized value or FALSE
	 */
	public function whiteList($input,$allowed,$default=FALSE)
	{
		$found = array_search($input,$allowed);
		return ($found === FALSE) ? $default : $allowed[$found];
	}
	
	/**
	 * function to filter out arrays, for the whitelisting purposes
	 * useful to pass entire superglobal to the INSERT or UPDATE query
	 * OUGHT to be used for this purpose, 
	 * as there could be fields to which user should have no access to.
	 * 
	 * Example:
	 * $allowed = array('title','url','body','rating','term','type');
	 * $data    = $db->filterArray($_POST,$allowed);
	 * $sql     = "INSERT INTO ?n SET ?u";
	 * $db->query($sql,$table,$data);
	 * 
	 * @param  array $input   - source array
	 * @param  array $allowed - an array with allowed field names
	 * @return array filtered out source array
	 */
	public function filterArray($input,$allowed)
	{
		foreach(array_keys($input) as $key )
		{
			if ( !in_array($key,$allowed) )
			{
				unset($input[$key]);
			}
		}
		return $input;
	}
	
	/**
	 * Function to get last executed query. 
	 * 
	 * @return string|NULL either last executed query or NULL if were none
	 */
	public function lastQuery()
	{
		$last = end($this->stats);
		return $last['query'];
	}
	
	/**
	 * Function to get all query statistics. 
	 * 
	 * @return array contains all executed queries with timings and errors
	 */
	public function getStats()
	{
		return $this->stats;
	}
	
	/**
	 * private function which actually runs a query against Mysql server.
	 * also logs some stats like profiling info and error message
	 * 
	 * @param string $query - a regular SQL query
	 * @return mysqli result resource or FALSE on error
	 */
	private function rawQuery($query)
	{
		$start = microtime(TRUE);
		$result= $this->_DB->query($query);
		$timer = microtime(TRUE) - $start;

		$this->stats[] = array(
			'query' => $query,
			'start' => $start,
			'timer' => $timer,
		);
		if (!$result)
		{
			$error = $this->_DB->errorInfo();
			
			end($this->stats);
			$key = key($this->stats);
			$this->stats[$key]['error_num']	= $error[1];
			$this->stats[$key]['error_info'] = $error[2];
			$this->cutStats();
			
			$this->error("$error. Полный запрос: [$query]");
		}
		$this->cutStats();
		return $result;
	}
	
	private function prepareQuery($args)
	{
		$query = '';
		$raw   = array_shift($args);
		$array = preg_split('~(\?[nsiuapt])~u',$raw,null,PREG_SPLIT_DELIM_CAPTURE);
		$anum  = count($args);
		$pnum  = floor(count($array) / 2);
		if ( $pnum != $anum )
		{
			$this->error("Количество аргументов ($anum) не соответствует количеству заменителей ($pnum) в [$raw]");
		}

		foreach ($array as $i => $part)
		{
			if ( ($i % 2) == 0 )
			{
				$query .= $part;
				continue;
			}

			$value = array_shift($args);
			switch ($part)
			{
				case '?n':
					$part = $this->escapeIdent($value);
					break;
				case '?s':
					$part = $this->escapeString($value);
					break;
				case '?i':
					$part = $this->escapeInt($value);
					break;
				case '?a':
					$part = $this->createIN($value);
					break;
				case '?u':
					$part = $this->createSET($value);
					break;
				case '?t':
					$part = $this->createIdentArray($value);
					break;
				case '?p':
					$part = $value;
					break;
			}
			$query .= $part;
		}
		return $query;
	}
	
	private function escapeInt($value)
	{
		if ($value === NULL)
		{
			return 'NULL';
		}
		if(!is_numeric($value))
		{
			$this->error("Числовой (?i) заменитель ожидает число, передается : ".gettype($value));
			return FALSE;
		}
		if (is_float($value))
		{
			$value = number_format($value, 0, '.', ''); // may lose precision on big numbers
		} 
		return $value;
	}

	private function escapeString($value)
	{
		if ($value === NULL)
		{
			return 'NULL';
		}
		
	return	$this->_DB->quote($value);
	}

	private function escapeIdent($value)
	{
		if ($value)
		{
			return "`".str_replace("`","``",$value)."`";
		} else {
		$this->error("Пустое значение на месте идентификатора (?n)");
		}
	}

	private function createIN($data)
	{
		if (!is_array($data))
		{
			$this->error("Значение для IN (?a) должно быть массивом");
			return;
		}
		if (!$data)
		{
			return 'NULL';
		}
		$query = $comma = '';
		foreach ($data as $value)
		{
			$query .= $comma.$this->escapeString($value);
			$comma  = ",";
		}
		return $query;
	}

	private function createSET($data)
	{
		if (!is_array($data))
		{
			$this->error("SET (?u) Заменитель ожидает массив, передается : ".gettype($data));
			return;
		}
		if (!$data)
		{
			$this->error("Передан пустой массив SET (?u)");
			return;
		}
		$query = $comma = '';
		foreach ($data as $key => $value)
		{
			$query .= $comma.$this->escapeIdent($key).'='.$this->escapeString($value);
			$comma  = ",";
		}
		return $query;
	}
	
	private function createIdentArray($data){
		if (!is_array($data))
		{
			$this->error("IdentArray (?t) Заменитель ожидает массив, передается : ".gettype($data));
			return;
		}
		if (!$data)
		{
			$this->error("Передан пустой массив IdentArray (?t)");
			return;
		}
		$query = $comma = '';
		
		foreach($data as $key=>$value){
			$value = trim($value);
			$colums[]	=	$this->escapeIdent($value);
		}
		return implode(',',$colums);
	}
	

	private function error($err)
	{
		$err  = __CLASS__.": ".$err;

		if ( self::$debug == true )
		{
			$err .= ". Ошибка вызвана в ".$this->caller();
			trigger_error($err,E_USER_ERROR);
		} else {
			throw new $this->exname($err);
		}
	}
	
	private function caller()
	{
		$trace  = debug_backtrace();
		$caller = '';
		foreach ($trace as $t)
		{
			if ( isset($t['class']) && $t['class'] == __CLASS__ )
			{
				$caller = $t['file']." на строке ".$t['line'];
			} else {
				break;
			}
		}
		return $caller;
	}

	/**
	 * On a long run we can eat up too much memory with mere statsistics
	 * Let's keep it at reasonable size, leaving only last 100 entries.
	 */
	private function cutStats()
	{
		if ( count($this->stats) > 100 )
		{
			reset($this->stats);
			$first = key($this->stats);
			unset($this->stats[$first]);
		}
	}
	
}
