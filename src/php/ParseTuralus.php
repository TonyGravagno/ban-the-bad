<?php

class ParseTuralus
{
    const RELATIVE = '../../text/';

    public function doParse()
    {
        $path = self::RELATIVE . 'turalus_encycloDB_master_Dirty_Words_DirtyWords.csv';
        $dataArray = $this->getFile($path);
        $isFirst = true;

        set_error_handler(function ($err_severity, $err_msg, $err_file, $err_line, array $err_context) {
            throw new ErrorException($err_msg, 0, $err_severity, $err_file, $err_line);
        }, E_WARNING);

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
            try {
                $writePath = self::RELATIVE . "turalus/turalus_$language.txt";
                if (!in_array($language, $languages)) {
                    if (file_exists($writePath))
                        unlink($writePath);
                    $languages[] = $language;
                }
                $prefix = file_exists($writePath) ? PHP_EOL : '';
                $result = file_put_contents($writePath, $prefix . $word,  FILE_APPEND);
                if (false === $result)
                    die("error with $writePath and $word, $line");
            } catch (\Throwable $th) {
                // warning is now a throwable ErrorException, handle if desired
                die("error with $writePath and $word, $line");
            }
        }
    }
    private function getFile($path)
    {
        $array = [];
        try {
            $array = file($path, FILE_SKIP_EMPTY_LINES);
        } catch (\Throwable $th) {
            die('failed to read file');
        }
        if (empty($array))
            die('file is empty');
        return $array;
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

(new ParseTuralus())->doParse();