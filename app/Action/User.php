<?php

namespace App\Action;

use App\Core\Controller\AbstractController;

class User extends AbstractController
{
    public function __invoke(string $userName)
    {
        return $this->render('users/user.html.twig', [
            'name' => $userName
        ]);
    }
}
