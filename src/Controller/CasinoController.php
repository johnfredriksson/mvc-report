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
     * @Route("proj/login", name="casino-login", methods={"GET"})
     */
    public function casinoLogin(
        SessionInterface $session
    ): Response {
        $data = [
            "loggedInStatus" => $session->get("loggedInStatus") ?? false,
            "user" => $session->get("user") ?? false
        ];

        return $this->render("casino/login.html.twig", $data);
    }

    /**
     * @Route("/proj/login", name="casino-login-process", methods={"POST"})
     */
    public function casinoLoginProcess(
        SessionInterface $session,
        Request $request,
        UsersRepository $usersRepository,
        ManagerRegistry $doctrine
    ): Response {
        $repository = $doctrine->getRepository(Users::class);
        $user = $repository->findOneBy(["username" => $request->request->get("username")]);

        if (!$user || !password_verify($request->request->get("password"), $user->getPassword())) {
            $this->addFlash("notice", "Fel lösenord eller användarnamn");
            return $this->redirectToRoute("casino-login");
        }

        $session->set("loggedInStatus", true);
        $session->set("user", $user);
        $session->set("admin", $user->getAdmin());


        $data = [
            "loggedInStatus" => $session->get("loggedInStatus") ?? false,
            "user" => $session->get("user") ?? false
        ];

        return $this->redirectToRoute("casino-index");
    }

    /**
     * @Route("/proj/register", name="register", methods={"GET"})
     */
    public function casinoRegister(
        SessionInterface $session
    ): Response {
        $data = [
            "loggedInStatus" => $session->get("loggedInStatus") ?? false,
            "user" => $session->get("user") ?? false
        ];

        return $this->render("casino/register.html.twig", $data);
    }

    /**
     * @Route("/proj/register", name="register-post", methods={"POST"})
     */
    public function casinoRegisterProcess(
        SessionInterface $session,
        Request $request,
        ManagerRegistry $doctrine,
        UsersRepository $usersRepository
    ): Response {
        $entityManager = $doctrine->getManager();

        $user = new Users();
        $user->setFirstname($request->request->get("firstname"));
        $user->setLastname($request->request->get("lastname"));
        $user->setUsername($request->request->get("username"));
        $user->setEmail($request->request->get("email"));
        $user->setPassword(password_hash($request->request->get("password"), PASSWORD_DEFAULT));
        $user->setBalance(0);
        $user->setAdmin("user");

        $entityManager->persist($user);
        $entityManager->flush();

        $repository = $doctrine->getRepository(Users::class);
        $user = $repository->findOneBy(["username" => $request->request->get("username")]);

        $session->set("loggedInStatus", true);
        $session->set("user", $user);

        $data = [
            "loggedInStatus" => $session->get("loggedInStatus") ?? false,
            "user" => $session->get("user") ?? false
        ];

        return $this->redirectToRoute("casino-account");
    }

    /**
     * @Route("/casino/logout", name="casino-logout")
     */
    public function casinoLogout(
        SessionInterface $session
    ): Response {
        $session->invalidate();

        return $this->redirectToRoute("casino-index");
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
            $RAW_QUERY = $query;
            // @phpstan-ignore-next-line
            $statement = $entityManager->getConnection()->prepare($RAW_QUERY);
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
     * @Route("/proj/account", name="casino-account", methods={"GET"})
     */
    public function casinoAccount(
        SessionInterface $session,
        ManagerRegistry $doctrine,
    ): Response {
        if (!$session->get("user")) {
            return $this->redirectToRoute("casino-login");
        };

        $histories = $doctrine->getRepository(History::class)->findBy(["user" => $session->get("user")->getId()]);
        $result = [];
        foreach ($histories as $game) {
            array_push($result, $game->getOutcome());
        }

        $data = [
            "loggedInStatus" => $session->get("loggedInStatus") ?? false,
            "user" => $session->get("user"),
            "history" => $result
        ];

        // var_dump($session->get("user")->getHistory()[0]->getOutcome());

        return $this->render("casino/account.html.twig", $data);
    }

    /**
     * @Route("/proj/account", name="casino-update-balance", methods={"POST"})
     */
    public function casinoUpdateBalanceProcess(
        SessionInterface $session,
        ManagerRegistry $doctrine,
        Request $request
    ): Response {
        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(Users::class)->find($session->get("user")->getId());
        $user->setBalance($user->getBalance() + $request->request->get("amount"));

        $entityManager->flush();
        $session->set("user", $user);

        return $this->redirectToRoute("casino-account");
    }

    /**
     * @Route("proj/account/update", name="casino-account-update", methods={"GET"})
     */
    public function casinoAccountUpdate(
        SessionInterface $session,
        Request $request
    ): Response {
        if (!$session->get("user")) {
            return $this->redirectToRoute("casino-login");
        };

        $data = [
            "loggedInStatus" => $session->get("loggedInStatus") ?? false,
            "user" => $session->get("user")
        ];

        return $this->render("casino/account-update.html.twig", $data);
    }

    /**
     * @Route("proj/account/update", name="casino-account-update-process", methods={"POST"})
     */
    public function casinoAccountUpdatePost(
        SessionInterface $session,
        ManagerRegistry $doctrine,
        Request $request
    ): Response {
        $entityManager = $doctrine->getManager();
        // $repository = $doctrine->getRepository(Users::class);
        $user = $entityManager->getRepository(Users::class)->find($session->get("user")->getId());
        // $user = $session->get("user");
        $user->setFirstname($request->request->get("firstname"));
        $user->setLastname($request->request->get("lastname"));
        $user->setEmail($request->request->get("email"));
        if ($request->request->get("image")) {
            $user->setImage($request->request->get("image"));
        };

        $entityManager->flush();

        $session->set("user", $user);

        return $this->redirectToRoute("casino-account");
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

    /**
     * @Route("/proj/admin", name="casino-admin")
     */
    public function casinoAdmin(
        SessionInterface $session,
        ManagerRegistry $doctrine
    ) {
        if (!$session->get("user") || $session->get("user")->getAdmin() != "admin") {
            return $this->redirectToRoute("casino-index");
        }

        $repository = $doctrine->getRepository(Users::class);
        $users = $repository->findAll();

        $data = [
            "loggedInStatus" => $session->get("loggedInStatus") ?? false,
            "user" => $session->get("user"),
            "users" => $users
        ];

        return $this->render("casino/admin.html.twig", $data);
    }

    /**
     * @Route("proj/create", name="casino-create", methods={"GET"})
     */
    public function casinoCreate(
        SessionInterface $session,
        ManagerRegistry $doctrine
    ) {
        if (!$session->get("user") || $session->get("user")->getAdmin() != "admin") {
            return $this->redirectToRoute("casino-index");
        }

        $data = [
            "loggedInStatus" => $session->get("loggedInStatus") ?? false,
            "user" => $session->get("user"),
        ];

        return $this->render("casino/create.html.twig", $data);
    }

    /**
     * @Route("/proj/create", name="casino-create-post", methods={"POST"})
     */
    public function casinoCreateProcess(
        Request $request,
        ManagerRegistry $doctrine,
    ): Response {
        $entityManager = $doctrine->getManager();

        $user = new Users();
        $user->setFirstname($request->request->get("firstname"));
        $user->setLastname($request->request->get("lastname"));
        $user->setUsername($request->request->get("username"));
        $user->setEmail($request->request->get("email"));
        $user->setPassword(password_hash($request->request->get("password"), PASSWORD_DEFAULT));
        $user->setBalance(0);
        $user->setAdmin($request->request->get("admin"));

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute("casino-admin");
    }

    /**
    * @Route("proj/single/{id}", name="casino-single", methods={"GET"})
    */
    public function casinoSingle(
        SessionInterface $session,
        ManagerRegistry $doctrine,
        int $id
    ) {
        if (!$session->get("user") || $session->get("user")->getAdmin() != "admin") {
            return $this->redirectToRoute("casino-index");
        }

        $repository = $doctrine->getRepository(Users::class);
        $target = $repository->find($id);

        $data = [
            "loggedInStatus" => $session->get("loggedInStatus") ?? false,
            "user" => $session->get("user"),
            "target" => $target
        ];

        return $this->render("casino/single.html.twig", $data);
    }

    /**
     * @Route("proj/update/{id}", name="casino-update", methods={"GET"})
     */
    public function casinoUpdate(
        SessionInterface $session,
        ManagerRegistry $doctrine,
        int $id
    ) {
        if (!$session->get("user") || $session->get("user")->getAdmin() != "admin") {
            return $this->redirectToRoute("casino-index");
        }

        $repository = $doctrine->getRepository(Users::class);
        $target = $repository->find($id);

        $data = [
            "loggedInStatus" => $session->get("loggedInStatus") ?? false,
            "user" => $session->get("user"),
            "target" => $target
        ];

        return $this->render("casino/update.html.twig", $data);
    }

    /**
     * @Route("/proj/update/{id}", name="casino-update-process", methods={"POST"})
     */
    public function casinoUpdateProcess(
        Request $request,
        ManagerRegistry $doctrine,
        int $id
    ): Response {
        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(Users::class)->find($id);

        $user->setFirstname($request->request->get("firstname"));
        $user->setLastname($request->request->get("lastname"));
        $user->setEmail($request->request->get("email"));
        if ($request->request->get("image")) {
            $user->setImage($request->request->get("image"));
        };
        $user->setUsername($request->request->get("username"));
        $user->setAdmin($request->request->get("admin"));
        $user->setBalance($request->request->get("balance"));

        $entityManager->flush();

        return $this->redirectToRoute("casino-admin");
    }

    /**
     * @Route("proj/delete/{id}", name="casino-delete", methods={"GET"})
     */
    public function casinoDelete(
        SessionInterface $session,
        ManagerRegistry $doctrine,
        int $id
    ) {
        if (!$session->get("user") || $session->get("user")->getAdmin() != "admin") {
            return $this->redirectToRoute("casino-index");
        }

        $repository = $doctrine->getRepository(Users::class);
        $target = $repository->find($id);

        $data = [
            "loggedInStatus" => $session->get("loggedInStatus") ?? false,
            "user" => $session->get("user"),
            "target" => $target
        ];

        return $this->render("casino/delete.html.twig", $data);
    }

    /**
     * @Route("/proj/delete/{id}", name="casino-delete-process", methods={"POST"})
     */
    public function casinoDeleteProcess(
        Request $request,
        ManagerRegistry $doctrine,
        int $id
    ): Response {
        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(Users::class)->find($id);

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute("casino-admin");
    }
}
