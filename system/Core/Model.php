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
    }

    function getAll()
    function getByID()

}