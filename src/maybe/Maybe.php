<?php

/**
 * Description of Maybe
 *
 * @author sirkleber
 */
set_include_path(dirname(__FILE__) . "/../");
require_once "fn/Fn.php";
require_once 'fn/Fn1.php';
require_once 'typeclasses/Monad.php';

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
