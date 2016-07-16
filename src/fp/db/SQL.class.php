<?php

  /**
   * Description of SQL
   *
   * @author sirkleber
   */

  namespace fp\db;

  use fp\collections\seq\Seq;
  use fp\collections\map\Map;
  use fp\utils\unit\Unit;
  use PDO;

  class SQL {

    private $query;
    private $pdo;
    private $m;

    /**
     * 
     * @param PDO $pdo
     * @param string $query
     * @return \SQL
     */
    public static function sql(PDO $pdo, $query) {
      return new SQL($pdo, $query, Map::map_());
    }

    private function __construct(PDO $pdo, $query, Map $m) {
      $this->pdo = $pdo;
      $this->query = $query;
      $this->m = $m;
    }

    /**
     * Funtion for insert, delete, updates and procedures sql statements
     * @param Map $m
     * @return SQL
     */
    public function on(Map $m) {
      return new SQL($this->pdo, $this->query, $m->map(function($tp) {
        list($k, $v) = $tp;
        return array(":$k", $v);
      }));
    }

    /**
     * Function for mapping the object Array that cames from select statement
     * into a Seq of A's
     * @param callable $f
     * @return A
     */
    public function as_(callable $f) {
      $st = $this->pdo->prepare($this->query);

      $this->m->map(function($tp)use(&$st) {
        list($k, $v) = $tp;
        $st->bindValue($k, $v);
        return Unit::unit();
      });

      $st->execute();

      $arr = Seq::seq();

      foreach ($st as $value) {
        $arr = $arr->cons($value);
      }

      return $f($arr);
    }

    /**
     * Function to execute updates, inserts and deletes
     * @return int
     */
    public function executeUpdate() {
      $st = $this->pdo->prepare($this->query);

      $this->m->map(function($tp)use(&$st) {
        list($k, $v) = $tp;
        $st->bindValue($k, $v);
        return Unit::unit();
      });

      $st->execute();
      return $st->rowCount();
    }

  }
  