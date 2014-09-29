<?php

/**
 * Description of Nil
 *
 * @author sirkleber
 */

set_include_path(dirname(__FILE__) . "/../");

require_once 'fn/Fn1.php';
require_once 'fn/Fn2.php';
require_once 'typeclasses/Monad.php';
require_once 'maybe/Just.php';
require_once 'maybe/Nothing.php';
require_once 'collections/seq/Seq.php';

class Nil extends Seq{
    private static $nil = null;
    
    public static function Nil(){
        if(!isset(self::$nil)){
            self::$nil = new Nil();
        }
        return self::$nil;
    }
    
    private function __construct() {}
}
