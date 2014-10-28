<?php
/**
 *
 * @author sirkleber
 */

namespace typeclasses;

interface Functor {
    /**
     * Function to traverse the container and apply a function to transform it
     * f a -> (a -> b) -> f b
     * @param (a -> b) $f
     */
    public function map($f);
}
