<?php

  class Database {
    private static $instance = NULL;
    private $db = NULL;
    
    private function __construct() {
      $this->db = new PDO('sqlite:../database/database.db');
      if (NULL == $this->db) 
        throw new Exception("Failed to open database");
      $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
      $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    public function db() {
      return $this->db;
    }   
    
    static function instance() {
      if (NULL == self::$instance) {
        self::$instance = new Database();
      }
      return self::$instance;
    }
  }
?>