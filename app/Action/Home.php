<?php

namespace App\Action;

use App\Core\Controller\AbstractController;

class Home extends AbstractController
{
    public function __invoke()
    {
        return $this->render('home.html.twig', $this->getRequest()->getQueryParams());
    }
}
