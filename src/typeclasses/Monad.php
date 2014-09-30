<?php

/**
 * Description of Monad
 *
 * @author sirkleber
 */
set_include_path(dirname(__FILE__) . "/../");

require_once 'Functor.php';

abstract class Monad implements Functor{
    
    /**
     * Haskell >>= (bind) function
     * flatMap:: m a -> (a -> m b) -> m b
     * @param Fn1 $f
     */
    public abstract function flatMap($f);
    
    /**
     * Haskell >> function
     * @param Fn1 $f
     */
    public function fpForeach($f){
        $this->map($f);
    }
}
