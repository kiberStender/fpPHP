<?php
/**
 * Description of MybeTest
 *
 * @author sirkleber
 */

class MaybeTest extends PHPUnit_Framework_TestCase{
  
  private $j;
  
  public function __construct(){
    parent::__construct();
    $this->j = new Just(1);
  }
  
  public function testEquals() {
    $this->assertEquals($this->j, $this->j);
  }
  
  public function testEquals1(){
    $this->assertEquals(Nothing::Nothing(), Nothing::Nothing());
  }
  
  public function testEquals2(){
    $this->assertFalse($this->j == Nothing::Nothing());
  }
  
  public function testMap(){
    $this->assertEquals($this->j->map(new JustAddMap()), new Just(2));
  }
  
  public function testMap1(){
    $this->assertEquals($this->j->map(new JustMultMap()), new Just(2));
  }
  
  public function testFlatMap(){
    $this->assertEquals($this->j->flatMap(new JustAddFlatMap()), new Just(2));
  }
  
  public function testGet(){
    $this->assertEquals($this->j->get(), 1);
  }
  
  /**
   * @expectedException Exception
   */
  public function testGet1(){
    Nothing::Nothing()->get();
  }
  
  public function testGetOrElse(){
    $this->assertEquals($this->j->getOrElse(new GetOrElseJust()), 1);
  }
  
  public function testGetOrElse1(){
    $this->assertEquals(Nothing::Nothing()->getOrElse(new GetOrElseJust()), "Nothing");
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

class GetOrElseJust implements Fn{
  public function apply() {
    return "Nothing";
  }
}