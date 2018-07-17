<?php

namespace App\Service\BestPath;

interface ReaderInterface
{
    public function read(string $fileInput): ?string;
}