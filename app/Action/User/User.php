<?php

namespace App\Action\User;

use App\Core\Controller\AbstractController;
use App\Entity\User as UserEntity;
use App\Validator\Validator;

class User extends AbstractController
{
    public function __invoke(string $userName)
    {
        $user = new UserEntity();
        $user->setUsername('John');
        $user->setFirstName('Boris');
        $user->setEmail('boris@boris.fr');

        $validator = new Validator();
        $isValid = $validator->validate($user);

        if (!$isValid) {
            // afficher erreurs
            echo '<pre>';
            print_r($validator->getErrors());
            die;
        } else {
            $connection = $this->getConnection();
        }

        return $this->render('users/user.html.twig', [
            'name' => $userName
        ]);
    }
}
