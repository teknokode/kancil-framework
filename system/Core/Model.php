<?php
namespace Kancil\Core;

use Kancil\Core\Database;

class Model 
{
    protected $db;

    // Awal create object, langsung connect database
    public function __construct()
    {
        $this->db = new Database;
        $this->db->connect();
    }

    // Dapatkan semua record pada tabel
    public function query( $sql) {
        return $this->db->query( $sql );
    }

    // Dapatkan semua record pada tabel
    public function get() {
        return $this->db->get($this->tableName);
    }

    // Dapatkan beberapa record sesuai parameter where 
    public function find( $where ) {
        return $this->db->find($this->tableName, $where); 
    }

      // Dapatkan beberapa record sesuai parameter where 
    public function insert( $data ) {
        return $this->db->insert($this->tableName, $data); 
    }

      // Dapatkan beberapa record sesuai parameter where 
    public function update( $data, $where ) {
        return $this->db->update($this->tableName, $data, $where); 
    }

      // Dapatkan beberapa record sesuai parameter where 
    public function delete( $where ) {
        return $this->db->delete($this->tableName, $where); 
    }

      // Dapatkan beberapa record sesuai parameter where 
    public function insertID() {
        return $this->db->insertID(); 
    }
}