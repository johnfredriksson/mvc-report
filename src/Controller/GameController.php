<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
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
     * @Route("/game/table", name="game-table")
     **/
    public function table(): Response
    {
        $data = [
            "title" => "TABLE"
        ];

        return $this->render("game/table.html.twig", $data);
    }
}
