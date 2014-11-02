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
}

class FilterAnon implements Fn1 {
  public function apply($x) {
    return $x == 2;
  }
}
