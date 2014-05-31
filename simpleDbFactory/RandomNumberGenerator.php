<?php
namespace simpleDbFactory;
	
class RandomNumberGenerator{
	private $min;
	private $max;
	
	
	function __construct($min = 0, $max = false){
		if ($max === false){
			$this->max = mt_getrandmax();
		}else{
			$this->max = $max;
		}
		$this->min = $min;
	}
	
	public function next(){
		return mt_rand($this->min, $this->max);
	}
}
