<?php

namespace App\Action\User;

use App\Core\Controller\AbstractController;
use App\Service\UserManager;
use Error;
use PDO;


class PersistUser extends AbstractController
{
    public function __invoke()
    {
        $connexion = $this->getConnection();
        $usermanager = new UserManager;
        $request = $this->getRequest();
        $data = $request->getParsedBody();

        if ($usermanager->findOneByEmail($data['email']) !== null) {
            throw new Error("Un compte correspond déjà à cette adresse mail");
        }

        $sth = $connexion->prepare("INSERT INTO users (firstName, lastName, email, registerAt, password) VALUES (:firstName, :lastName, :email, NOW(), :password)");
        $sth->bindParam(':firstName', $data['firstName'], PDO::PARAM_STR);
        $sth->bindParam(':lastName', $data['lastName'], PDO::PARAM_STR);
        $sth->bindParam(':email', $data['email'], PDO::PARAM_STR);
        $hashed = password_hash($data['password'], PASSWORD_DEFAULT);
        $sth->bindParam(':password', $hashed, PDO::PARAM_STR);

        if ($sth->execute()) {
            return json_encode(['ok' => 'ok']);
        } else {
            return $sth->errorCode();
        }
    }
}
