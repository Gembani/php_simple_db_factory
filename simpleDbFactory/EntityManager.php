<?php
namespace simpleDbFactory;

class EntityManager{
  public $db;
  private $connection_settings;
  private $table_options;
	private $table_primary_keys = [];
  function __construct($connection_settings = []) {
    $this->connection_settings = $connection_settings;
    if (!array_key_exists('host', $this->connection_settings)){
      throw new \InvalidArgumentException('host required', 10);
    }
    if (!array_key_exists('username', $this->connection_settings)){
      throw new \InvalidArgumentException('username required', 10);
    }
    if (!array_key_exists('password', $this->connection_settings)){
      throw new \InvalidArgumentException('password required', 10);
    }
    if (!array_key_exists('database', $this->connection_settings)){
      throw new \InvalidArgumentException('database required', 10);
    }
    $this->connect();
  }

  private function connect(){
    $host = $this->connection_settings['host'];
    $username = $this->connection_settings['username'];
    $password = $this->connection_settings['password'];
    $database = $this->connection_settings['database'];
    $this->db = mysqli_connect($host, $username, $password, $database) or die("Error " . mysqli_error($this->db));
  }

  private function query($sql){
    $data = $this->db->query($sql);
    if($data === false) {
      throw new \Exception('Wrong SQL: ' . $sql . ' Error: ' .$this->db->error);
    }
    return $data;
  }
	private function getPrimaryKey($table){
		$data = $this->query("SHOW KEYS FROM $table WHERE Key_name = 'PRIMARY'");
		$row = $data->fetch_assoc();
		return $row['Column_name'];
	}
  public function addRow($table_name, $overrides = []){
    $to_add = array_merge($this->table_options[$table_name], $overrides);
    $values = [];
    $keys = [];
    foreach ($to_add as $key => $pre_converted_value){
      $keys[]= "`$key`";
      
			
			if (method_exists($pre_converted_value, 'next')){
				$value = $pre_converted_value->next();
			}else{
				$value = $pre_converted_value;
			}
			
			if (is_string($value)){
        $value = mysqli_real_escape_string($this->db, $value);
        $values[]="'$value'";
      }else if(is_numeric($value)){
        $values[]="$value";
      }else {
        echo 'not supported yet';
      }
    }
    $keys_string = implode(", ", $keys);
    $values_string = implode(", ", $values);
    $sql = "INSERT INTO `$table_name` ($keys_string) VALUES ($values_string)";
    $this->query($sql);
		$id = mysqli_insert_id($this->db);
		
		$data = $this->query("select * from `$table_name` where `".$this->table_primary_keys[$table_name]."` = $id");
		return $data->fetch_assoc();
	}

  public function define($table_name, $options = []){
    if (is_array($this->table_options[$table_name])){
			throw new \Exception("cannot defined a table name twice");
    }
		$this->table_options[$table_name] = $options;
		$this->table_primary_keys[$table_name] = $this->getPrimaryKey($table_name);
    
 }
}