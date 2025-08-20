<?php

namespace Kancil\Core;

use Kancil\Core\Parser;

class Controller
{
    public $parser;

    public function __construct()
    {
        $this->parser = new Parser();
        // echo "Ini adalah induk controller \n";
    }
}
