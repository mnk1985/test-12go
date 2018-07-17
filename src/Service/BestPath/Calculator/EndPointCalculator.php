<?php

namespace App\Service\BestPath\Calculator;

use App\DTO\DestinationPoint;
use App\Exception\InvalidArgumentException;
use App\Model\Path;
use App\Model\Start;
use App\Model\Turn;
use App\Model\Walk;

class EndPointCalculator implements EndPointCalculatorInterface
{

    /**
     * @param Path $path
     * @return DestinationPoint
     * @throws InvalidArgumentException
     */
    public function calculate(Path $path): DestinationPoint
    {
        $x = $path->getStartX();
        $y = $path->getStartY();
        $degree = 0.0;

        foreach ($path->getDirections() as $direction) {

            if ($direction instanceof Start) {
                $degree = $direction->getValue();
                continue;
            }

            if ($direction instanceof Turn) {
                $degree = $degree + $direction->getValue();
                continue;
            }

            if (! $direction instanceof Walk) {
                throw new InvalidArgumentException('unexpected type of Path');
            }

            $x += $direction->getValue() * cos(deg2rad($degree));
            $y += $direction->getValue() * sin(deg2rad($degree));
        }

        return new DestinationPoint($x, $y);
    }
}