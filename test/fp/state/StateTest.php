<?php
/**
 * Description of StateTest
 *
 * @author sirkleber
 */
class StateTest extends PHPUnit_Framework_TestCase {
  /**
   * 
   * @param type $z
   * @return State Description
   */
  public function fibMemoR($z){
    if($z == 1){
      return State::insert($z);
    } else {
      return State::get(new StateGet($z))->flatMap(new FlatMapState($z, $this));
    }
  }
  
  private function fibMemo($n){
    return $this->fibMemoR($n)->evaluate(Map::build());
  }
  
  public function testFib(){
    $this->assertEquals(832040, $this->fibMemo(30));
  }
}

class StateGet implements Fn1 {
  private $z;
  
  function __construct($z) {
    $this->z = $z;
  }

  public function apply($m) {
    return $m->get($this->z);
  }
}

class FlatMapState implements Fn1 {
  
  private $z;
  private $tester;
  
  function __construct($z, StateTest $tester) {
    $this->z = $z;
    $this->tester = $tester;
  }
  
  public function apply($u) {
    return $u->map(new MapToState())->getOrElse(new GetOrElseFn($this->z, $this->tester))->map(new MapGetOrElse());   
  }
}

class MapGetOrElse implements Fn1 {
  public function apply($v) {
    return $v;
  }
}

class MapToState implements Fn1 {
  public function apply($i) {
    return State::insert($i);
  }
}

class GetOrElseFn implements Fn {
  private $z;
  private $tester;
  
  function __construct($z, StateTest $tester) {
    $this->z = $z;
    $this->tester = $tester;
  }
  
  public function apply() {
    return $this->tester->fibMemoR($this->z - 1)->flatMap(new FlatMapFibR($this->z, $this->tester));
  }
}

class FlatMapFibR implements Fn1 {
  private $z;
  private $tester;
  
  function __construct($z, StateTest $tester) {
    $this->z = $z;
    $this->tester = $tester;
  }
  
  public function apply($r) {
    return $this->tester->fibMemoR($this->z - 2)->flatMap(new FlatMapFibS($this->z, $this->tester, $r));
  }
}

class FlatMapFibS implements Fn1 {
  private $z;
  private $tester;
  private $r;
  
  function __construct($z, StateTest $tester, $r) {
    $this->z = $z;
    $this->tester = $tester;
    $this->r = $r;
  }
  
  public function apply($s) {
    return State::mod(new ModFn($this->z, $this->tester, $this->r, $s))->map(new MapModFn($this->r, $s));
  }
}

class ModFn implements Fn1 {
  private $z;
  private $tester;
  private $r;
  private $s;
  
  function __construct($z, StateTest $tester, $r, $s) {
    $this->z = $z;
    $this->tester = $tester;
    $this->r = $r;
    $this->s = $s;
  }
  
  public function apply($m) {
    return $m->cons(array($this->z, $this->r + $this->s));
  }
}

class MapModFn implements Fn1 {
  private $r;
  private $s;
  
  function __construct($r, $s) {
    $this->r = $r;
    $this->s = $s;
  }
  
  public function apply($unit) {
    return $this->r + $this->s;
  }
}