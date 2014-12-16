<?php

namespace Marcusklaas\HangmanBundle\Service\WordLoader;

class FileWordLoader implements WordLoaderInterface
{
    /** @var false|string[] */
    private $lines;

    /**
     * @param string $filePath
     */
    public function __construct($filePath)
    {
        $this->lines = @file($filePath);
    }

    /**
     * {@inheritdoc}
     */
    public function loadWord()
    {
        if (false === $this->lines) {
            throw new WordLoaderException('failed reading file');
        }

        $lineCount = count($this->lines);
        $line = $this->lines[rand(1, $lineCount) - 1];
        $pieces = explode(' ', $line);

        return trim(end($pieces));
    }
}