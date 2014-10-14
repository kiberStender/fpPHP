<?php

/**
 * Description of SQL
 *
 * @author sirkleber
 */
class SQL {
    private $query;
    private $pdo;
    
    public static function sql(PDO $pdo, $query){
        return new SQL($pdo, $query);
    }
    
    private function __construct(PDO $pdo, $query) {
        $this->pdo = $pdo;
        $this->query = $query;
    }
}
