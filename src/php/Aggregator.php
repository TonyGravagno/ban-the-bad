<?php

namespace BanTheBad;

require_once __DIR__ . "\BaseListProcessor.php";

use BanTheBad\BaseListProcessor;

class Aggregator extends BaseListProcessor
{
    function __construct()
    {
        $this->writePath = 'banned_whole_usernames/banned_whole_usernames.txt';
        $this->init();
    }

    public function process()
    {
        $this->write('turalus/turalus_en.txt');
        $this->write('banned_whole_usernames/biglou_resources_bad-words.txt');
        $this->write('banned_whole_usernames/freewebheaders.txt');
        $this->write('perishable/universal.txt');
        $this->write('perishable/was_wp_org.txt');
    }

    private function write($path)
    {
        $dataArray = $this->getFile($path);
        $this->saveFile($dataArray);
    }
}
(new Aggregator())->process();
