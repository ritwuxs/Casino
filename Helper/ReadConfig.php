<?php

namespace Helper;

use Exceptions\FileDoNotExists;

class ReadConfig
{
    private array $settings = [];
    public function __construct()
    {
        if (!file_exists('.env')) {
            throw new FileDoNotExists();
        }
        $lines = file('.env', FILE_IGNORE_NEW_LINES, FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $part = explode('=', $line, 2);
            if (count($part) === 2) {
                $key = trim($part[0]);
                $value = trim($part[1]);
            }
            $this->settings[$key] = $value;
        }
    }
    public function get(string $key)
    {
        $val = $this->settings[$key] ?? null;
        if ($val === 'true') {
            return true;
        }
        if ($val === false) {
            return false;
        }
        if(is_numeric($val)){
            return (float)$val;
        }
        return $val;
    }
}
