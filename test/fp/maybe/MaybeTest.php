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
}
