<?php
namespace simpleDbFactory;

class SequentialNumberGenerator{
  private $current;
  function __construct($start = 0){
    $this->current = $start;
  }
  public function next(){
    $to_return = $this->current;
    $this->current ++ ;
    return $to_return;
  }
}
