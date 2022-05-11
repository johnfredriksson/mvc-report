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
use App\Repository\UsersRepository;


class CasinoController extends AbstractController
{
    /**
     * @Route("/proj", name="casino-index")
     */
    public function casinoIndex(
        SessionInterface $session
    ):Response
    {
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
    ):Response
    {
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
    ): Response
    {

        $repository = $doctrine->getRepository(Users::class);
        $user = $repository->findOneBy(["username" => $request->request->get("username")]);

        $session->set("loggedInStatus", True);
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
    ): Response
    {
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
    ): Response
    {
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

        $session->set("loggedInStatus", true);
        $session->set("user", $usersRepository->find($request->request->get("username")));

        $data = [
            "loggedInStatus" => $session->get("loggedInStatus") ?? false,
            "user" => $session->get("user") ?? false
        ];

        return $this->render("casino/register.html.twig", $data);
    }

    /**
     * @Route("/casino/logout", name="casino-logout")
     */
    public function casinoLogout(
        SessionInterface $session
    ): Response
    {
        $session->invalidate();

        return $this->redirectToRoute("casino-index");
    }

    /**
     * @Route("/proj/account", name="casino-account", methods={"GET"})
     */
    public function casinoAccount(
        SessionInterface $session
    ): Response
    {
        if (!$session->get("user")) {
            return $this->redirectToRoute("casino-login");
        };

        $data = [
            "loggedInStatus" => $session->get("loggedInStatus") ?? false,
            "user" => $session->get("user")
        ];

        return $this->render("casino/account.html.twig", $data);
    }

    /**
     * @Route("/proj/account", name="casino-update-balance", methods={"POST"})
     */
    public function casinoUpdateBalanceProcess(
        SessionInterface $session,
        ManagerRegistry $doctrine,
        Request $request
    ): Response
    {
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
    ): Response
    {
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
    ): Response
    {
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
}