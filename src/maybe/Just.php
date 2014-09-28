<?php

/**
 * Description of Just
 *
 * @author sirkleber
 */
require_once '../fn/Fn.php';
require_once './Maybe.php';

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
