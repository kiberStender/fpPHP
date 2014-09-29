<?php

/**
 * Description of Seq
 *
 * @author sirkleber
 */
require_once 'collections/FTraversable.php';

abstract class Seq extends FTraversable{
    
    /**
     * 
     * @param array $args
     * @return Seq
     */
    private static final function construct(array $args){
        if(sizeof($args) === 0){
            return Nil::Nil();
        } else {
            return self::construct(array_slice($args, 1))->cons($args[0]);
        }
    }
    
    /**
     * 
     * @return Seq
     */
    public static final function aff(){
        return self::construct(func_get_args());
    }
    
    protected function empty_() {
        return Nil::Nil();
    }
    
    public function cons($item) {
        return new Cons($item, $this);
    }
    
    private function helper(Seq $acc, Seq $other){
        if($other->isEmpty()){
            return $acc;
        } else {
            return $this->helper($acc->cons($other->head()), $other->tail());
        }
    }
    
    public function concat(Traversable $prefix) {
        return $this->helper($this, $prefix);
    }
    
    protected function prefix() {
        return "Seq";
    }
    
    protected function toStringFrmt() {
        return new SeqFrmToString();
    }
    
    /**
     * 
     * @return Seq
     */
    public function reverse(){
        return $this->foldLeft(Seq::Seq(), new SeqReverse());
    }
    
    private function splitR($n, Seq $curL, Seq $pre){
        if($curL->isEmpty()){
            return array($pre->reverse(), $this->empty_());
        } else {
            if($n === 0){
                return array($pre->reverse(), $curL);
            } else {
                return $this->splitR($n - 1, $curL->tail(), $pre->cons($curL->head()));
            }
        }
    }
    
    public function splitAt($n) {
        return $this->splitR($n, $this, $this->empty_());
    }
    
}

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

class Nil extends Seq{
    private static $nil = null;
    
    public static function Nil(){
        if(!isset(self::$nil)){
            self::$nil = new Nil();
        }
        return self::$nil;
    }
    
    private function __construct() {}
    
    public function isEmpty() {
        return true;
    }
    
    public function head() {
        throw new Exception("No such Element");
    }
    
    public function tail() {
        throw new Exception("No such Element");
    }
    
    public function init() {
        throw new Exception("No such Element");
    }
    
    public function last() {
        throw new Exception("No such Element");
    }
    
    public function maybeHead() {
        return Nothing::Nothing();
    }
    
    public function maybeLast() {
        return Nothing::Nothing();
    }
}

class SeqFrmToString implements Fn2{
    public function apply($acc, $item) {
        if($acc === ""){
            return $item;
        } else {
            return "$acc, $item";
        }
    }
}

class SeqReverse implements Fn2{
    public function apply($acc, $item) {
        return $acc->cons($item);
    }
}