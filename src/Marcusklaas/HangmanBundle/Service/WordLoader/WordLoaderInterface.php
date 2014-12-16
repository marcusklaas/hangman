<?php

namespace Marcusklaas\HangmanBundle\Service\WordLoader;

interface WordLoaderInterface {
    /**
     * @return string
     *
     * @throws WordLoaderException
     */
    public function loadWord();
}