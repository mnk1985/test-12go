<?php

namespace App\DTO;

class DestinationResult
{
    /** @var float */
    private $x;

    /** @var float */
    private $y;

    /** the distance between the worst directions and the averaged destination
     * @var float
     */
    private $diff;

    public function __construct(float $x, float $y, float $diff)
    {
        $this->x = $x;
        $this->y = $y;
        $this->diff = $diff;
    }

    public function getX(): float
    {
        return $this->x;
    }

    public function getY(): float
    {
        return $this->y;
    }

    public function getDiff(): float
    {
        return $this->diff;
    }

}