<?php

/**
 * Description of SQL
 *
 * @author sirkleber
 */
namespace db;

set_include_path(dirname(__FILE__) . "/../");

include_once 'collections/Seq.php';
include_once 'collections/Map.php';

use collections\seq\Nil;
use collections\map\Map;

class SQL implements Functor{
    private $query;
    private $pdo;
    private $st;
    
    /**
     * 
     * @param PDO $pdo
     * @param string $query
     * @return \SQL
     */
    public static function sql(PDO $pdo, $query){
        return new SQL($pdo, $query);
    }
    
    private function __construct(PDO $pdo, $query, PDOStatement $st = null) {
        $this->pdo = $pdo;
        $this->query = $query;
        $this->st = $st;
    }
    
    /**
     * Funtion for insert, delete, updates and procedures sql statements
     * @param Map $m
     * @return SQL
     */
    public function on(Map $m){
      $m->fpForeach(function($m){
        $this->st->bindValue(":$m[0]", $m[1]);
        return $m;
      });
      return new SQL($this->pdo, $this->query, $this->st);
    }
    
    /**
     * Function for mapping the object Array that cames from select statement
     * into a Seq of A's
     * @param $f
     * @return Seq
     */
    public function as_($f){
      $st = $this->pdo->prepare($this->query);
      $arr = Nil::Nil();
      
      foreach ($st as $value){
        $arr = $arr->cons($f($value));
      }
      
      return $arr;
    }
    
    public function executeUpdate(){
      return $this->st->execute();
    }
    
    public function map($f) {
      $arr = Nil::Nil();
      
      foreach ($this->st as $value){
        $arr = $arr->cons($f($value));
      }      
      return $arr;
    }
}