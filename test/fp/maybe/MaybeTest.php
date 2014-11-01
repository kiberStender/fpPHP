<?php
/**
 * Description of MybeTest
 *
 * @author sirkleber
 */

class MaybeTest extends PHPUnit_Framework_TestCase{
  public function testEquals() {
    $this->assertEquals(new Just(1), new Just(1));
  }
  
  public function testEquals1(){
    $this->assertEquals(Nothing::Nothing(), Nothing::Nothing());
  }
  
  public function testEquals2(){
    $this->assertFalse(new Just(1) == Nothing::Nothing());
  }
  
  public function testMap(){
    $j = new Just(1);
    $this->assertEquals($j->map(new JustAddMap()), new Just(2));
  }
  
  public function testMap1(){
    $j = new Just(1);
    $this->assertEquals($j->map(new JustMultMap()), new Just(2));
  }
  
  public function testFlatMap(){
    $j = new Just(1);
    $this->assertEquals($j->flatMap(new JustAddFlatMap()), new Just(2));
  }
}

class JustAddMap implements Fn1{
  public function apply($a) {
    return $a + 1;
  }
}

class JustMultMap implements Fn1{
  public function apply($a) {
    return $a * 2;
  }
}

class JustAddFlatMap implements Fn1{
  public function apply($a) {
    return new Just($a + 1);
  }
}