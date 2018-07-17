<?php

namespace App\Model;

use Doctrine\Common\Collections\ArrayCollection;

/** holds all information on 1 destination (how many people were asked and their directions)
 * Class Destination
 * @package App\DTO
 */
class Destination
{
    const ASKED_TOKEN = 'ASKED';
    const PATH_TOKEN = 'PATH';
    const ASKED_MIN = 1;
    const ASKED_MAX = 20;

    /** how many people were asked
     * @var int
     */
    private $asked;

    /** @var ArrayCollection|Path[] */
    private $paths;

    public function __construct(int $asked)
    {
        $this->asked = $asked;
        $this->paths = new ArrayCollection();
    }

    public function getAsked(): int
    {
        return $this->asked;
    }

    /**
     * @return Path[]|ArrayCollection
     */
    public function getPaths()
    {
        return $this->paths;
    }

    public function setPaths(ArrayCollection $tokens)
    {
        $this->paths = new PathDirections($tokens);
    }

    public function addPath(Path $path): void
    {
        $this->paths->add($path);
    }

    public function isValid()
    {
        if ($this->asked < self::ASKED_MIN || self::ASKED_MAX < $this->asked) {
            return false;
        }

        if ($this->asked !== $this->getPaths()->count()) {
            return false;
        }

        return true;
    }

}