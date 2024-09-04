<?php
namespace Kancil\Core;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth 
{
    public $key = SECRET_KEY;

    // Membuat token JWT
    public function createJwtToken( $payload )
    {
        return JWT::encode($payload, $this->key, 'HS256');
    }

    // Verifikasi token JWT
    public function verifyJwtToken( $token )
    {
        return JWT::decode($token, new Key($this->key, 'HS256'));
    }

    // Mendapatkan header JWT
    public function getJwtHeaders( $token )
    {
        $headers = new stdClass();
        JWT::decode($token, new Key($this->key, 'HS256'), $headers);
        return $headers;
    }

    // User login, bisa untuk web atau API
    public function userLogin( $db, $username, $password )
    {
        $result = $db->find( USERS_TABLE , 
                             USERNAME_FIELD."='$username' AND ".PASSWORD_FIELD."= '$password'" );
        if ($result)
        {
            $_SESSION["users"]= json_encode($result[0]);
            $_SESSION["logged_in"]= true;
            return true;
        }
        return false;
    }

    // Untuk mengubah secret key
    public function setKey( $key )
    {
        $this->key = $key;
        return true;
    }
}