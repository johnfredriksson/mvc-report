<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Users;
use App\Entity\History;
use App\Repository\UsersRepository;

class CasinoController extends AbstractController
{
    /**
     * @Route("/proj", name="casino-index")
     */
    public function casinoIndex(
        SessionInterface $session
    ): Response {
        $data = [
            "loggedInStatus" => $session->get("loggedInStatus") ?? false,
            "user" => $session->get("user") ?? false
        ];

        return $this->render("casino/index.html.twig", $data);
    }

    /**
     * @Route("/proj/reset", name="casino-reset")
     */
    public function casinoReset(
        ManagerRegistry $doctrine,
        SessionInterface $session,
    ): Response {
        $session->invalidate();

        $sqlQueries = [
            "DROP TABLE users;",
            "CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 
            username VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, 
            lastname VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, 
            email VARCHAR(255) NOT NULL, admin VARCHAR(255) NOT NULL, balance 
            INTEGER NOT NULL, image VARCHAR(255) DEFAULT NULL);",
            "DROP TABLE history;",
            "create table history (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 
            user_id INTEGER NOT NULL, outcome INTEGER NOT NULL);",
            "create index IDX_27BA704BA76ED395 ON history (user_id)",
        ];


        $entityManager = $doctrine->getManager();

        foreach ($sqlQueries as $query) {
            $rawQuery = $query;
            // @phpstan-ignore-next-line
            $statement = $entityManager->getConnection()->prepare($rawQuery);
            $statement->execute();
        }

        $users = [["admin", "admin", "admin", "$2y$10$" .
        "MqvxbAdiC1VKJeksRmhwcOLmDYaZsfbxhFIJ899TI5k8Y0VeDWA0y", "admin", 10000, "admin"],
            ["doe", "doe", "doe", "$2y$10$9QH8AWK6qYrkjciAvsVYCeMTAunq.M4qqkU0QB3KIEpaQmDg.tUfu",
            "admin", 10000, "user"],
        ];

        foreach ($users as $userTemp) {
            $user = new Users();
            $user->setFirstname($userTemp[0]);
            $user->setLastname($userTemp[1]);
            $user->setUsername($userTemp[2]);
            $user->setPassword($userTemp[3]);
            $user->setEmail($userTemp[4]);
            $user->setBalance(intval($userTemp[5]));
            $user->setAdmin($userTemp[6]);

            $entityManager->merge($user);
            $entityManager->flush();
        }


        return $this->redirectToRoute("casino-index");
    }

    /**
     * @Route("/proj/about", name="casino-about")
     */
    public function casinoAbout(
        SessionInterface $session,
        ManagerRegistry $doctrine,
    ) {
        $entityManager = $doctrine->getManager();

        $history = new History();
        $history->setOutcome(rand(-100, 100));
        $user = $session->get("user");
        $user->addHistory($history);

        $entityManager->merge($history);
        $entityManager->flush();

        echo($user->getHistory()[0]->getOutcome());
        // var_dump($session->get("user")->getHistory());

        $data = [
            "loggedInStatus" => $session->get("loggedInStatus") ?? false,
            "user" => $session->get("user") ?? false
        ];

        return $this->render("casino/about.html.twig", $data);
    }
}
