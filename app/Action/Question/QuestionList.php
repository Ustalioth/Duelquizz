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
                $currentPage = (int)($_GET['page'] ?? 1);

                $q = "SELECT COUNT(id) AS numberposts FROM questions";

                $state = $connection->query($q);
                $data = $state->fetch(\PDO::FETCH_ASSOC);

                $number_posts = $data['numberposts'];

                $perPage = 1;
                $numberPages = ceil($number_posts / $perPage);

                $sql = 'SELECT * FROM questions LIMIT ' . $perPage . ' OFFSET ' . ($currentPage - 1) * $perPage;

                $statement = $connection->prepare($sql);
                $statement->execute();
                $questions = $statement->fetchAll();

                $themes = array();
                foreach ($questions as $question) {
                    array_push($themes, $this->getQuestionTheme($connection, $question));
                }

                return $this->render('questions/list.html.twig', ['themes' => $themes, 'questions' => $questions, 'isAdmin' => $_SESSION['isAdmin'], 'numberPages' => $numberPages]);
            } else {
                return $this->render('questions/list.html.twig', ['msg' => 'Vous n\'êtes pas administrateur et ne pouvez donc pas acceder à cette page']);
            }
        }
    }
}
