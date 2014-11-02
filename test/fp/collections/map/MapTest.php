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
  
}
