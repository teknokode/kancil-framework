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
        // $this->database = new Medoo([
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