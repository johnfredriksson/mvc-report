<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Game\Game;

class GameController extends AbstractController
{
    private function setGame($session)
    {
        $session->set("game", new Game(1000));
    }

    private function debugSession($session)
    {
        echo "<h3> PHP List All Session Variables</h3>";
        foreach ($session as $key=>$val) {
            echo json_encode($key)." ".json_encode($val)."<br/>";
        }
    }
    /**
     * @Route("/game/card", name="game-home")
     */
    public function home(): Response
    {
        $data = [
            'title' => 'GAME'
        ];

        return $this->render('game/home.html.twig', $data);
    }

    /**
     * @Route("/game/reset", name="game-reset")
     */
    public function reset(SessionInterface $session): Response
    {
        $session->clear();
        return $this->redirectToRoute("game-table");
    }

    /**
     * @Route("/game/table", name="game-table")
     **/
    public function table(
        Request $request,
        SessionInterface $session
    ): Response {
        if (!$session->get("game")) {
            $this->setGame($session);
        }

        $game = $session->get("game");

        $data = [
            "title" => "TABLE",
            "balance" => $game->getBalance(),
        ];

        return $this->render("game/table.html.twig", $data);
    }

    /**
     * @Route("/game/table/deal", name="game-table-bet", methods={"POST"})
     */
    public function gameDeal(
        Request $request,
        SessionInterface $session,
    ): Response {
        $session->set("wage", $request->request->get("wage"));

        $wage = $session->get("wage");
        $game = $session->get("game");
        $game->setDeck();
        $game->setBalance($session->get("wage"), "-");
        $game->dealCards();
        $playerSum = $game->getSum($game->getPlayerObject());

        if ($game->rules->blackjack($game->getPlayerObject())) {
            $this->addFlash("win", "BlackJack!");
            $game->setBalance($wage * 3, "+");
            return $this->redirectToRoute("game-table-end");
        }

        $data = [
            "title" => "TABLE",
            "balance" => $game->getBalance(),
            "wage" => $wage,
            "player" => $game->getCardFaces($game->getPlayer()),
            "dealer" => $game->getDealer(),
            "playerSum" => $playerSum
        ];

        return $this->render('game/deal.html.twig', $data);
    }

    /**
     * @Route("/game/table/choose", name="game-table-choose", methods={"POST"})
     */
    public function gameChoose(
        Request $request,
        SessionInterface $session,
    ): Response {
        $wage = $session->get("wage");
        $game = $session->get("game");
        $hit = $request->request->get("hit");
        $stay = $request->request->get("stay");


        if ($hit) {
            $game->drawCardPlayer();
        }

        if ($stay) {
            return $this->redirectToRoute("game-table-stay");
        }

        if ($game->rules->fat($game->getPlayerObject())) {
            $this->addFlash("lose", "You Lose. Bust");
            return $this->redirectToRoute("game-table-end");
        }

        $data = [
            "title" => "TABLE",
            "balance" => $game->getBalance(),
            "wage" => $wage,
            "player" => $game->getCardFaces($game->getPlayer()),
            "dealer" => $game->getDealer(),
            "playerSum" => $game->getSum($game->getPlayerObject())
        ];

        return $this->render('game/deal.html.twig', $data);
    }

    /**
     * @Route("/game/table/stay", name="game-table-stay")
     */
    public function gameStay(
        Request $request,
        SessionInterface $session,
    ): Response {
        $wage = $session->get("wage");
        $game = $session->get("game");

        $playerSum = $game->getSum($game->getPlayerObject());
        $dealerSum = $game->getSum($game->getDealerObject());

        while ($dealerSum[0] < 17) {
            $game->drawCardDealer();
            $dealerSum = $game->getSum($game->getDealerObject());
        }

        if ($game->rules->blackjack($game->getDealerObject())) {
            $this->addFlash("lose", "You lost. Dealer BlackJack.");
            return $this->redirectToRoute("game-table-end");
        }

        if ($game->rules->fat($game->getDealerObject())) {
            $this->addFlash("win", "You Win! Dealer Bust.");
            $game->setBalance($wage * 2, "+");
            return $this->redirectToRoute("game-table-end");
        }

        if ($dealerSum[0] > $playerSum[0]) {
            $this->addFlash("lose", "You Lose. Dealer has stronger cards.");
            return $this->redirectToRoute("game-table-end");
        }

        if ($dealerSum == $playerSum) {
            $this->addFlash("draw", "No Winner. Push.");
            $game->setBalance($wage, "+");
            return $this->redirectToRoute("game-table-end");
        }

        $this->addFlash("win", "You Win! You have stronger cards.");
        $game->setBalance($wage * 2, "+");
        return $this->redirectToRoute("game-table-end");
    }

    /**
     * @Route("/game/table/end", name="game-table-end")
     */
    public function gameEnd(
        Request $request,
        SessionInterface $session,
    ): Response {
        $wage = $session->get("wage");
        $game = $session->get("game");
        $hit = $request->request->get("hit");
        $stay = $request->request->get("stay");

        $playerSum = $game->getSum($game->getPlayerObject());

        $dealerSum = $game->getSum($game->getDealerObject());
        $dealerSum = $dealerSum[0];
        $playerSum = $playerSum[0];



        $data = [
            "title" => "TABLE",
            "balance" => $game->getBalance(),
            "wage" => $wage,
            "player" => $game->getCardFaces($game->getPlayer()),
            "dealer" => $game->getCardFaces($game->getDealer()),
            "playerSum" => $playerSum,
            "dealerSum" => $dealerSum
        ];

        return $this->render('game/end.html.twig', $data);
    }
}
