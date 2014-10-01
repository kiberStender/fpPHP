<?php
/**
 * Description of Try
 *
 * @author sirkleber
 */
namespace ftry;

require_once 'typeclasses/Monad.php';

abstract class FTry extends Monad {
    public static final function build($f){
        try {
            return new Success($f());
        } catch (Exception $ex) {
            return new Failure($ex);
        }
    }
    
    public abstract function getOrElse($f);

    public abstract function isSuccess();

    public abstract function isFailure();
}

class Success extends FTry {
    private $value;
    
    function __construct($value) {
        $this->value = $value;
    }
    
    public function map(Fn1 $f) {
        return FTry::build(function() use($f){
            return $f($this->value);
        });
    }
    
    public function flatMap($f) {
        try {
            return $f($this->value);
        } catch (Exception $ex) {
            return new Failure($ex);
        }
    }
    
    public function getOrElse($f) {
        return $this->value;
    }

    public function isFailure() {
        return false;
    }

    public function isSuccess() {
        return true;
    }
    
    public function __toString() {
        return "Success($this->value)";
    }
}

class Failure extends FTry {
    private $value;
    
    function __construct(Exception $value) {
        $this->value = $value;
    }
    
    public function map($f) {
        return $this;
    }
    
    public function flatMap($f) {
        return $this;
    }
    
    public function getOrElse($f) {
        return $f->apply();
    }

    public function isFailure() {
        return true;
    }

    public function isSuccess() {
        return false;
    }
    
    public function __toString() {
        return "Failure($this->value)";
    }
}