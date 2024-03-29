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
            return json_encode(["error" => "Already exists"]);
        }

        $sth = $connexion->prepare("INSERT INTO users (firstName, lastName, email, registerAt, password) VALUES (:firstName, :lastName, :email, NOW(), :password)");
        $sth->bindParam(':firstName', $data['firstName'], PDO::PARAM_STR);
        $sth->bindParam(':lastName', $data['lastName'], PDO::PARAM_STR);
        $sth->bindParam(':email', $data['email'], PDO::PARAM_STR);
        $hashed = password_hash($data['password'], PASSWORD_DEFAULT);
        $sth->bindParam(':password', $hashed, PDO::PARAM_STR);

        if ($sth->execute()) {
            $user = $usermanager->findOneByEmail($data['email']);
            $this->sendMail($user);
            return json_encode(['ok' => true]);
        } else {
            return $sth->errorCode();
        }
    }
}
