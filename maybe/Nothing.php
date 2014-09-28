<?php

/**
 * Description of Nothing
 *
 * @author sirkleber
 */
require_once './Maybe.php';

class Nothing extends Maybe{
    
    private function __construct() {}
    
    public static final function Nothing(){
        return new Nothing();
    }
    
    public function getOrElse(Fn $f) {
        return $f->apply();
    }
    
    public function get() {
        throw new Exception("No such element");
    }
}
