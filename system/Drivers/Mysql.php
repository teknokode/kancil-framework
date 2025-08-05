<?php

namespace Kancil\Drivers;

//use Kancil\Interfaces\Database;
//use Medoo\Medoo;

use \PDO;
use \PDOException;

//class Mysql implements Database {
class Mysql {

    protected $pdo;

    // public function connect()
    // {
    //     $this->pdo = new Medoo([
    //         'type' => 'mysql',
    //         'host' => DB_HOST,
    //         'database' => DB_NAME,
    //         'username' => DB_USER,
    //         'password' => DB_PASS
    //     ]);
    //     return $this->pdo;
    // }
    
    public function connect() 
    {
        $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        
        try 
        {
            $this->pdo = new PDO( $dsn, DB_USER, DB_PASS, $options);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        } catch(PDOException $e) {

            pd("Connection failed: " . $e->getMessage());
        }
    }


    public function query( $sql )
    {
        try {
          
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();

        } catch(PDOException $e) {
          
            pd("Error: " . $e->getMessage());

        }
        return $stmt->fetchAll();
    }

    public function get( $table ) 
    {
        return $this->query("SELECT * FROM $table");
    }

    // public function get( $table ) 
    // {
    //     return $this->pdo->select($table,"*");
    // }


    public function find( $table, $where ) 
    {
        return $this->query("SELECT * FROM $table WHERE $where");
    }

    // public function where( $table, $column, $where ) 
    // {
    //     return $this->where($table, $column, $where );
    // }

    // public function update() {}
    // public function insert() {}
    // public function delete() {}
    // public function insertID() {}

    public function insert($table, $data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
        
        return $stmt->rowCount();
    }

    public function update($table, $data, $where) {
        $set = "";
        foreach ($data as $key => $value) {
            $set .= "$key = :$key, ";
        }
        $set = rtrim($set, ", ");

        $sql = "UPDATE $table SET $set WHERE $where";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);

        return $stmt->rowCount();
    }

    public function delete($table, $where) {
        $sql = "DELETE FROM $table WHERE $where";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        
        return $stmt->rowCount();
    }

    public function insertID() {
        return $this->pdo->lastInsertId();
    }

}