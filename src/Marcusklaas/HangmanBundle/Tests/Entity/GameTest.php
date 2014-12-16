<?php

namespace Marcusklaas\HangmanBundle\Tests\Entity;

use Marcusklaas\HangmanBundle\Entity\Game;

class GameTest extends \PHPUnit_Framework_TestCase {
    public function testConstructor()
    {
        $game = new Game('testword');

        $this->assertSame('testword', $game->getWord());
        $this->assertSame('', $game->getGuessedCharacters());
    }

    public function testGoodGuess()
    {
        $game = new Game('testword');

        $this->assertTrue($game->guess('e'));
        $this->assertSame('e', $game->getGuessedCharacters());
    }

    public function testBadGuess()
    {
        $game = new Game('testword');

        $this->assertFalse($game->guess('x'));
        $this->assertSame('x', $game->getGuessedCharacters());
    }

    public function testGuessSequence()
    {
        $game = new Game('testword');

        $game->guess('A');
        $game->guess('b');
        $game->guess('d');
        $game->guess('F');

        $guessedCharacters = $game->getGuessedCharacters();

        $this->assertContains('f', $guessedCharacters);
        $this->assertContains('d', $guessedCharacters);
        $this->assertContains('b', $guessedCharacters);
        $this->assertContains('a', $guessedCharacters);
    }
}
