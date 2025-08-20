<?php

namespace Kancil\Core;

use Kancil\Drivers\Mysql;

// MySQL
if (true) {
    $driverClass = 'Kancil\Drivers\Mysql';
}
// Other
if (false) {
    $driverClass = 'Kancil\Drivers\Rethinkdb';
}

class_alias($driverClass, 'Kancil\Core\DatabaseDriver');

class Database extends DatabaseDriver
{
    protected $db;

    // Awal langsung connect
    function __construct()
    {
        $this->connect();
    }

    // Fungsi-fungsi query berasal dari DatabaseDriver - inherit
    // function skema()
    // {
    //     return $this->db->select("skema", "*");
    // }

}
