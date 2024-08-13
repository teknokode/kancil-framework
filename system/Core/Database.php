<?php
namespace Kancil\Core;

use Kancil\Drivers\Mysql;
//use Medoo\Medoo;

// MySQL
if ( true) {
    $driverClass = 'Kancil\Drivers\Mysql';
} 
// Other
if ( false ) {
    $driverClass = 'Kancil\Drivers\Rethinkdb';
}

class_alias( $driverClass, 'Kancil\Core\DatabaseDriver');

//class Database extends Mysql
class Database extends DatabaseDriver
{
    protected $db;

    function __construct()
    {
        $this->connect();
    }

    // public function connect();
    // public function select();
    // public function update();
    // public function insert();
    // public function delete();
    // public function insertID();

    function skema()
    {
        return $this->db->select("skema", "*");
    }

}