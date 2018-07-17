<?php

namespace App\Service\BestPath\Calculator;

use App\DTO\DestinationPoint;
use App\Model\Path;

interface EndPointCalculatorInterface
{
    public function calculate(Path $path): DestinationPoint;
}