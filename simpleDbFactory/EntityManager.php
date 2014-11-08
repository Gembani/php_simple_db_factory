<?php
namespace simpleDbFactory;

class EntityManager{
  private $db;
  private $connection_settings;
  private $table_options;
	private $table_primary_keys = [];
  function __construct($connection_settings = []) {
    $this->connection_settings = $connection_settings;
		$this->db = new drivers\Mysqli($this->connection_settings);

  }

  public function begin_transaction(){
    $this->db->begin_transaction();
  }
  public function rollback(){
    $this->db->rollback();
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
        $value = $this->db->escape_string($value);
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
    $this->db->query($sql);
		$id = $this->db->insert_id();
		$data = $this->db->query("select * from `$table_name` where `".$this->table_primary_keys[$table_name]."` = $id");
		return $data->fetch_assoc();
	}

  public function define($table_name, $options = []){
    if (is_array($this->table_options[$table_name])){
			throw new \Exception("cannot defined a table name twice");
    }
		$this->table_options[$table_name] = $options;
		$this->table_primary_keys[$table_name] = $this->db->get_primary_key($table_name);

 }
}