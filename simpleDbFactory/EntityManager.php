<?php
namespace simpleDbFactory;

class EntityManager{
  public $db;
  private $connection_settings;
  private $table_options;
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

  public function addRow($table_name, $overrides = []){
    $to_add = array_merge($this->table_options[$table_name], $overrides);
    $values = [];
    $keys = [];
    foreach ($to_add as $key => $value){
      $keys[]= "`$key`";
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
  }

  public function define($table_name, $options = []){
    // $sql = "describe `$table_name`";
    // $data = $this->query($sql);
    //TODO
    // if ($table_options[$table_name]){
    // 	throw new \Expeption("cannot defined a table name twice");
    // }
    $this->table_options[$table_name] = $options;
    // while($row = $data->fetch_assoc()){
    // 	print_r($row);
    // }
  }
}