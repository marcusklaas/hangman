<?php

namespace Marcusklaas\HangmanBundle\Model;

class GameState {
    /** @var string */
    private $representation;

    /** @var int */
    private $remainingTries;

    /** @var string */
    private $status;

    /**
     * @param string $representation
     * @param int    $remainingTries
     * @param string $status
     */
    public function __construct($representation, $remainingTries, $status)
    {
        $this->representation = $representation;
        $this->remainingTries = $remainingTries;
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getRepresentation()
    {
        return $this->representation;
    }

    /**
     * @return int
     */
    public function getRemainingTries()
    {
        return $this->remainingTries;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
}