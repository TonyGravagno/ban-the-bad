<?php

class Aggregator
{
    const RELATIVE = '../../text/';
    const WRITEPATH = 'banned_whole_usernames/banned_whole_usernames.txt';

    public function aggregate()
    {

        if (file_exists(self::WRITEPATH))
            unlink(self::WRITEPATH);

        $this->write(self::RELATIVE . 'turalus/turalus_en.txt');
        $this->write(self::RELATIVE . 'banned_whole_usernames/biglou_resources_bad-words.txt');
        $this->write(self::RELATIVE . 'banned_whole_usernames/freewebheaders.txt');



        $dataArray = file(self::RELATIVE . self::WRITEPATH, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        sort($dataArray);
        $dataArray = array_unique($dataArray);
        $dataArray = $this->removeEOL($dataArray);
        $result = file_put_contents(self::RELATIVE . self::WRITEPATH, implode(PHP_EOL,  $dataArray));
        if (false === $result)
            die("error writing to self::WRITEPATH");
    }

    private function write($path)
    {
        $to_add = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $to_add = $this->removeEOL($to_add);
        $prefix = file_exists(self::RELATIVE . self::WRITEPATH) ? PHP_EOL : '';
        $result = file_put_contents(self::RELATIVE . self::WRITEPATH, $prefix . implode(PHP_EOL,  $to_add),  FILE_APPEND);
        if (false === $result)
            die("error writing $path to self::WRITEPATH");
    }
    private function removeEOL($dataArray)
    {
        foreach ($dataArray as &$value) {
            $value = str_replace(["\r", "\n"], "", $value);
        }
        return $dataArray;
    }
}
(new Aggregator())->aggregate();