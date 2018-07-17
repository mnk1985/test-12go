<?php

namespace App\Service\BestPath;

use App\DTO\DestinationResults;

interface WriterInterface
{
    public function write(string $fileInput, DestinationResults $results): bool;
}