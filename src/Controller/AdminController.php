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

class AdminController extends AbstractController
{
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
    * @Route("proj/single/{userid}", name="casino-single", methods={"GET"})
    */
    public function casinoSingle(
        SessionInterface $session,
        ManagerRegistry $doctrine,
        int $userid
    ) {
        if (!$session->get("user") || $session->get("user")->getAdmin() != "admin") {
            return $this->redirectToRoute("casino-index");
        }

        $repository = $doctrine->getRepository(Users::class);
        $target = $repository->find($userid);

        $data = [
            "loggedInStatus" => $session->get("loggedInStatus") ?? false,
            "user" => $session->get("user"),
            "target" => $target
        ];

        return $this->render("casino/single.html.twig", $data);
    }

    /**
     * @Route("proj/update/{userid}", name="casino-update", methods={"GET"})
     */
    public function casinoUpdate(
        SessionInterface $session,
        ManagerRegistry $doctrine,
        int $userid
    ) {
        if (!$session->get("user") || $session->get("user")->getAdmin() != "admin") {
            return $this->redirectToRoute("casino-index");
        }

        $repository = $doctrine->getRepository(Users::class);
        $target = $repository->find($userid);

        $data = [
            "loggedInStatus" => $session->get("loggedInStatus") ?? false,
            "user" => $session->get("user"),
            "target" => $target
        ];

        return $this->render("casino/update.html.twig", $data);
    }

    /**
     * @Route("/proj/update/{userid}", name="casino-update-process", methods={"POST"})
     */
    public function casinoUpdateProcess(
        Request $request,
        ManagerRegistry $doctrine,
        int $userid
    ): Response {
        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(Users::class)->find($userid);

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
     * @Route("proj/delete/{userid}", name="casino-delete", methods={"GET"})
     */
    public function casinoDelete(
        SessionInterface $session,
        ManagerRegistry $doctrine,
        int $userid
    ) {
        if (!$session->get("user") || $session->get("user")->getAdmin() != "admin") {
            return $this->redirectToRoute("casino-index");
        }

        $repository = $doctrine->getRepository(Users::class);
        $target = $repository->find($userid);

        $data = [
            "loggedInStatus" => $session->get("loggedInStatus") ?? false,
            "user" => $session->get("user"),
            "target" => $target
        ];

        return $this->render("casino/delete.html.twig", $data);
    }

    /**
     * @Route("/proj/delete/{userid}", name="casino-delete-process", methods={"POST"})
     */
    public function casinoDeleteProcess(
        ManagerRegistry $doctrine,
        int $userid
    ): Response {
        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(Users::class)->find($userid);

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute("casino-admin");
    }
}
