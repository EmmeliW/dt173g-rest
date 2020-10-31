<?php

class Database {
    private $host = 'localhost';
    private $db_name = 'emmlan_portfolio';
    private $db_username = 'emmlan';
    private $db_password = 'rt41MAoi0';

    public function connect() {
        try {
          $pdo = new PDO('mysql:host=' . $this->host . ';db_name=' . $this->db_name, $this->db_username, $this->db_password, 
          array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
          $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
          $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
          return $pdo;
        } catch(PDOException $e) {
          echo 'Connection Error: ' . $e->getMessage();
        }
    }
}