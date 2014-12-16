<?php

namespace Marcusklaas\HangmanBundle\Tests\Service\WordLoader;

use Marcusklaas\HangmanBundle\Service\WordLoader\FileWordLoader;
use Marcusklaas\HangmanBundle\Service\WordLoader\WordLoaderException;

class FileWordLoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testGetWord()
    {
        $testFile = realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'testwords.txt';

        $fileLoader = new FileWordLoader($testFile);

        for ($i = 0; $i < 20; $i++){
            $word = $fileLoader->loadWord();

            $this->assertTrue(in_array($word, ['turbo', 'test', 'gaaf']));
        }
    }

    public function testBadPath()
    {
        $fileLoader = new FileWordLoader('no-exist.txt');

        $this->setExpectedException(WordLoaderException::class);

        $fileLoader->loadWord();
    }
}
