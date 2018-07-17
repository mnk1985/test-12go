<?php

namespace App\Service\BestPath\Calculator;

use App\DTO\DestinationPoint;
use App\Model\Destination;
use App\DTO\DestinationResult;
use Doctrine\Common\Collections\ArrayCollection;

class DestinationCalculator implements DestinationCalculatorInterface
{
    /**
     * @var EndPointCalculatorInterface
     */
    private $destinationPointCalculator;

    public function __construct(EndPointCalculatorInterface $destinationPointCalculator)
    {
        $this->destinationPointCalculator = $destinationPointCalculator;
    }

    public function calculate(Destination $destination): DestinationResult
    {
        $destinationPoints = new ArrayCollection();

        /** @var float $x_summary */
        $xSummary = 0.0;

        /** @var float $y_summary */
        $ySummary = 0.0;

        foreach ($destination->getPaths() as $path) {
            $endPoint = $this->destinationPointCalculator->calculate($path);
            $destinationPoints->add($endPoint);
            $xSummary += $endPoint->getX();
            $ySummary += $endPoint->getY();
        }

        if ($destinationPoints->count() > 0) {
            $average = new DestinationPoint($xSummary / $destinationPoints->count(), $ySummary / $destinationPoints->count());
        } else {
            $average = new DestinationPoint($xSummary, $ySummary);
        }

        /** @var float $distance*/
        $distance = 0;

        foreach ($destinationPoints as $endPoint) {
            $pathDistance = $this->squaredDistance($endPoint, $average);
            if ($pathDistance > $distance) {
                $distance = $pathDistance;
            }
        }
        $distance = sqrt($distance);

        return new DestinationResult($average->getX(), $average->getY(), $distance);
    }

    /**
     * @param array $start
     * @param array $end
     * @return float
     */
    protected function squaredDistance(DestinationPoint $start, DestinationPoint $end) : float
    {
        return (($start->getX() - $end->getX()) ** 2) + (($start->getY() - $end->getY()) ** 2);
    }
}