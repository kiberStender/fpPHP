<?php

/**
 * Description of Map
 *
 * @author sirkleber
 */
namespace collections\map;

require_once 'collections/FTraversable.php';

use collections\FTraversable;

abstract class Map extends FTraversable{
    /**
     * 
     * @param array $args
     * @return Map
     */
    private static final function construct(array $args){
        if(sizeof($args) === 0){
            return EmptyMap::EmptyMap();
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
    
    /**
     * 
     * @param type $item
     * @return \KVMapCons
     */
    protected function add($item){
        return new KVMap($item, $this);
    }
    
    private function compareTo($_key1, $_key2){
        if($_key1 == $_key2) {
            return 0;
        } elseif($_key1 < $_key2){
            return -1;
        } else {
            return 1;
        }
    }
    
    public function cons($item) {
        if($this->isEmpty()){
            return $this->add($item);
        } else {
            $head_ = $this->head();
            switch ($this->compareTo($item[0], $head_[0])){
                case 1: return $this->tail()->cons($item)->add($head_);
                case 2: 
                    if($item[1] === $head_[1]){
                        return $this;
                    } else {
                        return $this->tail()->cons($item);
                    }
                default : return $this->tail()->add($head_)->add($item);
            }
        }
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
    
    protected function toStringFrmt($acc, $item) {
        if($acc === ""){
            return "($item[0] -> $item[1])";
        } else {
            return "$acc, ($item[0] -> $item[1])";
        }
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
        if($this->tail_->isEmpty()){
            return $this->empty_();
        } else {
            return $this->tail_->init()->cons($this->head_);
        }
    }
    
    public function last() {
        if($this->tail_->isEmpty()){
            return $this->head_;
        } else {
            return $this->tail_->last();
        }
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