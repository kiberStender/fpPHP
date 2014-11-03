<?php
/**
 * Description of MapTest
 *
 * @author sirkleber
 */
class MapTest extends PHPUnit_Framework_TestCase {
  private $mi;
  private $md;
  
  public function __construct() {
    $this->mi = Map::build(array(1, "kleber"), array(2, "eduardo"));
    $this->md = Map::build(array(1, 2.0));
  }
  
  public function testConstructor(){
    $this->assertEquals(array(1, "kleber"), Map::build(array(1, "kleber"), array(2, "eduardo"))->head());
  }
  
  public function testEquality(){
    $this->assertEquals($this->mi, $this->mi);
  }
  
  public function testEquality1(){
    $this->assertFalse($this->mi == $this->md);
  }
  
  public function testCons(){
    $this->assertEquals(Map::build(array(1, 2.0), array(2, 3.2)), $this->md->cons(array(2, 3.2)));
  }
  
  public function testCons1(){
    $this->assertEquals(Map::build(array(1, 2.0), array(2, 3.2), array(3, 1.5)), $this->md->cons(array(3, 1.5))->cons(array(2, 3.2)));
  }
  
  public function testConcat(){
    $this->assertEquals(Map::build(array(1, 2.0), array(2, 3.1)), $this->md->concat(Map::build(array(2, 3.1))));
  }
  
  public function testConcat1(){
    $this->assertEquals(Map::build(array(1, 2.0), array(2, 3.1), array(3, 2.5)), $this->md->concat(Map::build(array(3, 2.5), array(2, 3.1))));
  }
  
  public function testHead(){
    $this->assertEquals(array(1, 2.0), $this->md->head());
  }
  
  public function testTail(){
    $this->assertEquals(Map::build(array(2, "eduardo")), $this->mi->tail());
  }
  
  public function testInit(){
    $this->assertEquals(Map::build(array(1, "kleber")), $this->mi->init());
  }
  
  public function testLast(){
    $this->assertEquals(array(2, "eduardo"), $this->mi->last());
  }
  
  public function testFind(){
    $this->assertEquals(new Just(array(2, "eduardo")), $this->mi->find(new FilterAnon()));
  }
}

class FilterAnon implements Fn1 {
  public function apply($x) {
    return $x[0] == 2;
  }
}