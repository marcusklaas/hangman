<?php

namespace Marcusklaas\HangmanBundle\Tests\Service;

use Marcusklaas\HangmanBundle\Entity\Game;
use Marcusklaas\HangmanBundle\Service\GameStateFactory;

class GameStateFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var int */
    private $maximumTries = 5;

    /** @var GameStateFactory */
    private $factory;

    public function setUp()
    {
        $this->factory = new GameStateFactory($this->maximumTries);
    }

    /**
     * @param Game   $game
     * @param string $expectedRepresentation
     * @param int    $badGuesses
     * @param string $expectedStatus
     *
     * @dataProvider getTestCases
     */
    public function test(Game $game, $expectedRepresentation, $badGuesses, $expectedStatus)
    {
        $gameState = $this->factory->getState($game);
        $remainingTries = max(0, $this->maximumTries - $badGuesses);

        $this->assertSame($expectedRepresentation, $gameState->getRepresentation());
        $this->assertSame($remainingTries, $gameState->getRemainingTries());
        $this->assertSame($expectedStatus, $gameState->getStatus());
    }

    /**
     * @return array[]
     */
    public function getTestCases()
    {
        return array(
            array($this->getMockGame('test', 'ta'), 't..t', 1, 'busy'),
            array($this->getMockGame('test', ''), '....', 0, 'busy'),
            array($this->getMockGame('test', 'taxxxff'), 't..t', 6, 'fail'),
            array($this->getMockGame('gaafhe', 'ehfga'), 'gaafhe', 0, 'success'),
            array($this->getMockGame('gaafhe', 'ehfxga'), 'gaafhe', 1, 'success'),
        );
    }

    /**
     * @param string $word
     * @param string $guesses
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|Game
     */
    private function getMockGame($word, $guesses)
    {
        $game = $this->getMockBuilder(Game::class)
            ->disableOriginalConstructor()
            ->getMock();

        $game->expects($this->any())
            ->method('getWord')
            ->will($this->returnValue($word));

        $game->expects($this->any())
            ->method('getGuessedCharacters')
            ->will($this->returnValue($guesses));

        return $game;
    }
}
