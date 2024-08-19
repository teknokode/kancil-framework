<?php
namespace Kancil\Core;

use Kancil\Core\Parser;

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
            return responseError($message = "Alamat tidak ditemukan", $code = 404, $data = []);
        }
    }
}