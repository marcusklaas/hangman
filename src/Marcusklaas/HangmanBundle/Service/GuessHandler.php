<?php

namespace Marcusklaas\HangmanBundle\Service;

use Doctrine\ORM\EntityManager;
use Marcusklaas\HangmanBundle\Entity\Game;

class GuessHandler {
    /** @var EntityManager */
    private $entityManager;

    /** @var GameStateFactory */
    private $gameStateFactory;

    /**
     * @param EntityManager    $entityManager
     * @param GameStateFactory $gameStateFactory
     */
    public function __construct(EntityManager $entityManager, GameStateFactory $gameStateFactory)
    {
        $this->entityManager = $entityManager;
        $this->gameStateFactory = $gameStateFactory;
    }

    /**
     * @param Game   $game
     * @param string $character
     *
     * @throws GuessException
     *
     * @return bool
     */
    public function guess(Game $game, $character)
    {
        $gameState = $this->gameStateFactory->getState($game);

        if ($gameState->getStatus() !== 'busy') {
            throw new GuessException('Cannot play a finished game');
        }

        if (ctype_alpha($character) !== true || strlen($character) !== 1) {
            throw new GuessException($character . ' is not an alphabetic character');
        }

        $goodGuess = $game->guess($character);

        $this->entityManager->flush($game);

        return $goodGuess;
    }
}