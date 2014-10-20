<?php

/**
 * Description of Result
 *
 * @author sirkleber
 */
abstract class Result {
  protected $value;
  protected $type;
  
  public abstract function isFailure();
  
  public function getValue(){
    return $this->value;
  }
  
  public function getType(){
    return $this->type;
  }
  
  public abstract function as_($type);
}

class ResFailure extends Result{
  
  public static function ResFailure($value){
    return new ResFailure($value);
  }
  
  private function __construct($value, $type = "json") {
    $this->value = $value;
    $this->type = $type;
  }
  
  public function as_($type) {
    return new ResFailure($this->value, $type);
  }
  
  public function isFailure() {
    return true;
  }
}

class ResSuccess extends Result{
  
  public static function ResSuccess($value){
    return new ResSuccess($value);
  }
  
  public function as_($type) {
    return new ResSuccess($this->value, $type);
  }

  
  private function __construct($value, $type = "json") {
    $this->value = $value;
    $this->type = $type;
  }  
  
  public function isFailure() {
    return false;
  }
}