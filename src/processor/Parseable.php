<?php

/**
 * Description of Parseable
 *
 * @author sirkleber
 */
abstract class Parseable {
  protected $value;
  protected $parser;
  protected $contentType;
  
  function __construct($prim, $contentType, Maybe $parser) {
    $this->value = $prim;
    $this->contentType = $contentType;
    $this->parser = $parser;
  }
  
  public function getContentType(){
    return $this->contentType;
  }
  
  public abstract function parse();
}

class ArrayPrimParseable extends Parseable{
  public function parse() {
    switch($this->contentType){
      case "json": return json_encode($this->value);
      case "xml":
      default: return $this->parser->get()->apply($this->value);
    }
  }
}

class PrimParseable extends Parseable{  
  public function parse() {
    switch ($this->contentType){
      case "json": return $this->value . "";
      case "xml":
      default : return $this->parser->get()->apply($this->value);
    }
  }
}
