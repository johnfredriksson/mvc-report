<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Poker\Poker;
use App\Poker\BankLogic;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Users;
use App\Repository\UsersRepository;

class PokerController extends AbstractController
{
    /**
     * @Route("/proj/poker/index", name="poker-index", methods={"GET"})
     */
    public function pokerIndex(
        SessionInterface $session
    ): Response
    {
        if (!$session->get("loggedInStatus")) {
            return $this->redirectToRoute("casino-login");
        }

        $session->set("bankLogic", new BankLogic());
        $session->set("blindTurn", "you");

        $data = [
            "loggedInStatus" => $session->get("loggedInStatus"),
            "user" => $session->get("user")
        ];

        return $this->render("poker/index.html.twig", $data);
    }

    /**
     * @Route("proj/poker/index", name="poker-index-process", methods={"POST"})
     */
    public function pokerIndexProcess(
        SessionInterface $session,
        ManagerRegistry $doctrine,
        Request $request
    ): Response
    {
        if ($request->request->get("stake")) {
            $session->set("pokerBlind", $request->request->get("stake"));
        }

        if ($session->get("blindTurn") == "bank") {
            $session->set("blindTurn", "you");
        } elseif ($session->get("blindTurn") == "you") {
            $session->set("blindTurn", "bank");
        }
        
        $this->addFlash("lose", "You Lose. Dealer has stronger cards.");

        $session->set("pokerGame", new Poker());

        return $this->redirectToRoute("poker-blind",[], 307);
    }

    /**
     * @Route("proj/poker/blind", name="poker-blind", methods={"POST"})
     */
    public function pokerBlind(
        SessionInterface $session
    ): Response
    {
        $data = [
            "loggedInStatus" => $session->get("loggedInStatus"),
            "user" => $session->get("user"),
            "blind" => $session->get("pokerBlind"),
            "blindTurn" => $session->get("blindTurn"),
            "pot" => $session->get("pokerGame")->getPot(),
            "answer" => $session->get("blindTurn") == "bank" && $session->get("bankLogic")->bet() == "raise",
            "callAmount" => 1300
        ];

        return $this->render("poker/blind.html.twig", $data);
    }

    /**
     * @Route("proj/poker/blindcheck", name="poker-blind-check", methods={"POST"})
     */
    public function pokerBlindcheck(): Response
    {
        return $this->redirectToRoute("poker-preflop");
    }

    /**
     * @Route("proj/poker/preflop", name="poker-preflop", methods={"GET"})
     */
    public function pokerPreflop(
        SessionInterface $session,
        Request $request
    ): Response
    {
        $data = [
            "loggedInStatus" => $session->get("loggedInStatus"),
            "user" => $session->get("user"),
            "blind" => $session->get("pokerBlind"),
            "blindTurn" => $session->get("blindTurn"),
            "pot" => $session->get("pokerGame")->getPot(),
            "player" => $session->get("pokerGame")->getPlayer(),
            "community" => []
            // "answer" => true,
            // "callAmount" => 1300
        ];

        return $this->render("poker/preflop.html.twig", $data);
    }

    /**
     * @Route("proj/poker/preflop", name="poker-preflop-process", methods={"POST"})
     */
    public function pokerPreflopProcess(
        SessionInterface $session,
        Request $request
    ): Response
    {
        $session->get("pokerGame")->flop();
        if ($request->request->get("check")) {
            return $this->redirectToRoute("poker-flop");
        }
    }

    /**
     * @Route("proj/poker/flop", name="poker-flop", methods={"GET"})
     */
    public function pokerFlop(
        SessionInterface $session,
        Request $request
    ): Response
    {
        
        $data = [
            "loggedInStatus" => $session->get("loggedInStatus"),
            "user" => $session->get("user"),
            "blind" => $session->get("pokerBlind"),
            "blindTurn" => $session->get("blindTurn"),
            "pot" => $session->get("pokerGame")->getPot(),
            "player" => $session->get("pokerGame")->getPlayer(),
            "community" => $session->get("pokerGame")->getCommunity()
            // "answer" => true,
            // "callAmount" => 1300
        ];

        return $this->render("poker/preflop.html.twig", $data);
    }

    /**
     * @Route("proj/poker/flop", name="poker-flop-process", methods={"POST"})
     */
    public function pokerFlopProcess(
        SessionInterface $session,
        Request $request
    ): Response
    {
        $session->get("pokerGame")->turn();
        if ($request->request->get("check")) {
            return $this->redirectToRoute("poker-turn");
        }
    }

    /**
     * @Route("proj/poker/turn", name="poker-turn", methods={"GET"})
     */
    public function pokerTurn(
        SessionInterface $session,
        Request $request
    ): Response
    {
        
        $data = [
            "loggedInStatus" => $session->get("loggedInStatus"),
            "user" => $session->get("user"),
            "blind" => $session->get("pokerBlind"),
            "blindTurn" => $session->get("blindTurn"),
            "pot" => $session->get("pokerGame")->getPot(),
            "player" => $session->get("pokerGame")->getPlayer(),
            "community" => $session->get("pokerGame")->getCommunity()
            // "answer" => true,
            // "callAmount" => 1300
        ];

        return $this->render("poker/preflop.html.twig", $data);
    }

    /**
     * @Route("proj/poker/turn", name="poker-turn-process", methods={"POST"})
     */
    public function pokerTurnProcess(
        SessionInterface $session,
        Request $request
    ): Response
    {
        $session->get("pokerGame")->river();
        if ($request->request->get("check")) {
            return $this->redirectToRoute("poker-river");
        }
    }

    /**
     * @Route("proj/poker/river", name="poker-river", methods={"GET"})
     */
    public function pokerRiver(
        SessionInterface $session
    ): Response
    {
        
        $data = [
            "loggedInStatus" => $session->get("loggedInStatus"),
            "user" => $session->get("user"),
            "blind" => $session->get("pokerBlind"),
            "blindTurn" => $session->get("blindTurn"),
            "pot" => $session->get("pokerGame")->getPot(),
            "player" => $session->get("pokerGame")->getPlayer(),
            "community" => $session->get("pokerGame")->getCommunity()
            // "answer" => true,
            // "callAmount" => 1300
        ];

        return $this->render("poker/preflop.html.twig", $data);
    }

     /**
     * @Route("proj/poker/river", name="poker-river-process", methods={"POST"})
     */
    public function pokerRiverProcess(
        SessionInterface $session,
        Request $request
    ): Response
    {
        if ($request->request->get("check")) {
            return $this->redirectToRoute("poker-end");
        }
    }

    /**
     * @Route("proj/poker/end", name="poker-end", methods={"GET"})
     */
    public function pokerEnd(
        SessionInterface $session
    ): Response
    {
        
        $data = [
            "loggedInStatus" => $session->get("loggedInStatus"),
            "user" => $session->get("user"),
            "blind" => $session->get("pokerBlind"),
            "blindTurn" => $session->get("blindTurn"),
            "pot" => $session->get("pokerGame")->getPot(),
            "player" => $session->get("pokerGame")->getPlayer(),
            "community" => $session->get("pokerGame")->getCommunity(),
            "bank" => $session->get("pokerGame")->getBank(),
            // "answer" => true,
            // "callAmount" => 1300
        ];

        return $this->render("poker/end.html.twig", $data);
    }

    /**
     * @Route("proj/poker/end", name="poker-end-process", methods={"POST"})
     */
    public function pokerEndProcess(
        SessionInterface $session,
        Request $request
    ): Response
    {
        if ($request->request->get("changeBlind")) {
            return $this->redirectToRoute("poker-index");
        }
        return $this->redirectToRoute("poker-index-process", [], 307);
    }   
}