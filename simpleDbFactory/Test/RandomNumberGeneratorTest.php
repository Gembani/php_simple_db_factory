<?php

namespace simpleDbFactory\Test;

use simpleDbFactory\RandomNumberGenerator;

class RandomNumberGeneratorTest extends \PHPUnit_Framework_TestCase {
	public function xtestRandomNoParameters(){
		$min = 0;
		$max =  mt_getrandmax();
		$random = new RandomNumberGenerator();
		$number = $random->next();
		
		$this->assertLessThanOrEqual($max, $number);
		$this->assertGreaterThanOrEqual($min, $number);
	}
	
	public function testRandomWithParams(){
		$min = 2;
		$max = 5;
		$random = new RandomNumberGenerator($min, $max);
		
		$number = $random->next();
		$this->assertLessThanOrEqual($max, $number);
		$this->assertGreaterThanOrEqual($min, $number);
		
	}
	
}