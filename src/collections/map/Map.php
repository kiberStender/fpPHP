<?php

/**
 * Description of Map
 *
 * @author sirkleber
 */
require_once 'collections/FTraversable.php';

abstract class Map extends FTraversable{
    /**
     * 
     * @param array $args
     * @return Map
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
     * @return Map
     */
    public static final function build(){
        return self::construct(func_get_args());
    }
    
    protected function empty_() {
        return EmptyMap::EmptyMap();
    }
    
    protected function add($item){
        return new KVMapCons($item, $this);
    }
    
    public function cons($item) {
        
    }
    
    private function helper(Map $acc, Map $other){
        if($other->isEmpty()){
            return $acc;
        } else {
            return $this->helper($acc->cons($other->head()), $other->tail());
        }
    }
    
    public function concat(FTraversable $prefix) {
        return $this->helper($this, $prefix);
    }
    
    protected function prefix() {
        return "Map";
    }
    
    protected function toStringFrmt() {
        return new MapFrmToString();
    }
    
    private function splitR($n, Map $curL, Map $pre){
        if($curL->isEmpty()){
            return array($pre, $this->empty_());
        } else {
            if($n === 0){
                return array($pre, $curL);
            } else {
                return $this->splitR($n - 1, $curL->tail(), $pre->cons($curL->head()));
            }
        }
    }
    
    public function splitAt($n) {
        return $this->splitR($n, $this, $this->empty_());
    }
}

class KVMap extends Map{
    private $head_;
    private $tail_;
    
    function __construct($head_, Map $tail_) {
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
        
    }
    
    public function last() {
        
    }
    
    public function maybeHead() {
        return new Just($this->head_);
    }
    
    public function maybeLast() {
        return new Just($this->last());
    }

}

class EmptyMap extends Map{
    private static $emtpym = null;
    
    /**
     * 
     * @return Map
     */
    public static final function EmptyMap(){
        if(!isset(self::$emtpym)){
            self::$emtpym = new EmptyMap();
        }
        return self::$emtpym;
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

class MapFrmToString implements Fn2{
    public function apply($acc, $item) {
        if($acc === ""){
            return $item;
        } else {
            return "$acc, $item";
        }
    }
}

