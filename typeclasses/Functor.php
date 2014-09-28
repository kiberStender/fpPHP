<?php
/**
 *
 * @author sirkleber
 */
require_once '../Fn/Fn1.php';

interface Functor {
    /**
     * Function to traverse the container and apply a function to transform it
     * f a -> (a -> b) -> f b
     * @param Fn1 $f
     */
    public function map(Fn1 $f);
}
