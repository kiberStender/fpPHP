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
}

class FilterAnon implements Fn1 {
  public function apply($x) {
    return $x == 2;
  }
}
