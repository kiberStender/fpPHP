<?php
/**
 *
 * @author sirkleber
 */
set_include_path(dirname(__FILE__) . "/../");

require_once 'fn/Fn1.php';

interface Functor {
    /**
     * Function to traverse the container and apply a function to transform it
     * f a -> (a -> b) -> f b
     * @param Fn1 $f
     */
    public function map(Fn1 $f);
}
