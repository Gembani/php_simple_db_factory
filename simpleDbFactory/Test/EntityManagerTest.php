<?php

namespace simpleDbFactory\Test;
use simpleDbFactory\EntityManager;
class EntityManagerTest extends \PHPUnit_Framework_TestCase {
  
	public function correct_credentials(){
		return  ['host' => 'localhost', 'username' => 'root', 'password' => 'wimo2010', 'database' => 'simple_db_factory'];
	}
	public function setUp(){
		$this->connect();
	}
	public function tearDown(){
		$this->db->query('truncate test_table');
	}
	public function connect(){
		$credentials = $this->correct_credentials();
		$host = $credentials['host'];
		$username = $credentials['username'];
		$password = $credentials['password'];
		$database = $credentials['database'];
		$this->db = mysqli_connect($host, $username, $password, $database) or die("Error " . mysqli_error($this->db)); 
	}
	/**
      * @expectedException InvalidArgumentException
	*/
	public function testNewEntityManagerNoArgumentsThrows(){
		new EntityManager();
	}
  /**
      * @expectedException InvalidArgumentException
	*/
	public function testNewEntityManagerHostOnlyThrows(){
		new EntityManager(['host' => "asdfas"]);
	}
  /**
      * @expectedException InvalidArgumentException
	*/
	public function testNewEntityManagerHostUsernameOnlyThrows(){
		new EntityManager(['host' => "asdfas",'username' => "asdfas"]);
	}

  /**
      * @expectedException InvalidArgumentException
	*/
	public function testNewEntityManagerHostUsernamePasswordOnlyThrows(){
		new EntityManager(['host' => "asdfas",'username' => "asdfas", 'password' => 'password']);
	}


	public function testConnectEntityManager(){
		$a = 	new EntityManager($this->correct_credentials());
		$this->assertTrue(
		  method_exists($a->db, 'query'), 
		  'Class does not have method'
		);
	}
  
	
	
	public function testDefineExists(){
		$int = 2;
		$string = "test_string";
		$a = 	new EntityManager($this->correct_credentials());
		
		$a->define('test_table', ['test_int' => $int, 'test_string' => $string]);
		$a->addRow('test_table');
		
		$result = $this->db->query("select * from test_table");
		while($row = $result->fetch_assoc()){
			$rows[]= $row;
		}
		$this->assertEquals(count($rows), 1);
		$this->assertEquals($rows[0]['test_int'], $int);
		$this->assertEquals($rows[0]['test_string'], $string);
		
	}
}