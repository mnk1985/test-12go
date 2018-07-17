<?php

namespace App\Service\BestPath;

use App\Model\Destinations;

interface ParserInterface
{
    public function run(string $input): Destinations;
}