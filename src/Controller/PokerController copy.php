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

// $this->addFlash("label", "");


class PokerController extends AbstractController
{
    // /**
    //  * @Route("/proj/poker/banklogic", name="poker-banklogic")
    //  */
    // public function bankMakeChoice(
    //     SessionInterface $session
    // ): Response
    // {
    //     $blind = $session->get("pokerBlind");
    //     $session->set("answer", false);
    //     $bankDecision = $session->get("bankLogic")->bet();
        
    //     if ($bankDecision == "check") {
    //         $this->addFlash("label", "Bank checks.");
    //         $session->get("pokerGame")->addToPot($session->get("callAmount"));
    //         $session->set("callAmount", 0);
    //         return $this->redirectToRoute("poker-preflop");
    //     }
    //     if ($bankDecision == "fold") {
    //         $this->addFlash("label", "Bank folds. You win!");
    //         return $this->redirectToRoute("poker-end");
    //     }
    //     if ($bankDecision == "raise") {
    //         $playerBalance = $session->get("user")->getBalance();
    //         $raise = $session->get("bankLogic")->raise($blind, $playerBalance);
    //         $this->addFlash("label", "Bank raises " . $raise . " sek.");
    //         $session->set("answer", true);
    //         $session->set("callAmount", $raise);
    //         $session->get("pokerGame")->addToPot($raise + $blind);

    //         return $this->redirectToRoute("poker-blind");
    //     }
    // }


    public function bankMakeChoiceNoRoute(
        SessionInterface $session
    )
    {
        $blind = $session->get("pokerBlind");
        $session->set("answer", false);
        $bankDecision = $session->get("bankLogic")->bet();
        
        if ($bankDecision == "check") {
            $this->addFlash("label", "Bank checks.");
            $session->get("pokerGame")->addToPot($session->get("callAmount"));
            $session->set("callAmount", 0);
            $session->set("bankChoice", "check");
            return;
        }
        if ($bankDecision == "fold") {
            $this->addFlash("label", "Bank folds. You win!");
            $session->set("bankChoice", "fold");
            return;
        }
        if ($bankDecision == "raise") {
            $session->set("bankChoice", "check");

            $playerBalance = $session->get("user")->getBalance();
            $raise = $session->get("bankLogic")->raise($blind, $playerBalance);

            $this->addFlash("label", "Bank raises " . $raise . " sek.");

            $session->set("answer", true);
            if (!$session->get("blindPaid")) {
                $session->get("pokerGame")->addToPot($raise + $blind);
                $session->set("blindPaid", true);
                $session->set("callAmount", $raise);
                return;
            }
            $session->get("pokerGame")->addToPot($raise + $session->get("callAmount"));
            $session->set("callAmount", $raise);
            return;
        }
    }



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
        $session->set("blindPaid", false);

        if ($request->request->get("stake")) {
            $session->set("pokerBlind", $request->request->get("stake"));
        }
        
        $blind = $session->get("pokerBlind");
        
        $session->set("pokerGame", new Poker());

        if ($session->get("blindTurn") == "bank") {
            $session->get("pokerGame")->addToPot($blind);
            $session->set("blindPaid", true);
            return $this->redirectToRoute("poker-blind");
        }
        $session->get("pokerGame")->addToPot($blind);
        $session->set("callAmount", $blind);

        $res = $this->bankMakeChoiceNoRoute($session);
        $routes = [
            "check" => "poker-preflopmiddle",
            "fold" => "poker-end",
            "raise" => "poker-blind"
        ];
        return $this->redirectToRoute($routes[$session->get("bankChoice")]);
    }

    /**
     * @Route("proj/poker/blind", name="poker-blind", methods={"GET"})
     */
    public function pokerBlind(
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
            "answer" => $session->get("answer"),
            "callAmount" => $session->get("callAmount")
        ];

        return $this->render("poker/blind.html.twig", $data);
    }

    /**
     * @Route("proj/poker/blind", name="poker-blind-process", methods={"POST"})
     */
    public function pokerBlindProcess(
        SessionInterface $session,
        Request $request
    ): Response
    {
        $session->set("answer", false);

        if ($request->request->get("check")) {
            return $this->redirectToRoute("poker-preflop");
        }
        if ($request->request->get("call")) {
            // dra pengar
            $session->get("pokerGame")->addToPot($session->get("callAmount"));
            return $this->redirectToRoute("poker-preflop");
        }
        if ($request->request->get("fold")) {
            $this->addFlash("label", "Hand folded, You lost.");
            return $this->redirectToRoute("poker-end");
        }
        
        $raise = $request->request->get("wage");
        $callAmount = $session->get("callAmount");
        // dra pengar
        $session->get("pokerGame")->addToPot($raise);
        $session->set("callAmount", $raise - $callAmount);
        $res = $this->bankMakeChoiceNoRoute($session);
        $routes = [
            "check" => "poker-preflop",
            "fold" => "poker-end",
            "raise" => "poker-blind"
        ];
        if ($res == "check" && $session->get("blindTurn") == "you") {
            return $this->redirectToRoute("poker-preflop-process", [], 307);
        }
        return $this->redirectToRoute($routes[$session->get("bankChoice")]);
    }

    /**
     * @Route("proj/poker/preflopmiddle", name="poker-preflopmiddle")
     */
    public function pokerPreflopmiddle(
        SessionInterface $session
    ): Response
    {
        if ($session->get("blindTurn") == "you") {
            $this->bankMakeChoiceNoRoute($session);
        }
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
        if ($session->get("blindTurn") == "you") {
            $this->bankMakeChoiceNoRoute($session);
        }
        $data = [
            "loggedInStatus" => $session->get("loggedInStatus"),
            "user" => $session->get("user"),
            "blind" => $session->get("pokerBlind"),
            "blindTurn" => $session->get("blindTurn"),
            "pot" => $session->get("pokerGame")->getPot(),
            "player" => $session->get("pokerGame")->getPlayer(),
            "community" => $session->get("pokerGame")->getCommunity(),
            "answer" => $session->get("answer"),
            "callAmount" => $session->get("callAmount")
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
        $session->set("answer", false);

        if ($request->request->get("check")) {
            return $this->redirectToRoute("poker-flop");
        }
        if ($request->request->get("call")) {
            // dra pengar
            $session->get("pokerGame")->addToPot($session->get("callAmount"));
            return $this->redirectToRoute("poker-flop");
        }
        if ($request->request->get("fold")) {
            $this->addFlash("label", "Hand folded, You lost.");
            return $this->redirectToRoute("poker-end");
        }
        
        $raise = $request->request->get("wage");
        $callAmount = $session->get("callAmount");
        // dra pengar
        $session->get("pokerGame")->addToPot($raise);
        $session->set("callAmount", $raise - $callAmount);
        $res = $this->bankMakeChoiceNoRoute($session);
        $routes = [
            "check" => "poker-flopmiddle",
            "fold" => "poker-end",
            "raise" => "poker-preflop"
        ];
        if ($req == "check" && $session->get("blindTurn") == "you") {
            return $this->redirectToRoute("poker-flop-process");
        }
        return $this->redirectToRoute($routes[$res]);
    }

    /**
     * @Route("proj/poker/flopmiddle", name="poker-flopmiddle")
     */
    public function pokerFlopmiddle(
        SessionInterface $session
    ): Response
    {
        $session->get("pokerGame")->flop();
        if ($session->get("blindTurn") == "you") {
            return $this->redirectToRoute("poker-flop-process", [], 307);
        }
        return $this->redirectToRoute("poker-flop");
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
            "community" => $session->get("pokerGame")->getCommunity(),
            "answer" => $session->get("answer"),
            "callAmount" => $session->get("callAmount")
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
     * @Route("proj/poker/turnmiddle", name="poker-turnmiddle")
     */
    public function pokerTurnmiddle(
        SessionInterface $session
    ): Response
    {
        if ($session->get("blindTurn") == "you") {
            $this->bankMakeChoiceNoRoute($session);
        }
        return $this->redirectToRoute("poker-turn");
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
            "community" => $session->get("pokerGame")->getCommunity(),
            "answer" => $session->get("answer"),
            "callAmount" => $session->get("callAmount")
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
     * @Route("proj/poker/rivermiddle", name="poker-rivermiddle")
     */
    public function pokerRivermiddle(
        SessionInterface $session
    ): Response
    {
        if ($session->get("blindTurn") == "you") {
            $this->bankMakeChoiceNoRoute($session);
        }
        return $this->redirectToRoute("poker-river");
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
            "community" => $session->get("pokerGame")->getCommunity(),
            "answer" => $session->get("answer"),
            "callAmount" => $session->get("callAmount")
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
            "answer" => $session->get("answer"),
            "callAmount" => $session->get("callAmount")
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
        if ($session->get("blindTurn") == "bank") {
            $session->set("blindTurn", "you");
        } else {
            $session->set("blindTurn", "bank");
        }
        if ($request->request->get("changeBlind")) {
            return $this->redirectToRoute("poker-index");
        }
        return $this->redirectToRoute("poker-index-process", [], 307);
    }   
}