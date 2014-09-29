<?php

/**
 * Description of Cons
 *
 * @author sirkleber
 */

set_include_path(dirname(__FILE__) . "/../");

require_once 'fn/Fn1.php';
require_once 'fn/Fn2.php';
require_once 'typeclasses/Monad.php';
require_once 'maybe/Just.php';
require_once 'maybe/Nothing.php';
require_once 'collections/seq/Seq.php';

class Cons extends Seq{
    private $head_;
    private $tail_;
    
    function __construct($head_, Seq $tail_) {
        $this->head_ = $head_;
        $this->tail_ = $tail_;
    }
    
    public function isEmpty() {
        return false;
    }
    
    public function head() {
        return $this->head_;
    }
    
    public function tail() {
        return $this->tail_;
    }
    
    public function init() {
        return $this->reverse()->tail()->reverse();
    }
    
    public function last() {
        return $this->reverse()->head();
    }
    
    public function maybeHead() {
        return new Just($this->head_);
    }
    
    public function maybeLast() {
        return new Just($this->last());
    }
}
