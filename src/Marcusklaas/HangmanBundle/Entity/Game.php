<?php

namespace Marcusklaas\HangmanBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Marcusklaas\HangmanBundle\Repository\GameRepository")
 */
class Game
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $word;

    /**
     * @ORM\Column(type="string")
     */
    private $guessedCharacters;

    /**
     * @param string $word
     */
    public function __construct($word)
    {
        $this->word = $word;
        $this->guessedCharacters = "";
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getWord()
    {
        return $this->word;
    }

    /**
     * @return string
     */
    public function getGuessedCharacters()
    {
        return $this->guessedCharacters;
    }

    /**
     * @param string $character
     *
     * @return bool
     */
    public function guess($character)
    {
        $character = strtolower($character);
        $this->guessedCharacters .= $character;

        return strpos($this->word, $character) !== false;
    }
}
