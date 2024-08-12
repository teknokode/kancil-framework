<?php

namespace Kancil\Interfaces;

interface Database {
    public function connect();
    public function select( $table );
    public function find( $table, $keys );

    public function query( $sql );
    // public function all( $table );
    // public function single( $table, $where );
    // public function update( $table, $data, $where );
    // public function insert( $table, $data );
    // public function delete( $table, $where );
    // public function insertID();
}