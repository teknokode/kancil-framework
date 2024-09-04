<?php
namespace Kancil\Core;

use Kancil\Core\Parser;
use Kancil\Core\Api;

class Error 
{
    // Kalau ada error 404, langsung arahkan ke halaman view page404.html
    public function page404()
    {
        $parser = new Parser;

        if (isBrowser()) 
        {
            return $parser->render("page404.html");
        } else 
        {
            $api = new Api;
            return $api->responseError($message = "Alamat tidak ditemukan", $code = 404, $data = []);
        }
    }
}