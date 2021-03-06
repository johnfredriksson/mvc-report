<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Card\Deck;

class CardJsonController extends AbstractController
{
    /**
     * @Route("/card/api/deck", name="deck-json")
     *
     */
    public function home(): Response
    {
        $deck = new Deck();

        $data = [
            'title' => 'Card',
            'data' => $deck->getJson()
        ];
        return $this->render('card/json.html.twig', $data);
    }
}
