<?php

/**
 * Description of Maybe
 *
 * @author sirkleber
 */
require_once 'typeclasses/Monad.php';

abstract class Maybe extends Monad{
    public function map($f) {
        if($this instanceof Nothing){
            return $this;
        } else {
            return new Just($f($this->get()));
        }
    }
    
    public function flatMap($f) {
        if($this instanceof Nothing){
            return $this;
        } else {
            return $f($this->get());
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
