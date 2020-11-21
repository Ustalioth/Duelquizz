<?php

namespace App\Action;

use App\Core\Controller\AbstractController;

class Home extends AbstractController
{
    public function __invoke()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        return $this->render('home.html.twig', ['isAdmin' => $_SESSION['isAdmin'], 'firstname' => $_SESSION['firstName'], 'lastname' => $_SESSION['lastName']]);
    }
}
