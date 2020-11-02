<?php

namespace App\Action;

use App\Core\Controller\AbstractController;

class Home extends AbstractController
{
    public function __invoke()
    {
        // télécharger twig
        // intégrer twig de la manière suivante
        return $this->render('home.html.twig', [
            'message' => 'Hello the World!'
        ]);
    }
}

