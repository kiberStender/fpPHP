<?php

/**
 * Description of Monad
 *
 * @author sirkleber
 */

require_once 'Functor.php';

abstract class Monad implements Functor{
    
    /**
     * Haskell >>= (bind) function
     * flatMap:: m a -> (a -> m b) -> m b
     * @param Fn1 $f
     */
    public abstract function flatMap(Fn1 $f);
    
    /**
     * Haskell >> function
     * @param Fn1 $f
     */
    public function fpForeach(Fn1 $f){
        $this->map($f);
    }
}
