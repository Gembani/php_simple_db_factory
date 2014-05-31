<?php

namespace simpleDbFactory\Test;

use simpleDbFactory\SequentialNumberGenerator;

class SequentialNumberGeneratorTest extends \PHPUnit_Framework_TestCase {
	public function testRandomNoParameters(){
		$random = new SequentialNumberGenerator();
		$this->assertEquals(0, $random->next());
		$this->assertEquals(1, $random->next());
		$this->assertEquals(2, $random->next());	
	}
	
	public function testRandomStartUsed(){
		$start = 100;
		$random = new SequentialNumberGenerator($start);
		$this->assertEquals(100, $random->next());
		$this->assertEquals(101, $random->next());
		$this->assertEquals(102, $random->next());	
	}
}