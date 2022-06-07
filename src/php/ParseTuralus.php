<?php

namespace BanTheBad;

require_once __DIR__ . "\BaseListProcessor.php";

use BanTheBad\BaseListProcessor;

class ParseTuralus extends BaseListProcessor
{
    function __construct()
    {
        $this->writePath = ''; // not used like it is in other modules
        $this->init();
    }

    public function process()
    {
        $this->write('turalus_encycloDB_master_Dirty_Words_DirtyWords.csv');
    }

    public function write($path)
    {
        $path = self::RELATIVE . $path;
        $dataArray = $this->getFile($path);
        $isFirst = true;

        $languages = [];
        foreach ($dataArray as $line) {
            if ($isFirst) { // skip single line column header
                $isFirst = false;
                continue;
            }
            $line = $this->cleanLine($line, true);
            try {
                $array = explode(',', $line);
                if (isset($array[2]))
                    [$key, $word, $language] = $array;
                else
                    die("failed to explode $line");
            } catch (\Throwable $th) {
                die("failed to explode $line");
            }
            $word = $this->cleanLine($word, false);
            $this->writePath = self::RELATIVE . "turalus/turalus_$language.txt";
            if (!in_array($language, $languages)) {
                if (file_exists($this->writePath))
                    unlink($this->writePath);
                $languages[] = $language;
            }
            $this->saveFile($word, "error with $this->writePath and $word, $line");
        }
    }

    function cleanLine($line, $start = true)
    {
        if (!$start)
            return str_replace("~", ",", $line);

        $line = str_replace("\r\n", "", $line);

        $unquote = function ($line, $quote) {
            $quoted = explode($quote, $line);
            if (isset($quoted[1]) && isset($quoted[2])) {
                $quoted[1] = str_replace(",", "~", $quoted[1]);
                $line = implode($quote, $quoted);
            }
            return $line;
        };
        $line = $unquote($line, "'");
        $line = $unquote($line, '"');
        $line = mb_convert_case($line, MB_CASE_LOWER);
        return $line;
    }
}

(new ParseTuralus())->process();
