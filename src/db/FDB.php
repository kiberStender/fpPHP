<?php

/**
 * Description of FDB
 *
 * @author sirkleber
 */
namespace db;

set_include_path(dirname(__FILE__) . "/../");

require_once 'maybe/Maybe.php';

use maybe\Just;
use maybe\Nothing;

class FDB {
  
  /**
   * Static function for getting the database configuration
   * @version 2.0
   * @return Maybe
   */
  private final static function getDBConfig() {
    $filename = Utils::getServer() . "config/dbconfig.json";
    $file = file_get_contents($filename);
    
    if($file == NULL){
      return Nothing::Nothing();
    } else {
      return new Just(json_decode($file));
    }
  }
  
  /**
   * Methodo para conexão com banco de dados
   * @version 3.5
   * @return Maybe
   */
  private final static function connect(){
    return self::getDBConfig()->flatMap(function($json){
      $dsn = call_user_func(function() use($json){
        if ($json->db === ""){
          return "{$json->dbms}:host={$json->host};port={$json->port};";
        } else{
          return "{$json->dbms}:host={$json->host};dbname={$json->db};port={$json->port};";
        }
      });
      
      try {
        $pdo = new PDO($dsn, $json->username, $json->passwd);
        
        return new Just(array($pdo, null));
      }catch (PDOException $ex) {
        return new Just(array(null, $ex->getMessage()));
      }
    });
  }
  
  private final static function disconnect(array $arr){
    $pdo = $arr[0];
    $data = $arr[1];
    
    $pdo = null;
    return $data;
  }
  
  /**
   * 
   * @param $fn
   * @version 3.5
   * @return Maybe
   */
  public static function withConnection($fn){
    return self::disconnect(self::connect()->flatMap(function($tuple) use($fn){
      $pdo = $tuple[0];
      $ex = $tuple[1];
      
      if(isset($pdo) && $pdo instanceof PDO){
        return array($pdo, $fn($pdo));
      } else {
        return array($pdo, $ex);
      }
    }));
  }
}