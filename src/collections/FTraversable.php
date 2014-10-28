<?php

/**
 * Description of FTraversable
 *
 * @author sirkleber
 */
namespace collections;

set_include_path(dirname(__FILE__) . "/../");

require_once 'maybe/Maybe.php';

use typeclasses\Monad;
use maybe\Just;

abstract class FTraversable extends Monad{
    /**
     * @return Boolean Description
     */
    public abstract function isEmpty();
    
    /**
     * @return A The item itself
     */
    public abstract function head();
    
    /**
     * @return FTraversable The array continuation
     */
    public abstract function tail();
    
    /**
     * @return FTraversable The array continuation
     */
    public abstract function init();

    
    /**
     * @return A The last irem of the array
     */
    public abstract function last();
    
    /**
     * @return Maybe If there is a head, it return a Just with it
     */
    public abstract function maybeHead();

    /**
     * @return Maybe If there is a last, it return a Just with it
     */
    public abstract function maybeLast();

    /**
     * @return FTraversable 
     */
    protected abstract function empty_();

    /**
     * Scala :: and Haskell : functions
     * @param item the item to be appended to the collection
     * @return a new collection
     */
    public abstract function cons($item);

    /**
     * Scala and Haskell ++ function
     * @param prefix new collection to be concat in the end of this collection
     * @return a new collection
     */
    public abstract function concat(FTraversable $prefix);
    
    public final function __toString(){
        return "{$this->prefix()}({$this->foldLeft("", function($acc, $item){return $this->toStringFrmt($acc, $item);})})";
    }

    protected abstract function prefix();

    protected abstract function toStringFrmt($acc, $item);

    
    public function length(){
        return $this->foldLeft(0, function($acc, $item){return $acc + 1;});
    }

    public function filter($p){
        return $this->foldRight($this->empty_(), function($item, $acc) use($p){
            if($p($item)){
                return $acc->cons($item);
            } else {
                return $acc;
            }
        });
    }

    public function filterNot($p){
        return $this->filter(function($x) use($p){
            return !$p($x);
        });
    }

    public final function partition($p){
        return array($this->filter($p), $this->filterNot($p));
    }

    public function find($p){
        if($this->isEmpty()){
            return Nothing::Nothing();
        } else {
            if($p($this->head())){
                return new Just($this->head());
            } else {
                return $this->tail()->find($p);
            }
        }
    }

    public abstract function splitAt($n);
    
    public function foldLeft($acc, $f){
        if($this->isEmpty()){
            return $acc;
        } else {
            return $this->tail()->foldLeft($f($acc, $this->head()), $f);
        }
    }

    public function foldRight($acc, $f){
        if($this->isEmpty()){
            return $acc;
        } else {
            return $f($this->head(), $this->tail()->foldRight($acc, $f));
        }
    }
    
    public function map($f) {
        if($this->isEmpty()){
            return $this->empty_();
        } else {
            return $this->tail()->map($f)->cons($f($this->head()));
        }
    }
    
    public function flatMap($f) {
        if($this->isEmpty()){
            return $this->empty_();
        } else {
            return $this->tail()->flatMap($f)->concat($f($this->head()));
        }
    }
}