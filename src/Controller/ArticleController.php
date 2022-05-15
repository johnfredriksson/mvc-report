<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Card\Deck;

class ArticleController extends AbstractController
{
    /**
     * @Route("/proj/cleancode", name="proj-article")
     *
     */
    public function home(): Response
    {
        return $this->render('proj/article.html.twig');
    }
}
