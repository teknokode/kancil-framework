<?php

namespace Kancil\Core;

use Kancil\Core\Database;

class Model 
{
    protected $db;

    // Awal create, langsung connect database
    public function __construct()
    {
        $this->db = new Database;
        $this->db->connect();
    }

    public function get() {
        return $this->db->get($this->tableName);
    }

    public function find( $where ) {
        return $this->db->find($this->tableName, $where); 
    }
}