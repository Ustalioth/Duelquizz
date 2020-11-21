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

        return $this->render('login.html.twig', ['msg' => 'Vous avez été déconnecté']);
    }
}
