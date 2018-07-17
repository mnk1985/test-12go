<?php

namespace App\Service\BestPath;

class Reader implements ReaderInterface
{
    public function read(string $fileInput): ?string
    {
        $content = file_get_contents($fileInput);

        return ($content !== false) ? $content : null;
    }
}