<?php

namespace App\Action;

use App\Core\Controller\AbstractController;
use App\Entity\User as UserEntity;
use App\Validator\Validator;

class User extends AbstractController
{
    public function __invoke(string $userName)
    {
        $user = new UserEntity();
        $user->setUsername('Joh n');
        $user->setEmail('boris@boris.fr');

        $validator = new Validator();
        $isValid = $validator->validate($user);

        if (!$isValid) {
            // afficher erreurs
            echo '<pre>';print_r($validator->getErrors());die;
        } else {
            // enregistrer en base de données
            $connection = $this->getConnection(); // get a PDO instance (singleton, les infos de la bdd (username, password) sont à mettre en config)
            // faire notre requête SQL pour insérer en base de données
        }

        return $this->render('users/user.html.twig', [
            'name' => $userName
        ]);
    }
}
