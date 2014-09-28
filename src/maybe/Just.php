<?php

/**
 * Description of Just
 *
 * @author sirkleber
 */
set_include_path(dirname(__FILE__) . "/../");

require_once 'fn/Fn.php';
require_once 'Maybe.php';

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
