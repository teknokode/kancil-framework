<?php
namespace Kancil\Core;

use Kancil\Core\Parser;

class Error 
{
    public function page404()
    {
        $parser = new Parser;

        echo $parser->render("page404.html");
    }
}