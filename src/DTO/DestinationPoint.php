<?php

namespace App\DTO;

/** used for storing results from Path calculation
 * Class DestinationPoint
 * @package App\Model
 */
class DestinationPoint
{
    /** @var float */
    private $x;

    /** @var float */
    private $y;

    public function __construct(float $x, float $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function getX(): float
    {
        return $this->x;
    }

    public function getY(): float
    {
        return $this->y;
    }
}