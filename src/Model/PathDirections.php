<?php

namespace App\Model;

use App\DTO\Token;
use App\Exception\InvalidArgumentException;
use App\Exception\PathDirectionsException;
use Doctrine\Common\Collections\ArrayCollection;

class PathDirections extends ArrayCollection
{

    /**
     * Paths constructor.
     * @param ArrayCollection $tokens
     * @throws PathDirectionsException
     */
    public function __construct(ArrayCollection $tokens)
    {
        parent::__construct();
        $direction = null;
        /** @var Token $token */
        foreach ($tokens as $token) {

            switch($token->getName()){
                case Turn::TURN_TOKEN:
                    $direction = new Turn();
                    break;
                case Walk::WALK_TOKEN:
                    $direction = new Walk();
                    break;
                case Start::START_TOKEN:
                    $direction = new Start();
                    break;
                case PathDirection::ARGUMENT_TOKEN:

                    if (!$direction) {
                        throw new PathDirectionsException('direction is not initialized yet, can\'t set its value');
                    }

                    if (! $direction instanceof PathDirection) {
                        throw new PathDirectionsException('direction should be instance of PathDirection');
                    }
                    try {
                        $direction->setValue($token->getValue());
                    } catch (InvalidArgumentException $e) {
                        throw new PathDirectionsException($e->getMessage());
                    }
                    $this->add($direction);
                    $direction = null;
                    break;
                default:
                    throw new PathDirectionsException('not expected token: '. $token->getName());
            }
        }

    }
}