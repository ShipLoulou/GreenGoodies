<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/auth/connexion', name: 'app_login')]
    public function login(
        AuthenticationUtils $authenticationUtils
    ): Response {
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        dump($authenticationUtils);

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

    #[Route('/auth/delete', name: 'app_auth_deleteUser')]
    public function deleteUser(
        EntityManagerInterface $em,
    ) {
        $user = $this->getUser();;

        // Définie le token de l'utilisateur connecté à null.
        $this->container->get('security.token_storage')->setToken(null);

        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('app_products');
    }

    #[Route('/activationApi', name: 'app_apiActivation')]
    public function apiActivation(
        UserRepository $userRepository,
        EntityManagerInterface $em,
        TokenStorageInterface $tokenStorage
    ): Response {
        // Cherche l'utilisateur connecté.
        $email = $this->getUser()->getUserIdentifier();
        $user = $userRepository->findOneBy(['email' => $email]);

        // Tableau contenant tous les rôles actuelles de l'utilisateur
        $arrayRoles = $user->getRoles();

        // Si 'API_ACTIVE' n'est pas dans le tableau des rôles, le rajouter, sinon le supprimer.
        if (!in_array('API_ACTIVE', $arrayRoles)) {
            $arrayRoles[] = 'API_ACTIVE';
        } else {
            unset($arrayRoles[array_search('API_ACTIVE', $arrayRoles)]);
        }

        $user->setRoles($arrayRoles);
        $em->flush();

        // Permet à l'utilisateur de rester connecté lors du changement de rôle.
        $tokenStorage->setToken(new UsernamePasswordToken($user, 'main', $user->getRoles()));

        return $this->redirectToRoute('app_orders_show');
    }
}
