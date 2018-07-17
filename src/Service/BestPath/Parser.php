<?php

namespace App\Service\BestPath;

use App\Exception\DestinationsException;
use App\Model\Destination;
use App\Model\Destinations;
use App\Model\Path;
use App\Model\PathDirection;
use App\Model\Start;
use App\DTO\Token;
use App\Model\Turn;
use App\Model\Walk;
use App\Exception\ParserException;
use Doctrine\Common\Collections\ArrayCollection;

class Parser implements ParserInterface
{
    private $globalTerminals = [
        "/^(\d+)$/" => Destination::ASKED_TOKEN,
        "/start/" => Destination::PATH_TOKEN,
    ];

    private $pathTerminals = [
        "/^start/" => Start::START_TOKEN,
        "/^walk/" => Walk::WALK_TOKEN,
        "/^turn/" => Turn::TURN_TOKEN,
        "/^[+-]?([0-9]*[.])?[0-9]+/" => PathDirection::ARGUMENT_TOKEN,
    ];

    /**
     * @param string $content
     * @return ArrayCollection|Destination[]
     * @throws ParserException
     */
    public function run(string $content): Destinations
    {
        $lines = explode(PHP_EOL, $content);
        $destination = null;
        $destinations = new Destinations();

        foreach ($lines as $line) {

            $result = $this->match($line, $this->globalTerminals);
            if (!$result instanceof Token) {
                continue;
            }

            if ($result->getName() == Destination::ASKED_TOKEN) {
                if ($destination){
                    try {
                        $destinations->addDestination($destination);
                    } catch (DestinationsException $e) {
                        throw new ParserException($e->getMessage());
                    }
                }

                $destination = new Destination($result->getValue());
                continue;
            }

            if (!$destination) {
                throw new ParserException('cant add directions to non initialized destination');
            }

            // get paths to destination
            if ($result->getName() == Destination::PATH_TOKEN) {
                $pathTokens = $this->getPathTokens($line);

                $destination->addPath(new Path($pathTokens));

                continue;
            }

            throw new ParserException('unexpected token name: ' . $result->getName());
        }

        try {
            $destinations->addDestination($destination);
        } catch (DestinationsException $e) {
            throw new ParserException($e->getMessage());
        }

        return $destinations;
    }

    /** create Token if string matches any rules
     * @param string $string
     * @param array $terminals
     * @return Token|null
     */
    private function match(string $string, array $terminals): ?Token
    {
        foreach ($terminals as $pattern => $name) {
            if (preg_match($pattern, $string, $matches)) {
                return new Token($name, $matches[0] ?? null);
            }
        }

        return null;
    }

    /** get tokens from string with directions
     * @param string $content
     * @return ArrayCollection
     */
    private function getPathTokens(string $content): ArrayCollection
    {
        $lines = explode(' ', $content);
        $pathTokens = new ArrayCollection();
        foreach ($lines as $line) {
            if ($result = $this->match($line, $this->pathTerminals)) {
                $pathTokens->add($result);
            }
        }

        return $pathTokens;
    }

}