<?php

namespace Helper;

use Exceptions\InvalidArgumentException;
use Exceptions\FileDoNotExists;

class JsonStorage
{
    private string $filePath;
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }
    public function read(): array
    {
        if (!file_exists($this->filePath)) {
            throw new FileDoNotExists();
        }
        $content = file_get_contents($this->filePath);
        if (empty(trim($content))) {
            return [];
        }
        $data = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            throw new InvalidArgumentException();
        }
        return $data;
    }
    public function write(array $data): void
    {
        $jsonString = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($this->filePath, $jsonString);
    }
}
