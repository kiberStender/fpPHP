<?php

/**
 * Description of FTraversable
 *
 * @author sirkleber
 */

require_once 'fn/Fn2.php';
require_once 'maybe/Maybe.php';

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
        return "{$this->prefix()}({$this->foldLeft("", new ToStringFrm($this->toStringFrmt()))})";
    }

    protected abstract function prefix();

    protected abstract function toStringFrmt();

    
    public function length(){
        return $this->foldLeft(0, new SumFoldLeft());
    }

    public function filter(Fn1 $p){
        return $this->foldRight($this->empty_(), new FilterFoldRight($p));
    }

    public function filterNot(Fn1 $p){
        return $this->filter(new FilterNot($p));
    }

    public final function partition(Fn1 $p){
        return array($this->filter($p), $this->filterNot($p));
    }

    public function find(Fn1 $p){
        if($this->isEmpty()){
            return Nothing::Nothing();
        } else {
            if($p->apply($this->head())){
                return new Just($this->head());
            } else {
                return $this->tail()->find($p);
            }
        }
    }

    public abstract function splitAt($n);
    
    public function foldLeft($acc, Fn2 $f){
        if($this->isEmpty()){
            return $acc;
        } else {
            return $this->tail()->foldLeft($f->apply($acc, $this->head()), $f);
        }
    }

    public function foldRight($acc, Fn2 $f){
        if(isEmpty()){
            return acc;
        } else {
            return $f->apply($this->head(), $this->tail()->foldRight($acc, $f));
        }
    }
    
    public function map(Fn1 $f) {
        if($this->isEmpty()){
            return $this->empty_();
        } else {
            return $this->tail()->map($f)->cons($f->apply($this->head()));
        }
    }
    
    public function flatMap(Fn1 $f) {
        if($this->isEmpty()){
            return $this->empty_();
        } else {
            return $this->tail()->flatMap($f)->concat($f->apply($this->head()));
        }
    }
}

class ToStringFrm implements Fn2{
    private $frmt;
    
    function __construct(Fn2 $frmt) {
        $this->frmt = $frmt;
    }
    
    public function apply($acc, $item) {
        return $this->frmt->apply($acc, $item);
    }
}

class SumFoldLeft implements Fn2{
    public function apply($acc, $item) {
        return $acc + $item;
    }
}

class FilterFoldRight implements Fn2{
    private $p;
    
    public function __construct(Fn1 $p) {
        $this->p = $p;
    }

    public function apply($item, $acc) {
        if($this->p->apply($item)){
            return $acc->cons($item);
        } else {
            return $acc;
        }
    }
}

class FilterNot implements Fn1{
    private $p;
    
    public function __construct(Fn1 $p) {
        $this->p = $p;
    }
    public function apply($item) {
        return !$this->p->apply($item);
    }
}
