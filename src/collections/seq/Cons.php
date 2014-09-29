<?php

/**
 * Description of Cons
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

class Cons extends Seq{
    
}
