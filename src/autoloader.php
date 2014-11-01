<?php
  function my_autoloader($class) {
    $arr = array(
      #fn
      "Fn" => "fn/Fn",
      "Fn1" => "fn/Fn1",
      "Fn2" => "fn/Fn2",
      #typeclasses
      "Functor" => "typeclasses/Functor",
      "Monad" => "typeclasses/Monad",
      #Maybe
      "Just" => "maybe/Just",
      "Maybe" => "maybe/Maybe",
      "Nothing" => "maybe/Nothing",
      #collections
      "FTraversable" => "collections/FTraversable",
      #collections.seq
      "Cons" => "collections/seq/Cons",
      "Nil" => "collections/seq/Nil",
      "Seq" => "collections/seq/Seq"
      #collections.map
      #collections.set
    );
    
    if(isset($arr[$class])){
      include_once 'src/fp/' . $arr[$class] . '.class.php';
    }
  }
  
  spl_autoload_register('my_autoloader');
