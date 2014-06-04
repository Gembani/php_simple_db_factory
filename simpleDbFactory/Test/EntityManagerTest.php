<?php

namespace simpleDbFactory\Test;
use simpleDbFactory\EntityManager;
class EntityManagerTest extends \PHPUnit_Framework_TestCase {
  private static $db;
  public static function correct_credentials(){
    return  ['host' => 'localhost', 'username' => 'root', 'password' => 'wimo2010', 'database' => 'simple_db_factory'];
  }

  public static function setUpBeforeClass(){

    $credentials = self::correct_credentials();
    $host = $credentials['host'];
    $username = $credentials['username'];
    $password = $credentials['password'];
    $database = $credentials['database'];
    self::$db =  mysqli_connect($host, $username, $password, $database) or die("Error " . mysqli_error( self::$db));

    $sql = 'CREATE TABLE `test_table` (
    `test_table_id` int(11) NOT NULL AUTO_INCREMENT,
    `test_int` int(11) NOT NULL,
    `test_string` varchar(300) NOT NULL,
    PRIMARY KEY (`test_table_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
     self::$db->query($sql);

  }

  public static function tearDownAfterClass(){
     self::$db->query('DROP TABLE IF EXISTS `test_table`');
  }

  public function setUp(){

  }

  public function tearDown(){
     self::$db->query('truncate test_table');
  }

  



  /**
  * @expectedException Exception
  */
	public function testCallTwoDefinedThrowsException(){
    $a = 	new EntityManager(self::correct_credentials());
    $a->define('test_table');
    $a->define('test_table');
	}

	public function testCanAddSimpleRow(){
    $int = 2;
    $string = "test_string";
    $a = 	new EntityManager(self::correct_credentials());

    $a->define('test_table', ['test_int' => $int, 'test_string' => $string]);
    $row = $a->addRow('test_table');

    $result =  self::$db->query("select * from test_table");
    
		//verifyRowReturned	
		$this->assertEquals($row['test_int'], $int);
		$this->assertEquals($row['test_string'], $string);
		$this->assertEquals($row['test_table_id'], 1);
		
		//verifyDB
		while($row = $result->fetch_assoc()){
      $rows[]= $row;
    }
    $this->assertEquals(count($rows), 1);
    $this->assertEquals($rows[0]['test_int'], $int);
    $this->assertEquals($rows[0]['test_string'], $string);

  }
	public function testCanAddRowWithGenerators(){
    $int = 2;
    $string = "test_string";
    $a = 	new EntityManager(self::correct_credentials());

    $a->define('test_table', ['test_int' => new \simpleDbFactory\SequentialNumberGenerator(), 'test_string' => new \simpleDbFactory\StringGenerator('test_string {n}')]);
    $a->addRow('test_table');

    $result =  self::$db->query("select * from test_table");
    while($row = $result->fetch_assoc()){
      $rows[]= $row;
    }
    $this->assertEquals(count($rows), 1);
    $this->assertEquals($rows[0]['test_int'], 0);
    $this->assertEquals($rows[0]['test_string'], 'test_string 0');

  }
}