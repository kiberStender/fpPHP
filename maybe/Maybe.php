<?php

/**
 * Description of Maybe
 *
 * @author sirkleber
 */
require_once '../fn/Fn.php';
require_once '../typeclasses/Monad.php';

abstract class Maybe extends Monad{
    public abstract function getOrElse(Fn $f);
    
    public abstract function get();
}
