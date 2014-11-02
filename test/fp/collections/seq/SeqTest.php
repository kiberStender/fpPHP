<?php
/**
 * Description of SetTest
 *
 * @author sirkleber
 */
class SeqTest extends PHPUnit_Framework_TestCase{
  private $s;
  private $seqi;
  private $seqs;
  private $filter;
  
  public function __construct() {
    parent::__construct();
    $this->s = Seq::build(1, 2, 3);
    $this->seqi = new Cons(1, new Cons(2, new Cons(3, Nil::Nil())));
    $this->seqs = Seq::build("Joao", "Luiz");
    $this->filter = new FilterAnon();
  }
  
  public function testEquals(){
    $this->assertEquals($this->seqi, $this->seqi);
  }
  
  public function testEquals1(){
    $this->assertEquals($this->s, $this->s);
  }
  
  public function testEquals2(){
    $this->assertEquals($this->seqi, $this->s);
  }
  
  public function testEquals3(){
    $this->assertFalse($this->seqi != $this->s);
  }
  
  public function testToString(){
    $this->assertEquals("Seq(1, 2, 3)", $this->seqi);
  }
  
  public function testToString1(){
    $this->assertEquals("Seq(Joao, Luiz)", $this->seqs);
  }
  
  public function testLength(){
    $this->assertEquals(3, $this->seqi->length());
  }
  
  public function testLength4(){
    $this->assertEquals(2, $this->seqs->length());
  }
  
  public function testCons(){
    $this->assertEquals(Seq::build(0, 1, 2, 3), $this->seqi->cons(0));
  }
  
  public function testConcat(){
    $this->assertEquals(Seq::build(1, 2, 3, 1, 2, 3), $this->seqi->concat($this->s));
  }
  
  public function testReverse(){
    $this->assertEquals(Seq::build(3, 2, 1), $this->seqi->reverse());
  }
  
  public function testFoldLeft(){
    $this->assertEquals(-6, $this->seqi->foldLeft(0, new FLeft()));
  }
  
  public function testFoldRight(){
    $this->assertEquals(2, $this->seqi->foldRight(0, new FRight()));
  }
  
  public function testInit(){
    $this->assertEquals(Seq::build(1, 2), $this->seqi->init());
  }
  
  public function testLast(){
    $this->assertEquals(3, $this->seqi->last());
  }
  
  public function testMaybeLast(){
    $this->assertEquals(new Just(3), $this->s->maybeLast());
  }
  
  public function testFilter(){
    $this->assertEquals(Seq::build(2), $this->seqi->filter($this->filter));
  }
  
  public function testFilterNot(){
    $this->assertEquals(Seq::build(1, 3), $this->seqi->filterNot($this->filter));
  }
  
  public function testPartition(){
    $this->assertEquals(array(Seq::build(2), Seq::build(1, 3)), $this->seqi->partition($this->filter));
  }
  
  public function testFind(){
    $this->assertEquals(new Just(2), $this->seqi->find($this->filter));
  }
  
  public function testMap(){
    $this->assertEquals(Seq::build(2, 4, 6), $this->seqi->map(new MapMult()));
  }
  
  public function testFlatMap(){
    $this->assertEquals(Seq::build(2, 4, 6), $this->seqi->flatMap(new FlatMapMult()));
  }
  
  public function testSplit(){
    $this->assertEquals(array(Seq::build(1, 2), Seq::build(3)), $this->seqi->splitAt(2));
  }
  
  public function testContains(){
    $this->assertEquals(TRUE, $this->seqi->contains(2));
  }
}

class FilterAnon implements Fn1 {
  public function apply($x) {
    return $x == 2;
  }
}

class FLeft implements Fn2 {
  public function apply($acc, $item) {
    return $acc - $item;
  }
}

class FRight implements Fn2 {
  public function apply($item, $acc) {
    return $item - $acc;
  }
}

class MapMult implements Fn1 {
  public function apply($x) {
    return $x * 2;
  }
}

class FlatMapMult implements Fn1 {
  public function apply($x) {
    return Seq::build($x * 2);
  }
}