<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\Deck;
use App\Card\Deck2;

class CardController extends AbstractController
{
    private function setDeck(SessionInterface $session)
    {
        if (!$session->get("deck")) {
            $session->set("deck", new Deck());
        } elseif ($session->get("deck")->countDeck() == 0) {
            $session->set("deck", new Deck());
        }
    }

    /**
     * @Route("/card", name="card-home")
     */
    public function home(
        SessionInterface $session
    ): Response {
        $session->start();
        $this->setDeck($session);
        $data = [
            'title' => 'Card',
        ];
        return $this->render('card/home.html.twig', $data);
    }

    /**
     * @Route("/card/deck", name="card-deck")
     */
    public function deck(): Response
    {
        $deck = new Deck();
        $cardsImg = [];

        foreach ($deck->getDeck() as $card) {
            array_push($cardsImg, $card->getImgUrl());
        }
        $data = [
            'title' => 'Deck',
            'cards' => $cardsImg
        ];
        return $this->render('card/deck.html.twig', $data);
    }

    /**
     * @Route("/card/deck/shuffle", name="card-shuffle")
     */
    public function shuffle(
        SessionInterface $session
    ): Response {
        $session->set("deck", new Deck());
        $deck = $session->get("deck");
        $deck->shuffleDeck();
        $cardsImg = [];

        foreach ($deck->getDeck() as $card) {
            array_push($cardsImg, $card->getImgUrl());
        }
        $data = [
            'title' => 'Deck',
            'cards' => $cardsImg
        ];
        return $this->render('card/shuffle.html.twig', $data);
    }
    /**
     * @Route("/card/deck/draw", name="card-draw")
     */
    public function draw(
        SessionInterface $session
    ): Response {
        $data = [
            'title' => 'Card',
            'cards' => $session->get("deck")->drawCard(1),
            'cardsLeft' => $session->get("deck")->countDeck()
        ];
        return $this->render('card/draw.html.twig', $data);
    }

    /**
     * @Route("/card/deck/draw/{number}", name="card-number", methods={"GET","HEAD"})
     */
    public function drawNumber(
        int $number,
        SessionInterface $session
    ): Response {
        $data = [
            'title' => 'Card',
            'cards' => $session->get("deck")->drawCard($number),
            'cardsLeft' => $session->get("deck")->countDeck(),
            'cardsHand' => $session->get("number")
        ];
        return $this->render('card/draw-number.html.twig', $data);
    }

    /**
     * @Route("/card/deck/draw/{number}", name="card-number-post", methods={"POST"})
     */
    public function drawNumberPost(
        Request $request,
        SessionInterface $session,
    ): Response {
        $draw  = $request->request->get('drawit');
        $add  = $request->request->get('addit');
        $remove = $request->request->get('removeit');
        $shuffle = $request->request->get('shuffleit');

        if (!is_int($session->get("number"))) {
            $session->set("number", 0);
            return $this->redirectToRoute("card-number", ['number' => $session->get("number")]);
        }

        if ($draw) {
            return $this->redirectToRoute("card-number", ['number' => $session->get("number")]);
        } elseif ($add) {
            $session->set("number", $session->get("number") + 1);
        } elseif ($remove) {
            $session->set("number", $session->get("number") - 1);
        } elseif ($shuffle) {
            $session->set("deck", new Deck());
            $session->get("deck")->shuffleDeck();
        }
        $data = [
            'title' => 'Card',
            'cards' => [],
            'cardsLeft' => $session->get("deck")->countDeck(),
            'cardsHand' => $session->get("number")
        ];
        return $this->render('card/draw-number.html.twig', $data);
    }

    /**
     * @Route("/card/deck/deal/{players}/{cards}", name="card-deal", methods={"GET","HEAD"})
     */
    public function deal(
        int $cards,
        int $players,
        SessionInterface $session
    ): Response {
        if (!is_int($session->get("cards"))) {
            $session->set("cards", 0);
            if (!is_int($session->get("players"))) {
                $session->set("players", 0);
            }
            $sPlayers = $session->get("players");
            $sCards = $session->get("cards");
            return $this->redirectToRoute("card-deal", ["players" => $sPlayers, "cards" => $sCards]);
        }
        if (!is_int($session->get("players"))) {
            $session->set("players", 0);
            if (!is_int($session->get("cards"))) {
                $session->set("cards", 0);
            }
            $sPlayers = $session->get("players");
            $sCards = $session->get("cards");
            return $this->redirectToRoute("card-deal", ["players" => $sPlayers, "cards" => $sCards]);
        }

        $dealer = [];

        for ($i = 0; $i < $players; $i++) {
            array_push($dealer, $session->get("deck")->drawCard($cards));
        }

        $data = [
            'title'     => 'Deal',
            'cardsLeft' => $session->get("deck")->countDeck(),
            'cardsHand' => $cards,
            'players'   => $players,
            'dealer'    => $dealer
        ];
        return $this->render('card/draw-deal.html.twig', $data);
    }

    /**
     * @Route(
     *     "/card/deck/deal/{players}/{cards}",
     *     name="card-draw-post",
     *     methods={"POST"}
     * )
     */
    public function dealPost(
        Request $request,
        SessionInterface $session,
    ): Response {
        $draw  = $request->request->get('drawit');
        $add  = $request->request->get('addit');
        $remove = $request->request->get('removeit');
        $padd  = $request->request->get('paddit');
        $premove = $request->request->get('premoveit');
        $shuffle = $request->request->get('shuffleit');

        if ($draw) {
            return $this->redirectToRoute(
                "card-deal",
                ["players" => $session->get("players"), "cards" => $session->get("cards")]
            );
        } elseif ($add) {
            $session->set("cards", $session->get("cards") + 1);
        } elseif ($remove) {
            $session->set("cards", $session->get("cards") - 1);
        } elseif ($padd) {
            $session->set("players", $session->get("players") + 1);
        } elseif ($premove) {
            $session->set("players", $session->get("players") - 1);
        } elseif ($shuffle) {
            $session->set("deck", new Deck());
            $session->get("deck")->shuffleDeck();
        }

        return $this->redirectToRoute(
            "card-deal",
            ["players" => $session->get("players"), "cards" => $session->get("cards")]
        );
    }

    /**
     * @Route("/card/deck2", name="card-deck2")
     */
    public function deck2(): Response
    {
        $deck = new Deck2();
        $deck->addjoker();
        $cardsImg = [];

        foreach ($deck->getDeck() as $card) {
            array_push($cardsImg, $card->getImgUrl());
        }
        $data = [
            'title' => 'Deck 2',
            'cards' => $cardsImg
        ];
        return $this->render('card/deck.html.twig', $data);
    }
}
