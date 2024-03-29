<?php

namespace App\Action\Quizz;

use App\Core\Controller\AbstractController;
use PDO;

class QuizzList extends AbstractController
{
    public function __invoke()
    {

        if (isset($_SESSION['isAdmin'])) {
            if ($_SESSION['isAdmin'] === true) {

                $currentPage = $_GET['page'] ?? 1;

                $connection = $this->getConnection();
                $request = $this->getRequest();
                $formParams = $request->getParsedBody();


                if ($formParams !== null) {
                    $q = ("SELECT COUNT(q.id) AS numberposts 
                    FROM quizzes q INNER JOIN users u ON q.user1 = u.id OR q.user2 = u.id 
                    WHERE u.firstName LIKE '%:search%' OR u.lastName LIKE '%:search%' OR u.email LIKE '%:search%'");

                    $state = $connection->query($q);
                    $data = $state->fetch(\PDO::FETCH_ASSOC);

                    $number_posts = $data['numberposts'];

                    $perPage = 1;
                    $numberPages = ceil($number_posts / $perPage);

                    $sql = ("SELECT * FROM quizzes q INNER JOIN users u ON q.user1 = u.id OR q.user2 = u.id WHERE
                    users.firstName LIKE '%:search%'  OR
                    users.lastName LIKE '%:search%'  OR 
                    users.email LIKE '%:search%' LIMIT :perPage OFFSET :currentPage * :perPage");

                    $statement = $connection->prepare($sql);

                    $statement->bindParam(':search', $formParams['search']);
                    $statement->bindParam(':perPage', $perPage);
                    $statement->bindParam(':currentPage', $currentPage - 1);

                    $statement->execute();
                } else {
                    $q = "SELECT COUNT(id) AS numberposts FROM quizzes";

                    $state = $connection->query($q);
                    $data = $state->fetch(\PDO::FETCH_ASSOC);

                    $number_posts = $data['numberposts'];

                    $perPage = 1;
                    $numberPages = ceil($number_posts / $perPage);

                    $sql = 'SELECT * FROM quizzes LIMIT ' . $perPage . " OFFSET " . ($currentPage - 1) * $perPage;
                }
                $statement = $connection->prepare($sql);

                $usersData = 'SELECT u.lastName, u.firstName FROM quizzes q INNER JOIN users u ON q.user1 = u.id OR q.user2 = u.id';
                $winner = 'SELECT u.lastName, u.firstName FROM quizzes q INNER JOIN users u ON q.winner = u.id';

                $temp = $connection->query($usersData);
                $temp2 = $connection->query($winner);
        
                $uData = $temp->fetchAll(\PDO::FETCH_ASSOC);
                $wData = $temp2->fetch(\PDO::FETCH_ASSOC);

                if ($statement->execute()) {
                    $quizzes = $statement->fetchAll(\PDO::FETCH_ASSOC);

                    return $this->render('quizzes/list.html.twig', ['quizzes' => $quizzes, 'isAdmin' => $_SESSION['isAdmin'], 'numberPages' => $numberPages, 'uData' => $uData, 'wData' => $wData]);
                } else {
                    throw new \Exception('Quizz correspondant introuvable!');
                }
            } else {
                return $this->render('quizzes/list.html.twig', ['msg' => 'Vous n\'êtes pas administrateur et ne pouvez donc pas acceder à cette page']);
            }
        }
    }
}
