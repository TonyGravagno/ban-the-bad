<?php

namespace BanTheBad;

require_once __DIR__ . "\BaseListProcessor.php";

use BanTheBad\BaseListProcessor;

class ParseFreeWebHeaders extends BaseListProcessor
{
    function __construct()
    {
        $this->writePath = 'banned_whole_usernames/freewebheaders.txt';
        $this->init();
    }

    public function process()
    {
        $this->write('full-list-of-bad-words-banned-by-google.txt');
        $this->write('british-swear-words-list-and-bad-words.txt');
    }

    private function write($path)
    {
        $dataArray = $this->getFile($path);

        $dataArray = $this->removeHeader($dataArray, 1);

        sort($dataArray);
        $dataArray = array_unique($dataArray);

        $dataArray = $this->removeHeader($dataArray, 2);

        $this->saveFile($dataArray);
    }

    private function removeHeader($dataArray, $flag)
    {
        $original = $dataArray;
        $found = false;
        $limit = count($dataArray) < 50 ? count($dataArray) : 50;
        for ($i = 0; $limit; $i++) {
            if (empty($dataArray))
                break;
            if (1 == $flag)
                $emptyLine = false;
            if (2 == $flag)
                $emptyLine = empty(trim($dataArray[0]));
            if (false !== strpos($dataArray[0], '------') || $emptyLine) {
                array_shift($dataArray); // remove break line
                $found = true;
                break;
            }
            array_shift($dataArray); // remove current line, next line becomes 0
        }
        if (!$found)
            return $original;
        return $dataArray;
    }
}

(new ParseFreeWebHeaders())->process();