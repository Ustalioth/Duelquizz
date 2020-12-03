<?php

namespace App\Action\Quizz;

use App\Core\Controller\AbstractController;

class QuizzList extends AbstractController
{
    public function __invoke()
    {

        if (isset($_SESSION['isAdmin'])) {
            if ($_SESSION['isAdmin'] === true) {

                    $currentPage =  1; 
                
                $connection = $this->getConnection();
                $request = $this->getRequest();
                $formParams = $request->getParsedBody();


                if ($formParams !== null) {
                    $q = ("SELECT COUNT(quizzes.id) AS numberposts FROM users, quizzes WHERE
                    quizzes.user1 = users.id OR quizzes.user2 = users.id AND
                    users.firstName LIKE '%:search%'  OR
                    users.lastName LIKE '%:search%'  OR 
                    users.email LIKE '%:search%'");
                    
                    $state = $connection->query($q);
                    $data = $state->fetch(\PDO::FETCH_ASSOC);

                    $number_posts= $data['numberposts'];

                    $perPage = 20;
                    $numberPages = ceil($number_posts/$perPage);

                    $sql = ("SELECT * FROM users, quizzes WHERE
                    quizzes.user1 = users.id OR quizzes.user2 = users.id AND
                    users.firstName LIKE '%:search%'  OR
                    users.lastName LIKE '%:search%'  OR 
                    users.email LIKE '%:search%' LIMIT " . $perPage. " OFFSET " . ($currentPage-1)*$perPage); 

                    $statement = $connection->prepare($sql);

                    $statement->bindParam(':search', $formParams['search']);

                    $statement->execute();
                } else {
                    $q = "SELECT COUNT(id) AS numberposts FROM quizzes";

                    $state = $connection->query($q);
                    $data = $state->fetch(\PDO::FETCH_ASSOC);

                    $number_posts= $data['numberposts'];

                    $perPage = 1;
                    $numberPages = ceil($number_posts/$perPage);

                    $sql = 'SELECT * FROM quizzes LIMIT ' . $perPage . " OFFSET " . ($currentPage-1)*$perPage;

                }
                $statement = $connection->prepare($sql);

                if ($statement->execute()) {
                    $quizzes = $statement->fetchAll(\PDO::FETCH_ASSOC);

                    var_dump($quizzes);die;
                    return $this->render('quizzes/list.html.twig', ['quizzes' => $quizzes, 'isAdmin' => $_SESSION['isAdmin'], 'numberPages' => $numberPages]);
                } else {
                    throw new \Exception('Quizz correspondant introuvable!');
                }
            } else {
                return $this->render('quizzes/list.html.twig', ['msg' => 'Vous n\'êtes pas administrateur et ne pouvez donc pas acceder à cette page']);
            }
        }
    }
}
