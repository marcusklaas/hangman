<?php

namespace Marcusklaas\HangmanBundle\DataFixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Marcusklaas\HangmanBundle\Entity\Game;

class LoadGameData implements FixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $game = new Game('testabc');
        $game->guess('aeex');
        $manager->persist($game);

        $game = new Game('hallo');
        $game->guess('xyzwqrxxxxxxxxxzzzz');
        $manager->persist($game);

        $game = new Game('netbegonnen');
        $manager->persist($game);

        $manager->flush();
    }
}