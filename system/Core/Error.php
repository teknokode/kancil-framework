<?php
namespace Kancil\Core;

use Kancil\Core\Parser;
use Kancil\Core\Api;

class Error 
{
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