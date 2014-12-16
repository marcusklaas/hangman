<?php

namespace Marcusklaas\HangmanBundle\Service;

use Marcusklaas\HangmanBundle\Entity\Game;
use Marcusklaas\HangmanBundle\Model\GameState;

class GameStateFactory {
    /** @var int */
    private $maximumTries;

    /**
     * @param int $maximumTries
     */
    public function __construct($maximumTries)
    {
        $this->maximumTries = $maximumTries;
    }

    /**
     * @param Game $game
     *
     * @return GameState
     */
    public function getState(Game $game)
    {
        $representation = $this->getRepresentation($game);
        $remainingTries = $this->countRemainingTries($game);
        $status = $this->getStatus($representation, $remainingTries);

        return new GameState($representation, $remainingTries, $status);
    }

    /**
     * @param Game $game
     *
     * @return string
     */
    private function getRepresentation(Game $game)
    {
        $pattern = '/[^0' . $game->getGuessedCharacters() . ']/';

        return preg_replace($pattern, '.', $game->getWord());
    }

    /**
     * @param Game $game
     *
     * @return int
     */
    private function countRemainingTries(Game $game)
    {
        $pattern = '/[' . $game->getWord() . ']/';
        $wrongCharacters = preg_replace($pattern, '', $game->getGuessedCharacters());

        return max(0, $this->maximumTries - strlen($wrongCharacters));
    }

    /**
     * @param string $representation
     * @param int    $remainingTries
     *
     * @return string
     */
    private function getStatus($representation, $remainingTries)
    {
        if (strpos($representation, '.') === false) {
            return 'success';
        }

        if ($remainingTries <= 0) {
            return 'fail';
        }

        return 'busy';
    }
}