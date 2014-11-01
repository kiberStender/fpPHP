<?php
  function my_autoloader($class) {
    $arr = array(
      #fn
      "Fn" => "fn/Fn.class.php",
      "Fn1" => "fn/Fn1.class.php",
      "Fn2" => "fn/Fn2.class.php",
      #typeclasses
      "Functor" => "typeclasses/Functor.class.php",
      "Monad" => "typeclasses/Monad.class.php",
      #Maybe
      "Just" => "maybe/Just.class.php",
      "Maybe" => "maybe/Maybe.class.php",
      "Nothing" => "maybe/Nothing.class.php"
    );
    
    if(isset($arr[$class])){
      include_once 'src/fp/' . $arr[$class];
    }
  }
  
  spl_autoload_register('my_autoloader');
