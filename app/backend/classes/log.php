<?php

class Log {
    static $file = './uploads/logs/log.txt';

    static public function file($record){
        $fp = fopen('./uploads/logs/log.txt', 'a');
        fwrite($fp, $record ."\n");


        fclose($fp);

    }
}