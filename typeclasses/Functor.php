<?php
/**
 *
 * @author sirkleber
 */
require_once '../Fn/Fn1.php';

interface Functor {
    public function map(Fn1 $f);
}
