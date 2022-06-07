<?php

namespace BanTheBad;

require_once __DIR__ . "\BaseListProcessor.php";

use BanTheBad\BaseListProcessor;

class GenerateTG extends BaseListProcessor
{
    function __construct()
    {
        $this->writePath = 'banned_whole_usernames/banned_whole_usernames_tg.txt';
        $this->init();
    }

    public function process()
    {
        $this->write('banned_whole_usernames/banned_whole_usernames.txt');
    }

    public function write($path)
    {
        $dataArray = $this->getFile($path);
        $dataArray = $this->processDelimiters($dataArray);
        //$dataArray = $this->processLeet($dataArray);

        $this->saveFile($dataArray);
    }
}

(new GenerateTG())->process();
