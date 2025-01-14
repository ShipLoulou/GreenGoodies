<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/auth/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/activationApi', name: 'app_apiActivation')]
    public function apiActivation(
        UserRepository $userRepository,
        EntityManagerInterface $em,
        TokenStorageInterface $tokenStorage
    ): Response {
        $email = $this->getUser()->getUserIdentifier();
        $user = $userRepository->findOneBy(['email' => $email]);
        $arrayRoles = $user->getRoles();

        if (!in_array('API_ACTIVE', $arrayRoles)) {
            $arrayRoles[] = 'API_ACTIVE';
        } else {
            unset($arrayRoles[array_search('API_ACTIVE', $arrayRoles)]);
        }

        $user->setRoles($arrayRoles);
        $em->flush();

        $tokenStorage->setToken(new UsernamePasswordToken($user, 'main', $user->getRoles()));

        return $this->redirectToRoute('app_orders_show');
    }
}
