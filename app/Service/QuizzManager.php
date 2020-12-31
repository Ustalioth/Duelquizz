<?php

namespace App\Service;

use App\Core\Connection\Connection;
use App\Entity\Quizz;

class QuizzManager
{
    public function findOneById(int $id): ?Quizz
    {
        $connection = Connection::getInstance();

        $sql = 'SELECT * FROM quizzes WHERE id=?';

        $statement = $connection->prepare($sql);
        $statement->execute([$id]);

        $data = $statement->fetch(\PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        $quizz = new Quizz();
        $quizz->hydrate($data);

        return $quizz;
    }
}
