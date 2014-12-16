<?php

namespace Marcusklaas\HangmanBundle\Tests\Model;


use Marcusklaas\HangmanBundle\Model\GameState;

class GameStateTest extends \PHPUnit_Framework_TestCase {
    public function testConstructor()
    {
        $gameState = new GameState('c..li.', 5, 'busy');

        $this->assertSame('c..li.', $gameState->getRepresentation());
        $this->assertSame(5, $gameState->getRemainingTries());
        $this->assertSame('busy', $gameState->getStatus());
    }
}
