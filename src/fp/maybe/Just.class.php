<?php

/**
 * Description of Maybe
 *
 * @author sirkleber
 */

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