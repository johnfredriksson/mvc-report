<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Poker\Poker;
use App\Poker\BankLogic;
use App\Poker\Rules;
use App\Poker\Compare;
use App\Entity\Users;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\UsersRepository;

// $this->addFlash("label", "");


class PokerController extends AbstractController
{
    public function bankMakeChoice(
        SessionInterface $session
    )
    {
        $blind = $session->get("pokerBlind");
        $session->set("answer", false); //
        $bankDecision = $session->get("bankLogic")->bet();

        if ($bankDecision == "check") {
            $session->get("pokerGame")->addToPot($session->get("callAmount"));
            $session->set("callAmount", 0);
            if ($session->get("callAmount")) {
                $this->addFlash("label", "Bank calls.");
                return "call";
            }
            $this->addFlash("label", "Bank checks.");
            return "check";
        }
        if ($bankDecision == "fold") {
            $this->addFlash("label", "Bank folds. You win!");
            return "fold";
        }
        if ($bankDecision == "raise") {

            $playerBalance = $session->get("user")->getBalance();
            $raise = $session->get("bankLogic")->raise($blind, $playerBalance);

            $this->addFlash("label", "Bank raises " . $raise . " sek.");

            $session->set("answer", true);

            $session->get("pokerGame")->addToPot($raise + $session->get("callAmount"));
            $session->set("callAmount", $raise);
            return "raise";
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
        if ($request->request->get("stake")) {
            $session->set("pokerBlind", $request->request->get("stake"));
        }
        
        $blind = $session->get("pokerBlind");
        
        $session->set("pokerGame", new Poker());
        $session->set("callAmount", 0);

        // Dra spelares pengar
        $session->get("pokerGame")->addToPot($blind * 2);
        
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
            $choice = $this->bankMakeChoice($session);
            if ($choice == "call") {
                $session->get("pokerGame")->flop();
                return $this->redirectToRoute("poker-flop");
            }
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

        if ($request->request->get("check") && $session->get("blindTurn") == "you") {
            $session->get("pokerGame")->flop();
            return $this->redirectToRoute("poker-flop");
        }
        if ($request->request->get("check")) {
            $choice = $this->bankMakeChoice($session);
            if ($choice == "check") {
                $session->get("pokerGame")->flop();
                return $this->redirectToRoute("poker-flop");
            }
            return $this->redirectToRoute("poker-preflop");
        }
        if ($request->request->get("call")) {
            // dra pengar
            $session->get("pokerGame")->flop();
            $session->get("pokerGame")->addToPot($session->get("callAmount"));
            $session->set("callAmount", 0);
            $session->set("answer", false);
            return $this->redirectToRoute("poker-flop");
        }
        if ($request->request->get("fold")) {
            $this->addFlash("label", "Hand folded, You lost.");
            return $this->redirectToRoute("poker-end");
        }
        // Raise case //
        $raise = $request->request->get("wage");
        $callAmount = $session->get("callAmount");
        $session->get("pokerGame")->addToPot($raise);

        // dra pengar
        $session->set("callAmount", $raise - $callAmount);
        if ($session->get("blindTurn") == "you") {
            return $this->redirectToRoute("poker-preflop");
        }

        $choice = $this->bankMakeChoice($session);
        if ($choice == "call" || $choice == "check") {
            $session->get("pokerGame")->flop();
            return $this->redirectToRoute("poker-flop");
        }
        return $this->redirectToRoute("poker-preflop");
    }


    /**
     * @Route("proj/poker/flop", name="poker-flop", methods={"GET"})
     */
    public function pokerFlop(
        SessionInterface $session,
        Request $request
    ): Response
    {
        if ($session->get("blindTurn") == "you") {
            $choice = $this->bankMakeChoice($session);
            if ($choice == "call") {
                $session->get("pokerGame")->turn();
                return $this->redirectToRoute("poker-turn");
            }
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
     * @Route("proj/poker/flop", name="poker-flop-process", methods={"POST"})
     */
    public function pokerFlopProcess(
        SessionInterface $session,
        Request $request
    ): Response
    {
        $session->set("answer", false);

        if ($request->request->get("check") && $session->get("blindTurn") == "you") {
            $session->get("pokerGame")->turn();
            return $this->redirectToRoute("poker-turn");
        }
        if ($request->request->get("check")) {
            $choice = $this->bankMakeChoice($session);
            if ($choice == "check") {
                $session->get("pokerGame")->turn();
                return $this->redirectToRoute("poker-turn");
            }
            return $this->redirectToRoute("poker-flop");
        }
        if ($request->request->get("call")) {
            // dra pengar
            $session->get("pokerGame")->turn();
            $session->get("pokerGame")->addToPot($session->get("callAmount"));
            $session->set("callAmount", 0);
            $session->set("answer", false);
            return $this->redirectToRoute("poker-turn");
        }
        if ($request->request->get("fold")) {
            $this->addFlash("label", "Hand folded, You lost.");
            return $this->redirectToRoute("poker-end");
        }
        // Raise case //
        $raise = $request->request->get("wage");
        $callAmount = $session->get("callAmount");
        $session->get("pokerGame")->addToPot($raise);

        // dra pengar
        $session->set("callAmount", $raise - $callAmount);
        if ($session->get("blindTurn") == "you") {
            return $this->redirectToRoute("poker-flop");
        }

        $choice = $this->bankMakeChoice($session);
        if ($choice == "call" || $choice == "check") {
            $session->get("pokerGame")->turn();
            return $this->redirectToRoute("poker-turn");
        }
        return $this->redirectToRoute("poker-flop");
    }


    /**
     * @Route("proj/poker/turn", name="poker-turn", methods={"GET"})
     */
    public function pokerTurn(
        SessionInterface $session,
        Request $request
    ): Response
    {
        if ($session->get("blindTurn") == "you") {
            $choice = $this->bankMakeChoice($session);
            if ($choice == "call") {
                $session->get("pokerGame")->river();
                return $this->redirectToRoute("poker-river");
            }
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
     * @Route("proj/poker/turn", name="poker-turn-process", methods={"POST"})
     */
    public function pokerTurnProcess(
        SessionInterface $session,
        Request $request
    ): Response
    {
        $session->set("answer", false);

        if ($request->request->get("check") && $session->get("blindTurn") == "you") {
            $session->get("pokerGame")->river();
            return $this->redirectToRoute("poker-river");
        }
        if ($request->request->get("check")) {
            $choice = $this->bankMakeChoice($session);
            if ($choice == "check") {
                $session->get("pokerGame")->river();
                return $this->redirectToRoute("poker-river");
            }
            return $this->redirectToRoute("poker-turn");
        }
        if ($request->request->get("call")) {
            // dra pengar
            $session->get("pokerGame")->river();
            $session->get("pokerGame")->addToPot($session->get("callAmount"));
            $session->set("callAmount", 0);
            $session->set("answer", false);
            return $this->redirectToRoute("poker-river");
        }
        if ($request->request->get("fold")) {
            $this->addFlash("label", "Hand folded, You lost.");
            return $this->redirectToRoute("poker-end");
        }
        // Raise case //
        $raise = $request->request->get("wage");
        $callAmount = $session->get("callAmount");
        $session->get("pokerGame")->addToPot($raise);

        // dra pengar
        $session->set("callAmount", $raise - $callAmount);
        if ($session->get("blindTurn") == "you") {
            return $this->redirectToRoute("poker-turn");
        }

        $choice = $this->bankMakeChoice($session);
        if ($choice == "call" || $choice == "check") {
            $session->get("pokerGame")->river();
            return $this->redirectToRoute("poker-river");
        }
        return $this->redirectToRoute("poker-turn");
    }

    /**
     * @Route("proj/poker/river", name="poker-river", methods={"GET"})
     */
    public function pokerRiver(
        SessionInterface $session
    ): Response
    {
        if ($session->get("blindTurn") == "you") {
            $choice = $this->bankMakeChoice($session);
            if ($choice == "call") {
                return $this->redirectToRoute("poker-compare", [], 307);
            }
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
     * @Route("proj/poker/river", name="poker-river-process", methods={"POST"})
     */
    public function pokerRiverProcess(
        SessionInterface $session,
        Request $request
    ): Response
    {
        $session->set("answer", false);

        if ($request->request->get("check") && $session->get("blindTurn") == "you") {
            return $this->redirectToRoute("poker-compare", [], 307);
        }
        if ($request->request->get("check")) {
            $choice = $this->bankMakeChoice($session);
            if ($choice == "check") {
                return $this->redirectToRoute("poker-compare", [], 307);
            }
            return $this->redirectToRoute("poker-river");
        }
        if ($request->request->get("call")) {
            // dra pengar
            $session->get("pokerGame")->addToPot($session->get("callAmount"));
            $session->set("callAmount", 0);
            $session->set("answer", false);
            return $this->redirectToRoute("poker-compare", [], 307);
        }
        if ($request->request->get("fold")) {
            $this->addFlash("label", "Hand folded, You lost.");
            return $this->redirectToRoute("poker-compare", [], 307);
        }
        // Raise case //
        $raise = $request->request->get("wage");
        $callAmount = $session->get("callAmount");
        $session->get("pokerGame")->addToPot($raise);

        // dra pengar
        $session->set("callAmount", $raise - $callAmount);
        if ($session->get("blindTurn") == "you") {
            return $this->redirectToRoute("poker-river");
        }

        $choice = $this->bankMakeChoice($session);
        if ($choice == "call" || $choice == "check") {
            return $this->redirectToRoute("poker-compare", [], 307);
        }
        return $this->redirectToRoute("poker-river");
    }

    /**
     * @Route("proj/poker/compare", name="poker-compare", methods={"POST"})
     */
    public function pokerCompare(
        SessionInterface $session,
    ): Response
    {
        $player     = $session->get("pokerGame")->getPlayerFull();
        $bank       = $session->get("pokerGame")->getBankFull();
        $community  = $session->get("pokerGame")->getCommunityFull();

        $playerHand = new Rules($player, $community);
        $bankHand   = new Rules($bank, $community);
        $compare    = new Compare($playerHand, $bankHand);

        $result = $compare->compareHands();
        $this->addFlash("label", $result[0] . " win with " . $result[1]);

        return $this->redirectToRoute("poker-end");
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
            "user"           => $session->get("user"),
            "blind"          => $session->get("pokerBlind"),
            "blindTurn"      => $session->get("blindTurn"),
            "pot"            => $session->get("pokerGame")->getPot(),
            "player"         => $session->get("pokerGame")->getPlayer(),
            "community"      => $session->get("pokerGame")->getCommunity(),
            "bank"           => $session->get("pokerGame")->getBank(),
            "answer"         => $session->get("answer"),
            "callAmount"     => $session->get("callAmount")
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