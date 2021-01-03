<?php

namespace App\Action\Question;

use App\Core\Controller\AbstractController;
use PDO;


class Delete extends AbstractController
{
    public function __invoke(int $id)
    {

        $connection = $this->getConnection();

        $sql = 'select * from questions WHERE id=?';
        $statement = $connection->prepare($sql);
        $statement->execute([$id]);

        $question = $statement->fetch(\PDO::FETCH_ASSOC);

        if (false === $question) {
            throw new \Exception('Question non trouvée!', 404);
        }

        $sql = 'DELETE FROM questions WHERE id=:id';
        $statement = $connection->prepare($sql);
        $statement->bindParam('id', $id, \PDO::PARAM_INT);

        if ($statement->execute()) {
            if ($this->getQuestionPossibleAnswers($connection, $question, true)) {
                if ($this->getRequest()->getMethod() === 'GET') {
                    header('Location: /questions');
                    exit(0);
                }
            }
            exit(0);
        }

        throw new \Exception('Une erreur est survenue!');
    }
}
