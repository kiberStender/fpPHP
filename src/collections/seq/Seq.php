<?php

/**
 * Description of Seq
 *
 * @author sirkleber
 */
set_include_path(dirname(__FILE__) . "/../");

require_once 'fn/Fn1.php';
require_once 'fn/Fn2.php';
require_once 'typeclasses/Monad.php';
require_once 'maybe/Just.php';
require_once 'maybe/Nothing.php';
require_once 'collections/Traversable.php';
require 'Nil.php';

abstract class Seq extends Traversable{
    
    public static function Seq(){
        $args = func_get_arg();
        $nargs = func_num_args();
        if($nargs === 0){
            return Nil::Nil();
        } else {
            return new Cons($args[0], call_user_func('Seq', array_slice($args, 1)));
        }
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
    public function apply(Seq $acc, $item) {
        return $acc->cons($item);
    }
}