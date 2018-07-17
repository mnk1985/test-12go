<?php

namespace App\Model;

use App\DTO\Token;
use App\Exception\InvalidArgumentException;
use Doctrine\Common\Collections\ArrayCollection;

/** holds information on 1 person's direction
 * Class Path
 * @package App\DTO
 */
class Path
{
    const START_TOKEN = 'START';

    /** @var float */
    private $startX;

    /** @var float */
    private $startY;

    /** @var ArrayCollection|PathDirection[] */
    private $directions;

    /**
     * Path constructor.
     * @param ArrayCollection $tokens
     * @throws InvalidArgumentException
     */
    public function __construct(ArrayCollection $tokens)
    {
        if ($tokens->count() == 0) {
            throw new InvalidArgumentException('invalid empty paths');
        }

        /** @var Token $startXToken */
        $startXToken = $tokens->first();
        $this->startX = $startXToken->getValue();

        /** @var Token $startYToken */
        $startYToken = $tokens->offsetGet(1);
        $this->startY = $startYToken->getValue();

        $this->directions = new PathDirections(new ArrayCollection($tokens->slice(2)));
    }

    public function getStartX(): float
    {
        return $this->startX;
    }

    public function getStartY(): float
    {
        return $this->startY;
    }

    /**
     * @return PathDirection[]|ArrayCollection
     */
    public function getDirections()
    {
        return $this->directions;
    }

    public function addDirection(PathDirection $direction): void
    {
        $this->directions->add($direction);
    }

}