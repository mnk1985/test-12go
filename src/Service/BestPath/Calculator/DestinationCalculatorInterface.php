<?php

namespace App\Service\BestPath\Calculator;

use App\Model\Destination;
use App\DTO\DestinationResult;

interface DestinationCalculatorInterface
{
    public function calculate(Destination $destination): DestinationResult;
}