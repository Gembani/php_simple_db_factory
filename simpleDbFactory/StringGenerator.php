<?php
namespace simpleDbFactory;

class StringGenerator{
  private $seed_string;
  private $child_generator;
  private $replace_pattern;

  function __construct($seed_string, $child_generator = false, $replace_pattern = '{n}'){
    $this->seed_string = $seed_string;
    $this->replace_pattern = $replace_pattern;
    if ($child_generator === false){
      $this->child_generator = new SequentialNumberGenerator();
    }else{
      $this->child_generator = $child_generator;
    }
  }

  public function next(){
    return str_replace($this->replace_pattern, $this->child_generator->next(),$this->seed_string);
  }
}
