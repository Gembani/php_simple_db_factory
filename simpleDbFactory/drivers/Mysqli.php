<?php
namespace simpleDbFactory\drivers;

class Mysqli{
  private $db;
  private $connection_settings;
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
	
	public function insert_id(){
		return mysqli_insert_id($this->db);
	}
	public function escape_string($value){
		return mysqli_real_escape_string($this->db, $value);
	}
  public function query($sql){
    $data = $this->db->query($sql);
    if($data === false) {
      throw new \Exception('Wrong SQL: ' . $sql . ' Error: ' .$this->db->error);
    }
    return $data;
  }
	public function get_primary_key($table){
		$data = $this->query("SHOW KEYS FROM $table WHERE Key_name = 'PRIMARY'");
		$row = $data->fetch_assoc();
		return $row['Column_name'];
	}

}