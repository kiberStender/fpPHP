<?php

/**
 * Description of Nothing
 *
 * @author sirkleber
 */

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