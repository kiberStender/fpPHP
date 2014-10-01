<?php

/**
 * Description of State
 *
 * @author sirkleber
 */
namespace state;

require_once 'typeclasses/Monad.php';

class State extends Monad{
    /**
     *
     * @var Fn1
     */
    private $run;
    
    function __construct($run) {
        $this->run = $run;
    }
    
    public function map($f) {
        return new State(function($s) use($f){
            $t = $this->run($s);
            return array($t[0], $f($t[1]));
        });
    }

    public function flatMap($f) {
        return new State(function($s) use($f){
            $t = $this->run($s);
            return $f($t[1])->run($t[0]);
        });
    }
    
    public function evaluate($s){
        $t = $this->run($s);
        return $t[1];
    }
    
    public static final function insert($a){
        return new State(function($s) use($a){
            return array($s, $a);
        });
    }
    
    public static final function get($f){
        return new State(function($s) use($f){
            return array($s, $f($s));
        });
    }
    
    public static final function mod(Fn1 $f){
        return new State(function($s) use($f){
            return array($f($s), array());
        });
    }
}