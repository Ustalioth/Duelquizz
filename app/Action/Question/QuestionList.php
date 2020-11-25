<?php

namespace App\Action\Question;

use App\Core\Controller\AbstractController;

class QuestionList extends AbstractController
{
    public function __invoke()
    {

        if (isset($_SESSION['isAdmin'])) {
            if ($_SESSION['isAdmin'] === true) {
                $connection = $this->getConnection();

                $sql = 'select * from questions';
                $statement = $connection->query($sql);

                $questions = $statement->fetchAll(\PDO::FETCH_ASSOC);

                $themes = array(); //array qui va contenir les thèmes qui correspondent aux questions
                foreach ($questions as $question) {
                    array_push($themes, $this->getQuestionTheme($connection, $question));
                }

                return $this->render('questions/list.html.twig', ['themes' => $themes, 'questions' => $questions, 'isAdmin' => $_SESSION['isAdmin']]);
            } else {
                return $this->render('questions/list.html.twig', ['msg' => 'Vous n\'êtes pas administrateur et ne pouvez donc pas acceder à cette page']);
            }
        }
    }
}
