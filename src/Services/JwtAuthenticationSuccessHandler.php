<?php

namespace App\Services;

use App\Entity\User;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class JwtAuthenticationSuccessHandler
{
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event): void
    {
        $data = $event->getData(); // Contient le token JWT
        $user = new User;
        $user = $event->getUser(); // Récupère l'utilisateur authentifié

        if ($user) {
            $data['user'] = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
                // Ajoutez d'autres informations utiles ici
            ];
        }

        if (in_array('API_ACTIVE', $user->getRoles())) {
            $event->setData($data);
        } else {
            throw new AccessDeniedHttpException('Accès API non activé');
        }
    }
}
