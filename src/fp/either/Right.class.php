<?php

  namespace fp\either;

  /**
   * Description of Right
   *
   * @author sirkleber
   */
  class Right extends Either{
    private $value_;
    
    function __construct($value_) {
      $this->value_ = $value_;
    }

    public function value() {
      return $this->value_;
    }

    public function isLeft() {
      return false;
    }

    public function isRight() {
      return true;
    }

    public function __toString() {
      return "Right($this->value_)";
    }
  }
  