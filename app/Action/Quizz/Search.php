<?php

namespace App\Action\Quizz;

use App\Core\Controller\AbstractController;

class Search extends AbstractController
{
    public function __invoke()
    {

        if (isset($_SESSION['isAdmin'])) {
            if ($_SESSION['isAdmin'] === true) {
                $connection = $this->getConnection();

                $sql = 'select * from quizzes';
                $statement = $connection->query($sql);

                $quizzes = $statement->fetchAll(\PDO::FETCH_ASSOC);

                return $this->render('quizzes/list.html.twig', ['quizzes' => $quizzes, 'isAdmin' => $_SESSION['isAdmin']]);
            } else {
                return $this->render('quizzes/list.html.twig', ['msg' => 'Vous n\'êtes pas administrateur et ne pouvez donc pas acceder à cette page']);
            }
        }
    }
}
