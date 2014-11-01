<?php

/**
 * Description of Maybe
 *
 * @author sirkleber
 */
set_include_path(dirname(__FILE__) . "/../");

require_once "fn/Fn.php";
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

class Just extends Maybe{
    private $value;
    
    public function __construct($value) {
        $this->value = $value;
    }
    
    public function getOrElse(Fn $f) {
        return $this->value;
    }
    
    public function get() {
        return $this->value;
    }
    
    public function __toString() {
        return "Just($this->value)";
    }
}

class Nothing extends Maybe{
    
    private function __construct() {}
    
    private static $not = null;
    
    /**
     * 
     * @return Maybe
     */
    public static final function Nothing(){
        if(!isset(self::$not)){
            self::$not = new Nothing();
        }
        return self::$not;
    }
    
    public function getOrElse(Fn $f) {
        return $f->apply();
    }
    
    public function get() {
        throw new Exception("No such element");
    }
    
    public function __toString() {
        return "";
    }
}