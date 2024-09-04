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
    public function get() {
        return $this->db->get($this->tableName);
    }

    // Dapatkan beberapa record sesuai parameter where 
    public function find( $where ) {
        return $this->db->find($this->tableName, $where); 
    }
}