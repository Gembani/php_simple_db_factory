<?php

namespace simpleDbFactory\Test;
use simpleDbFactory\drivers\Mysqli;
class MysqliDriverTest extends \PHPUnit_Framework_TestCase {
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
  public function testNewMysqliNoArgumentsThrows(){
		new Mysqli();
  }
  /**
      * @expectedException InvalidArgumentException
  */
  public function testNewMysqliHostOnlyThrows(){
    new Mysqli(['host' => "asdfas"]);
  }
  /**
  * @expectedException InvalidArgumentException
  */
  public function testNewMysqliHostUsernameOnlyThrows(){
    new Mysqli(['host' => "asdfas",'username' => "asdfas"]);
  }

  /**
      * @expectedException InvalidArgumentException
  */
  public function testNewMysqliHostUsernamePasswordOnlyThrows(){
    new Mysqli(['host' => "asdfas",'username' => "asdfas", 'password' => 'password']);
  }


  public function testConnectMysqli(){
    $a = 	new Mysqli(self::correct_credentials());
    $data = $a->query("select * from test_table");
		$this->assertEquals($data->field_count, 3);
  }

	public function testGetPrimaryKeyMysqli (){
    $a = 	new Mysqli(self::correct_credentials());
    $this->assertEquals($a->get_primary_key('test_table'),'test_table_id');
	}

	public function testEscapedString (){
    $a = 	new Mysqli(self::correct_credentials());
    $this->assertEquals($a->escape_string('helloWorld'),'helloWorld');
	}

	
	public function testInsertIdMysqli (){
    $a = 	new Mysqli(self::correct_credentials());
    $this->assertEquals($a->insert_id(), 0);
	}
	
	
	

}