<?php

namespace Marcusklaas\HangmanBundle\Tests\Service;

use Doctrine\ORM\EntityManager;
use Marcusklaas\HangmanBundle\Entity\Game;
use Marcusklaas\HangmanBundle\Model\GameState;
use Marcusklaas\HangmanBundle\Service\GameStateFactory;
use Marcusklaas\HangmanBundle\Service\GuessException;
use Marcusklaas\HangmanBundle\Service\GuessHandler;

class GuessHandlerTest extends \PHPUnit_Framework_TestCase
{
    /** @var GuessHandler */
    private $guessHandler;

    /** @var \PHPUnit_Framework_MockObject_MockObject|GameStateFactory */
    private $gameStateFactory;

    /** @var \PHPUnit_Framework_MockObject_MockObject|EntityManager */
    private $entityManager;

    public function setUp()
    {
        $this->gameStateFactory = $this->getMockGameStateFactory();
        $this->entityManager = $this->getMockEntityManager();
        $this->guessHandler = new GuessHandler($this->entityManager, $this->gameStateFactory);
    }

    /**
     * @param string $character
     * @param bool   $isGoodGuess
     *
     * @dataProvider getProperGuesses
     */
    public function testProperGuess($character, $isGoodGuess)
    {
        $game = $this->getMockGame();

        $game->expects($this->once())
            ->method('guess')
            ->with($character)
            ->will($this->returnValue($isGoodGuess));

        $this->gameStateFactory->expects($this->any())
            ->method('getState')
            ->with($game)
            ->will($this->returnValue($this->getMockGameState('busy')));

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->assertSame($isGoodGuess, $this->guessHandler->guess($game, $character));
    }

    /**
     * @return array[]
     */
    public function getProperGuesses()
    {
        return array(
            array('a', true),
            array('x', true),
            array('B', true),
            array('d', false),
        );
    }

    /**
     * @param string $status
     *
     * @dataProvider getStatusList
     */
    public function testGuessOnFinishedGame($status)
    {
        $game = $this->getMockGame();

        $this->gameStateFactory->expects($this->any())
            ->method('getState')
            ->with($game)
            ->will($this->returnValue($this->getMockGameState($status)));

        $this->entityManager->expects($this->never())
            ->method('flush');

        $this->setExpectedException(GuessException::class, 'Cannot play a finished game');

        $this->guessHandler->guess($game, 'X');
    }

    /**
     * @return array[]
     */
    public function getStatusList()
    {
        return array(
            array('success'),
            array('fail'),
        );
    }

    /**
     * @param string $guess
     *
     * @dataProvider getBadGuessList
     */
    public function testBadGuess($guess)
    {
        $game = $this->getMockGame();

        $this->gameStateFactory->expects($this->any())
            ->method('getState')
            ->with($game)
            ->will($this->returnValue($this->getMockGameState('busy')));

        $this->entityManager->expects($this->never())
            ->method('flush');

        $this->setExpectedException(GuessException::class, $guess . ' is not an alphabetic character');

        $this->guessHandler->guess($game, $guess);
    }

    /**
     * @return array[]
     */
    public function getBadGuessList()
    {
        return array(
            array('0'),
            array('*'),
            array('aa'),
            array('aaxxxx&& '),
            array('❤')
        );
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|GameStateFactory
     */
    private function getMockGameStateFactory()
    {
        return $this->getMockBuilder(GameStateFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
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

    /**\
     * @param string $status
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|GameState
     */
    private function getMockGameState($status)
    {
        $gameState = $this->getMockBuilder(GameState::class)
            ->disableOriginalConstructor()
            ->getMock();

        $gameState->expects($this->any())
            ->method('getStatus')
            ->will($this->returnValue($status));

        return $gameState;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Game
     */
    private function getMockGame()
    {
        return $this->getMockBuilder(Game::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
