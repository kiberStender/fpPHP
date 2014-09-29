<?php
/**
 * Description of Try
 *
 * @author sirkleber
 */
require_once "fn/Fn.php";
require_once 'typeclasses/Monad.php';

abstract class FTry extends Monad {
    
}

class Success extends FTry {
    
}

class Failure extends FTry {
    
}
