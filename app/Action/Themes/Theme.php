<?php

namespace App\Action\Themes;

use App\Core\Controller\AbstractController;

class Theme extends AbstractController
{
    public function __invoke()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['id'])) {
            return $this->render('login.html.twig', $this->getRequest()->getQueryParams());
        }

        return $this->render('theme.html.twig');
    }
}
