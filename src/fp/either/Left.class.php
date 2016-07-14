<?php

  namespace fp\either;

  /**
   * Description of Left
   *
   * @author sirkleber
   */
  class Left extends Either{
    private $value;
    function __construct($value) {
      $this->value = $value;
    }

    public function isLeft() {
      return true;
    }

    public function isRight() {
      return false;
    }

    public function value() {
      return $this->value;
    }

    public function __toString() {
      return "Left($this->value)";
    }

//put your code here
  }
  