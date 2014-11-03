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
  
  public function testGet(){
    $this->assertEquals(new Just("eduardo"), $this->mi->get(2));
  }
  
  public function testFoldLeft(){
    $this->assertEquals(-3, $this->mi->foldLeft(0, new FLeft()));
  }
  
  public function testFoldRight(){
    $this->assertEquals(-1, $this->mi->foldRight(0, new FRight()));
  }
  
  public function testMap(){
    $this->assertEquals(Map::build(array(1, "kleberk"), array(2, "eduardok")), $this->mi->map(new MapV()));
  }
  
  public function testMap1(){
    $this->assertEquals(Map::build(array(2, "kleber"), array(4, "eduardo")), $this->mi->map(new MapK()));
  }
  
  public function testFlatMap(){
    $this->assertEquals(Map::build(array(1, "kleberk"), array(2, "eduardok")), $this->mi->flatMap(new FlatMapV()));
  }
  
  public function testFlatMap1(){
    $this->assertEquals(Map::build(array(2, "kleber"), array(4, "eduardo")), $this->mi->flatMap(new FlatMapK));
  }
  
  public function testSplit(){
    $this->assertEquals(array(Map::build(array(1, "kleber")), Map::build(array(2, "eduardo"))), $this->mi->splitAt(1));
  }
  
  public function testSplit1(){
    $m = array(Map::build(array(1, "kleber")), Map::build(array(2, "eduardo"), array(3, "scalise")));
    $this->assertEquals($m, $this->mi->cons(array(3, "scalise"))->splitAt(1));
  }
  
  public function testSplit2(){
    $m = array(Map::build(array(1, "kleber"), array(2, "eduardo")), Map::build(array(3, "scalise")));
    $this->assertEquals($m, $this->mi->cons(array(3, "scalise"))->splitAt(2));
  }
}

class FilterAnon implements Fn1 {
  public function apply($x) {
    return $x[0] == 2;
  }
}

class FLeft implements Fn2 {
  public function apply($acc, $item) {
    return $acc - $item[0];
  }
}

class FRight implements Fn2 {
  public function apply($item, $acc) {
    return $item[0] - $acc;
  }
}

class MapK implements Fn1 {
  public function apply($item) {
    return array($item[0] * 2, $item[1]);
  }
}

class MapV implements Fn1 {
  public function apply($item) {
    return array($item[0], "$item[1]k");
  }
}

class FlatMapK implements Fn1 {
  public function apply($item) {
    return Map::build(array($item[0] * 2, $item[1]));
  }
}

class FlatMapV implements Fn1 {
  public function apply($item) {
    return Map::build(array($item[0], "$item[1]k"));
  }
}