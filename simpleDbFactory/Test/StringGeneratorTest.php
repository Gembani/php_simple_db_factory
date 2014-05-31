<?php

namespace simpleDbFactory\Test;

use simpleDbFactory\StringGenerator;

class StringGeneratorTest extends \PHPUnit_Framework_TestCase {
	public function testRandomNoParameters(){
		$random = new StringGenerator("hello {n}");
		$this->assertEquals("hello 0", $random->next());
		$this->assertEquals("hello 1", $random->next());
		$this->assertEquals("hello 2", $random->next());	
	}
	public function testRandomWithInjection(){
		
		$injected = $this->getMock('StringGenerator', ['next']);
		$inject_return_value = "injected_return_payload";
		$injected->expects($this->once())->method('next')->with()->will($this->returnValue($inject_return_value));
		$random = new StringGenerator("hello {n}", $injected);
		$this->assertEquals("hello $inject_return_value", $random->next());
	}
	
}