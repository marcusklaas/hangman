<?php

namespace Marcusklaas\HangmanBundle\Service;

use Doctrine\ORM\EntityManager;
use Marcusklaas\HangmanBundle\Entity\Game;
use Marcusklaas\HangmanBundle\Service\WordLoader\WordLoaderInterface;

class GameManager {
    /** @var EntityManager */
    private $entityManager;

    /** @var WordLoaderInterface */
    private $wordLoader;

    /**
     * @param EntityManager       $entityManager
     * @param WordLoaderInterface $wordLoader
     */
    public function __construct(EntityManager $entityManager, WordLoaderInterface $wordLoader)
    {
        $this->entityManager = $entityManager;
        $this->wordLoader = $wordLoader;
    }

    /**
     * @return Game
     */
    public function createNew()
    {
        $word = $this->wordLoader->loadWord();
        $game = new Game($word);

        $this->entityManager->persist($game);
        $this->entityManager->flush();

        return $game;
    }
}