<?php

namespace App\Model;

use App\Exception\InvalidArgumentException;

abstract class PathDirection
{
    const ARGUMENT_TOKEN = 'ARGUMENT';
    const VALUE_MIN = -1000;
    const VALUE_MAX = 1000;
    
    /** @var float */
    private $value;

    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @param float $value
     * @throws InvalidArgumentException
     */
    public function setValue(float $value)
    {
        if ($value < self::VALUE_MIN || self::VALUE_MAX < $value) {
            throw new InvalidArgumentException('direction argument should be between -1000 and 1000');
        }
        $this->value = $value;
    }

}