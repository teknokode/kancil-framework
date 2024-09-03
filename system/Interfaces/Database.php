<?php

namespace Kancil\Interfaces;

interface Database 
{
    // Wajib
    public function connect();

    // Jalankan query umum / mentah
    public function query( $sql );

    // Baca seluruh isi tabel    
    public function get( $table );

    // Cari record pada sebuah tabel
    public function find( $table, $where );

    // public function update( $table, $data, $where );
    // public function insert( $table, $data );
    // public function delete( $table, $where );
    // public function lastID();
    // public function rowCount();


}