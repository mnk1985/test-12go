<?php

namespace App\Service\BestPath;

use App\DTO\DestinationResults;

class Writer implements WriterInterface
{

    public function write(string $fileInput, DestinationResults $results): bool
    {
        if (!$file = fopen($fileInput, "w")) {
            return false;
        }

        foreach ($results as $result) {
            fwrite($file,
                (float)number_format($result->getX(), 4, '.', '').' '.
                (float)number_format($result->getY(), 4).' '.
                (float)number_format($result->getDiff(), 5).PHP_EOL);
        }

        fclose($file);
        return true;
    }
}