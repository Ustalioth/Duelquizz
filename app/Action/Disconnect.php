<?php

namespace App\Action;

use App\Core\Controller\AbstractController;

class Disconnect extends AbstractController
{
    public function __invoke()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        $this->setAuthCookie(); // On supprime le cookie pour bien déconnecter l'utilisateur

        return $this->render('login.html.twig', ['msg' => 'Vous avez été déconnecté']);
    }
}
