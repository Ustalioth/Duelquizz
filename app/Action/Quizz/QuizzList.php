<?php

namespace App\Action\Quizz;

use App\Core\Controller\AbstractController;

class QuizzList extends AbstractController
{
    public function __invoke()
    {

        if (isset($_SESSION['isAdmin'])) {
            if ($_SESSION['isAdmin'] === true) {
                $connection = $this->getConnection();
                $request = $this->getRequest();
                $formParams = $request->getParsedBody();

                if($formParams!==null){
                    $sql = "SELECT DISTINCT * FROM users, quizzes WHERE
                    quizzes.user1 = users.id OR quizzes.user2 = users.id AND
                    users.firstName LIKE '%$formParams[search]%'  OR 
                    users.lastName LIKE '%$formParams[search]%'  OR 
                    users.email LIKE '%$formParams[search]%'";

                }
                else{
                    $sql = 'select * from quizzes';
                }  
                $statement = $connection->prepare($sql);

                if ($statement->execute()) {
                    $quizzes = $statement->fetchAll(\PDO::FETCH_ASSOC);

                    return $this->render('quizzes/list.html.twig', ['quizzes' => $quizzes, 'isAdmin' => $_SESSION['isAdmin']]);
                } else {
                    throw new \Exception('Quizz correspondant introuvable!');
                }

            } else {
                return $this->render('quizzes/list.html.twig', ['msg' => 'Vous n\'êtes pas administrateur et ne pouvez donc pas acceder à cette page']);
            }
        }
    }
}
