<?php

class GenerateTG
{
    const RELATIVE = '../../text/';
    const READPATH = 'banned_whole_usernames/banned_whole_usernames.txt';
    const WRITEPATH = 'banned_whole_usernames/banned_whole_usernames_tg.txt';

    public function process()
    {
        if (file_exists(self::RELATIVE . self::WRITEPATH))
            unlink(self::RELATIVE . self::WRITEPATH);

        $dataArray = $this->getFile(self::RELATIVE . self::READPATH);

        set_error_handler(function ($err_severity, $err_msg, $err_file, $err_line, array $err_context) {
            throw new ErrorException($err_msg, 0, $err_severity, $err_file, $err_line);
        }, E_WARNING);

        $dataArray = $this->removeQuotes($dataArray);
        $dataArray = $this->processDelimiters($dataArray);
        //$dataArray = $this->processLeet($dataArray);
        sort($dataArray);
        $dataArray = array_unique($dataArray);

        $prefix = file_exists(self::RELATIVE . self::WRITEPATH) ? PHP_EOL : '';
        $result = file_put_contents(self::RELATIVE . self::WRITEPATH, $prefix . implode(PHP_EOL,  $dataArray),  FILE_APPEND);
        if (false === $result)
            die("error writing to self::RELATIVE self::WRITEPATH");
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
    private function removeQuotes($dataArray)
    {
        // remove quotes
        foreach ($dataArray as &$value) {
            $value = str_replace(["'", '"'], "", $value);
        }
        return $dataArray;
    }

    /**
     * Add alternative delimiters where space is used
     * Full version is not used for user names, maybe for text later.
     *
     */
    private function processDelimiters($dataArray)
    {
        $added = [];
        foreach ($dataArray as &$value) {
            $value = trim($value);
            // short version
            $morphed = str_replace([' ', '_', '-', '^', '#', '!', '*', '%', '&'], "`", $value);
            if ($morphed !== $value) {
                $added[] = str_replace('`', "_", $morphed);
                $added[] = str_replace('`', "-", $morphed);
                $value = str_replace('`', '', $morphed);
            }

            /** full version
            $morphed = str_replace([' ', '_', '-', '^', '#', '!', '*', '%', '&'], "`", $value);
            if ($morphed !== $value) {
                $added[] = str_replace('`', " ", $morphed);
                $added[] = str_replace('`', "_", $morphed);
                $added[] = str_replace('`', "-", $morphed);
                $added[] = str_replace('`', "^", $morphed);
                $added[] = str_replace('`', "#", $morphed);
                $added[] = str_replace('`', "!", $morphed);
                $added[] = str_replace('`', "*", $morphed);
                $added[] = str_replace('`', "%", $morphed);
                $added[] = str_replace('`', "&", $morphed);
                $value = str_replace('`', '', $morphed);
            }
             */
        }
        if (!empty($added))
            $dataArray = array_merge($dataArray, $added);
        return $dataArray;
    }
    /**
     * Add alternatives using Leet
     * Not used for user names, maybe for text later.
     * Examples: ass becomes 455, 4ss, and a55.
     * This doesn't process all combinations, it just intends to create a number of
     * possible hit values if someone does try to use simple leeting.
     */
    private function processLeet($dataArray)
    {
        $added = [];
        foreach ($dataArray as &$value) {
            $morphed = str_replace('o', '0', $value);
            if ($morphed !== $value)
                $added[] = $morphed;
            $morphed = str_replace('i', '1', $value);
            if ($morphed !== $value)
                $added[] = $morphed;
            $morphed = str_replace('a', '4', $value);
            if ($morphed !== $value)
                $added[] = $morphed;
            $morphed = str_replace('e', '3', $value);
            if ($morphed !== $value)
                $added[] = $morphed;
            $morphed = str_replace('s', '5', $value);
            if ($morphed !== $value)
                $added[] = $morphed;
        }
        if (!empty($added))
            $dataArray = array_merge($dataArray, $added);
        return $dataArray;
    }
}

(new GenerateTG())->process();