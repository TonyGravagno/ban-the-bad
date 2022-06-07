<?php

namespace BanTheBad;

require_once __DIR__ . "\ParseFreeWebHeaders.php";
require_once __DIR__ . "\ParseTuralus.php";
require_once __DIR__ . "\Aggregator.php";
require_once __DIR__ . "\GenerateTG.php";

use BanTheBad\BaseListProcessor;

class ProcessAll
{

    public function process()
    {
        (new ParseFreeWebHeaders())->process();
        (new ParseTuralus())->process();
        (new Aggregator())->process();
        (new GenerateTG())->process();
    }
}

(new ProcessAll())->process();