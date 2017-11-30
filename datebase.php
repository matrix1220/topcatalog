<?
class datebase {
	private $conn;
	private $query;
	private $query_parts;
	function clear_parts() {
		$this->query_parts=[
			'into'=>'',
			'from'=>'',
			'where'=>'',
			'orderby'=>'',
			'limit'=>'',
			'set'=>''
		];
	}
	function __construct($host,$user,$pass,$db) {
		$this->conn=new mysqli($host,$user,$pass,$db);
		$this->clear_parts();
	}
	function __destruct() {
		$this->conn->close();
	}
	function build_query() {
		if(!empty($this->query_parts)) {
			if(isset($this->query_parts['select'])) {
				$this->query=$this->query_parts['select'].
				$this->query_parts['from'].
				$this->query_parts['where'].
				$this->query_parts['orderby'].
				$this->query_parts['limit'];
			} elseif(isset($this->query_parts['update'])) {
				$this->query=$this->query_parts['update'].
				$this->query_parts['set'].
				$this->query_parts['where'].
				$this->query_parts['limit'];
			} elseif(isset($this->query_parts['insert'])) {
				$this->query=$this->query_parts['insert'].
				$this->query_parts['into'].
				$this->query_parts['set'];
			} elseif(isset($this->query_parts['delete'])) {
				$this->query=$this->query_parts['delete'].
				$this->query_parts['from'].
				$this->query_parts['where'].
				$this->query_parts['limit'];
			}
			$this->clear_parts();
		}
	}
	function exec() {
		$this->build_query();
		$temp=$this->conn->query($this->query);
		if(!$temp) throw new Exception('Mysqlda xatolik: '.$this->conn->error);
		return $temp;
	}
	function fetch() {
		$q=$this->exec();
		while ($res=$q->fetch_object()) {
			yield $res;
		}
	}
	function query($q) {
		$this->query=$q;
		return $this;
	}
	function escape($t) {
		return "'".$this->conn->escape_string($t)."'";
	}
	function field($t) {
		return '`'.$t.'`';
	}
	function select() {
		foreach (func_get_args() as $value) $temp[]=$this->field($value);
		if(empty($temp)) $temp=['*'];
		$this->query_parts['select']='SELECT '.implode(',',$temp);
		return $this;
	}
	function update($p) {
		$this->query_parts['update']='UPDATE '.$p;
		return $this;
	}
	function delete() {
		$this->query_parts['delete']='DELETE';
		return $this;
	}
	function insert() {
		$this->query_parts['insert']='INSERT';
		//$temp=array_keys($p);
		//foreach ($temp as $key=>$value) $temp[$key]=$this->field($value);
		//$temp1=array_values($p);
		//foreach ($temp1 as $key=>$value) $temp1[$key]=$this->escape($value);
		//$this->query_parts['insert']='('.implode(',',$temp).') VALUES ('.implode(',',$temp1).')';
		return $this;
	}
	function set($p) {
		foreach ($p as $key=>$value) $temp[]=$this->field($key).'='.$this->escape($value);
		$this->query_parts['set']=' SET '.implode(',',$temp);
		return $this;
	}
	function from($p) {
		$this->query_parts['from']=' FROM '.$p;
		return $this;
	}
	function into($p) {
		$this->query_parts['into']=' INTO '.$p;
		return $this;
	}
	function where($p) {
		$this->query_parts['where']=' WHERE '.$p;
		return $this;
	}
	function orderby() {
		foreach (func_get_args() as $value) $temp[]=$this->field($value);
		$this->query_parts['orderby']=' ORDER BY '.implode(',',$temp);
		return $this;
	}
	function limit($p) {
		$this->query_parts['limit']=' LIMIT '.$p;
		return $this;
	}
}
?>