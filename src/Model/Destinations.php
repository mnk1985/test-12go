<?php

namespace App\Model;

use App\Exception\DestinationsException;
use Doctrine\Common\Collections\ArrayCollection;

class Destinations extends ArrayCollection
{
    const MAX_ELEMENTS = 100;

    /**
     * @param Destination $destination
     * @throws DestinationsException
     */
    public function addDestination(Destination $destination)
    {
        if (!$destination->isValid()) {
            return;
        }

        if ($this->count() >= self::MAX_ELEMENTS) {
            throw new DestinationsException('only up to 100 destinations (test cases) allowed');
        }

        $this->add($destination);
    }
}