<?php

namespace Marcusklaas\HangmanBundle\Tests\Service;


use Doctrine\ORM\EntityManager;
use Marcusklaas\HangmanBundle\Entity\Game;
use Marcusklaas\HangmanBundle\Service\GameManager;
use Marcusklaas\HangmanBundle\Service\WordLoader\WordLoaderInterface;

class GameManagerTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $word = 'testwoord';
        $entityManager = $this->getMockEntityManager();
        $wordLoader = $this->getMockWordLoader();
        $gameManager = new GameManager($entityManager, $wordLoader);

        $entityManager->expects($this->at(0))
            ->method('persist')
            ->with($this->callback(function (Game $game) use ($word) {
                return $game->getWord() === $word;
            }));

        $entityManager->expects($this->at(1))
            ->method('flush');

        $wordLoader->expects($this->any())
            ->method('loadWord')
            ->will($this->returnValue($word));

        $gameManager->createNew();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|WordLoaderInterface
     */
    private function getMockWordLoader()
    {
        return $this->getMock(WordLoaderInterface::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|EntityManager
     */
    private function getMockEntityManager()
    {
        return $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
