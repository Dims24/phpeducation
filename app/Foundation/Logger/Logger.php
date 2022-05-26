<?php

namespace App\Foundation\Logger;

use App\Foundation\Application;

class Logger
{
    protected string $PATH; #папка с лог-файлами
    protected string $name; #имя текущего логгера
    protected string $file; #путь к файлу

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->getFile();
        $this->open();
    }

    public function open(): void
    {
        $this->fp = fopen($this->file, 'a');
    }

    /**
     * @return string
     */
    public function getFile(): void
    {
        $app = Application::getInstance();
        $this->PATH = $app->getRootPath() . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'logs';
        $this->file = $this->PATH . DIRECTORY_SEPARATOR . $this->name . ".log";
    }

    public function log($message): void
    {
        $log = "";

        foreach ($message as $key => $value) {
            if ($key == 'trace') {
                continue;
            }
            if ($key == 'code') {
                $log .= "[$value] ";
                continue;
            }

            $log .= $value . " ";
        }

        $this->write($log.PHP_EOL);
    }

    protected function write($string): void
    {
        fwrite($this->fp, $string);
        fclose($this->fp);
    }
}