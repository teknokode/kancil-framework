<?php

namespace Kancil\Drivers;

use Kancil\Interfaces\Database;
//use Medoo\Medoo;
use \PDO;
use \PDOException;

class Mysql implements Database {
    
    public function connect() {
        // $this->db = new Medoo([
        //     // [required]
        //     'type' => 'mysql',
        //     'host' => 'localhost',
        //     'database' => 'sertifikasi_240625',
        //     'username' => 'root',
        //     'password' => 'root',
         
        //     // [optional]
        //     'charset' => 'utf8mb4',
        //     'collation' => 'utf8mb4_general_ci',
        //     'port' => 3306,
        // ]);

        // $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4";
        // $options = [
        //     PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        //     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        //     PDO::ATTR_EMULATE_PREPARES   => false,
        // ];
        // $this->db = new PDO($dsn, $user, $pass, $options);

        $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        
        try 
        {
            $this->db = new PDO( $dsn, DB_USER, DB_PASS, $options);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        } catch(PDOException $e) {

            pd("Connection failed: " . $e->getMessage());
        }
    }


    public function query( $sql )
    {
        try {
          
            $stmt = $this->db->prepare($sql);
            $stmt->execute();

        } catch(PDOException $e) {
          
            pd("Error: " . $e->getMessage());

        }
        return $stmt->fetchAll();
    }

    public function select( $table ) 
    {
        return $this->query("SELECT * FROM $table");
    }
    
    public function find( $table, $where ) 
    {
        return $this->query("SELECT * FROM $table WHERE $where");
    }

    public function update() {}
    public function insert() {}
    public function delete() {}
    public function insertID() {}
}