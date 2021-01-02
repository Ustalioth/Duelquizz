<?php

namespace App\Service;

use App\Core\Connection\Connection;
use App\Entity\User;
use PDO;

class UserManager
{
    public function findOneByEmail(string $email): ?User
    {
        $connection = Connection::getInstance();

        $sql = 'SELECT * FROM users WHERE email=?';

        $statement = $connection->prepare($sql);
        $statement->execute([$email]);

        $data = $statement->fetch(\PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        $user = new User();
        $user->hydrate($data);

        return $user;
    }
}
