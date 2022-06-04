<?php

class ParseFreeWebHeaders
{
    const RELATIVE = '../../text/';
    const WRITEPATH = 'banned_whole_usernames/freewebheaders.txt';

    public function doParse()
    {
        if (file_exists(self::RELATIVE . self::WRITEPATH))
            unlink(self::RELATIVE . self::WRITEPATH);

        $this->write(self::RELATIVE . 'full-list-of-bad-words-banned-by-google.txt');
        $this->write(self::RELATIVE . 'british-swear-words-list-and-bad-words.txt');
    }

    private function write($path)
    {
        $dataArray = $this->getFile($path);
        $isFirst = true;

        set_error_handler(function ($err_severity, $err_msg, $err_file, $err_line, array $err_context) {
            throw new ErrorException($err_msg, 0, $err_severity, $err_file, $err_line);
        }, E_WARNING);

        $dataArray = $this->removeHeader($dataArray, 1);
        sort($dataArray);
        $dataArray = array_unique($dataArray);
        $dataArray = $this->removeHeader($dataArray, 2);
        $dataArray = $this->removeEOL($dataArray);

        $prefix = file_exists(self::RELATIVE . self::WRITEPATH) ? PHP_EOL : '';
        $result = file_put_contents(self::RELATIVE . self::WRITEPATH, $prefix . implode(PHP_EOL,  $dataArray),  FILE_APPEND);
        if (false === $result)
            die("error writing to self::RELATIVE self::WRITEPATH");
    }

    private function removeHeader($dataArray, $flag)
    {
        $ok = false;
        for ($i = 0; 100; $i++) {
            if (empty($dataArray))
                break;
            if (1 == $flag)
                $emptyLine = false;
            if (2 == $flag)
                $emptyLine = empty(trim($dataArray[0]));
            if (false !== strpos($dataArray[0], '------') || $emptyLine) {
                array_shift($dataArray); // remove break line
                $ok = true;
                break;
            }
            array_shift($dataArray); // remove current line, next line becomes 0
        }
        if (!$ok)
            die("Didn't find header line");
        return $dataArray;
    }
    private function getFile($path)
    {
        $array = [];
        try {
            $array = file($path, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
        } catch (\Throwable $th) {
            die('failed to read file');
        }
        if (empty($array))
            die('file is empty');
        return $array;
    }
    private function removeEOL($dataArray)
    {
        foreach ($dataArray as &$value) {
            $value = str_replace(["\r", "\n"], "", $value);
        }
        return $dataArray;
    }
}

(new ParseFreeWebHeaders())->doParse();