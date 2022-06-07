<?php

namespace BanTheBad;

abstract class BaseListProcessor
{
    const RELATIVE = '../../text/';
    protected $writePath = '';

    public function init()
    {
        $this->setErrorHandler();
        $this->writePath = self::RELATIVE . $this->writePath;
        if ($this->writePath == self::RELATIVE)
            return; // no filename provided, nothing to remove
        if (file_exists($this->writePath))
            unlink($this->writePath);
    }

    protected function getFile($path)
    {
        if (false === strpos($path, self::RELATIVE))
            $path = self::RELATIVE . $path;
        $array = [];
        try {
            $array = file($path, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
        } catch (\Throwable $th) {
            die("failed to read file $path");
        }
        if (empty($array))
            die('file is empty');
        $array = $this->removeEOL($array);
        $array = $this->removeQuotes($array);
        return $array;
    }

    protected function saveFile($data, $msg = null)
    {
        if (is_array($data)) {
            sort($data);
            $data = array_unique($data);
            $temp = implode(PHP_EOL,  $data);
        } else
            $temp = $data;

        $result = file_put_contents($this->writePath, $this->getPrefix() . $temp,  FILE_APPEND);
        if (false === $result) {
            $msg = $msg ?? "error writing to $this->writePath";
            die($msg);
        }
    }

    protected function getPrefix()
    {
        return file_exists($this->writePath) ? PHP_EOL : '';
    }

    protected function removeEOL($dataArray)
    {
        foreach ($dataArray as &$value) {
            $value = str_replace(["\r", "\n"], "", $value);
        }
        return $dataArray;
    }

    protected function removeQuotes($dataArray)
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
    protected function processDelimiters($dataArray)
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
    protected function processLeet($dataArray)
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

    protected function setErrorHandler()
    {
        set_error_handler(function ($err_severity, $err_msg, $err_file, $err_line, array $err_context) {
            throw new \ErrorException($err_msg, 0, $err_severity, $err_file, $err_line);
        }, E_WARNING);
    }
}