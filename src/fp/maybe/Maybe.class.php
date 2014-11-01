<?php

/**
 * Description of Maybe
 *
 * @author sirkleber
 */

abstract class Maybe extends Monad{
  public function map(Fn1 $f) {
    if($this instanceof Nothing){
      return $this;
    } else {
      return new Just($f->apply($this->get()));
    }
  }
  
  public function flatMap(Fn1 $f) {
    if($this instanceof Nothing){
      return $this;
      } else {
        return $f->apply($this->get());
      }
  }
  
  public abstract function getOrElse(Fn $f);
  
  public abstract function get();
}