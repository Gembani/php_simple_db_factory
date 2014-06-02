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
    $a = 	new EntityManager(self::correct_credentials());
    $this->assertTrue(
    method_exists($a->db, 'query'), 'Class does not have method');
  }



  /**
  * @expectedException Exception
  */
	public function testCallTwoDefinedThrowsException(){
    $a = 	new EntityManager(self::correct_credentials());
    $a->define('test_table');
    $a->define('test_table');
	}

	public function testDefineExists(){
    $int = 2;
    $string = "test_string";
    $a = 	new EntityManager(self::correct_credentials());

    $a->define('test_table', ['test_int' => $int, 'test_string' => $string]);
    $a->addRow('test_table');

    $result =  self::$db->query("select * from test_table");
    while($row = $result->fetch_assoc()){
      $rows[]= $row;
    }
    $this->assertEquals(count($rows), 1);
    $this->assertEquals($rows[0]['test_int'], $int);
    $this->assertEquals($rows[0]['test_string'], $string);

  }
}