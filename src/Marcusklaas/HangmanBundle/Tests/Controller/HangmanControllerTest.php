<?php

namespace Marcusklaas\HangmanBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Marcusklaas\HangmanBundle\DataFixtures\LoadGameData;
use Symfony\Bundle\FrameworkBundle\Client;

class HangmanControllerTest extends WebTestCase
{
    /** @var Client */
    private $client;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->client->followRedirects();

        $this->loadFixtures(array(
            LoadGameData::class
        ));
    }

    public function testIndex()
    {
        $idList = $this->fetchJson('GET', '/games');

        $this->assertGreaterThanOrEqual(3, count($idList));

        // make sure all ids are unique
        $this->assertSame(count(array_unique($idList)), count($idList));
    }

    public function testNewGame()
    {
        $gameList = $this->fetchJson('GET', '/games');

        $this->fetchJson('POST', '/games');

        $newGameList = $this->fetchJson('GET', '/games');

        $this->assertSame(count($gameList) + 1, count($newGameList));

        $diff = array_diff($newGameList, $gameList);
        $newId = reset($diff);
        $details = $this->fetchJson('GET', '/games/' . $newId);

        $this->assertArrayHasKey('representation', $details);
        $this->assertArrayHasKey('remaining_tries', $details);
        $this->assertArrayHasKey('status', $details);

        $this->assertSame(11, $details['remaining_tries']);
        $this->assertSame('busy', $details['status']);
    }

    public function testGoodGuesses()
    {
        $this->fetchJson('POST', '/games/3', array('char' => 'e'));

        $details = $this->fetchJson('GET', '/games/3');

        $this->assertSame('.e..e....e.', $details['representation']);
        $this->assertSame(11, $details['remaining_tries']);
        $this->assertSame('busy', $details['status']);

        $this->fetchJson('POST', '/games/3', array('char' => 'n'));
        $this->fetchJson('POST', '/games/3', array('char' => 't'));
        $this->fetchJson('POST', '/games/3', array('char' => 'b'));
        $this->fetchJson('POST', '/games/3', array('char' => 'g'));
        $this->fetchJson('POST', '/games/3', array('char' => 'o'));

        $finalDetails = $this->fetchJson('GET', '/games/3');

        $this->assertSame('netbegonnen', $finalDetails['representation']);
        $this->assertSame(11, $finalDetails['remaining_tries']);
        $this->assertSame('success', $finalDetails['status']);
    }

    public function testFailedWord()
    {
        $details = $this->fetchJson('GET', '/games/2');

        $this->assertSame(0, $details['remaining_tries']);
        $this->assertSame('fail', $details['status']);
    }

    public function testBadGuess()
    {
        $details = $this->fetchJson('GET', '/games/1');

        $this->fetchJson('POST', '/games/1', array('char' => 'X'));

        $newDetails = $this->fetchJson('GET', '/games/1');

        $this->assertSame($details['representation'], $newDetails['representation']);
        $this->assertSame($details['remaining_tries'], 1 + $newDetails['remaining_tries']);
    }

    public function testDetailsNonExistentGame()
    {
        $result = $this->fetchJson('GET', '/games/5000');

        $this->assertArrayHasKey('code', $result);
        $this->assertSame(404, $result['code']);
    }

    public function testInvalidGuess()
    {
        $result = $this->fetchJson('POST', '/games/1', array('char' => '*'));

        $this->assertArrayHasKey('code', $result);
        $this->assertSame(400, $result['code']);

        $this->assertArrayHasKey('message', $result);
        $this->assertSame('* is not an alphabetic character', $result['message']);
    }

    public function testGuessOnFinishedGame()
    {
        $result = $this->fetchJson('POST', '/games/2', array('char' => 'e'));

        $this->assertArrayHasKey('code', $result);
        $this->assertSame(400, $result['code']);

        $this->assertArrayHasKey('message', $result);
        $this->assertSame('Cannot play a finished game', $result['message']);
    }

    /**
     * @param string $method
     * @param string $path
     * @param array  $data
     *
     * @return mixed
     */
    private function fetchJson($method, $path, $data = array())
    {
        $this->client->request($method, $path, $data);

        $response = $this->client->getResponse();
        $content = $response->getContent();

        return json_decode($content, true);
    }
}
