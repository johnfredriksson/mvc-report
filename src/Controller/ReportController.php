<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ReportController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }

    /**
     * @Route("/reset", name="reset")
     */
    public function reset(SessionInterface $session): Response
    {
        // $session->clear();
        $session->invalidate();

        return redirectToRoute("home");
    }

    /**
     * @Route("/about", name="about")
     */
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    /**
     * @Route("/report", name="report")
     */
    public function report(): Response
    {
        return $this->render('report.html.twig');
    }

    /**
     * @Route("/exercise", name="exercise")
     */
    public function exercise(): Response
    {
        return $this->render('exercise.html.twig');
    }

    /**
     * @Route("/metrics", name="metrics")
     */
    public function metrics(): Response
    {
        return $this->render("metrics.html.twig");
    }
}
