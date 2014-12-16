<?php

namespace Marcusklaas\HangmanBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Marcusklaas\HangmanBundle\Entity\Game;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;

class HangmanController extends FOSRestController
{
    /**
     * @return View
     */
    public function indexAction()
    {
        $gameRepository = $this->get('hangman.repository.game');
        $idList = $gameRepository->getIdList();

        return $this->okay($idList);
    }

    /**
     * @return View
     */
    public function newGameAction()
    {
        $gameManager = $this->get('hangman.game_manager');
        $game = $gameManager->createNew();

        return $this->okay($game->getId());
    }

    /**
     * @param Game $game
     *
     * @return View
     */
    public function detailsAction(Game $game)
    {
        $gameStateFactory = $this->get('hangman.game_state_factory');
        $gameState = $gameStateFactory->getState($game);

        return $this->okay($gameState);
    }

    /**
     * @param Request $request
     * @param Game $game
     *
     * @return View
     */
    public function guessAction(Request $request, Game $game)
    {
        $guessHandler = $this->get('hangman.guess_handler');
        $character = $request->get('char');
        $goodGuess = $guessHandler->guess($game, $character);

        return $this->okay($goodGuess);
    }

    /**
     * @param mixed $result
     *
     * @return View
     */
    private function okay($result)
    {
        return View::create($result, Response::HTTP_OK);
    }
}
