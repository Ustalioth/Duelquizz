<?php

namespace App\Action;

use App\Core\Controller\AbstractController;

class UserList extends AbstractController
{
    public function __invoke()
    {

        if (isset($_SESSION['isAdmin'])) {
            if ($_SESSION['isAdmin'] === true) {
                $connection = $this->getConnection();

                $sql = 'select * from users';
                $statement = $connection->query($sql);

                $users = $statement->fetchAll(\PDO::FETCH_ASSOC);

                return $this->render('users/list.html.twig', ['users' => $users]);
            } else {
                return $this->render('users/list.html.twig', ['msg' => 'Vous n\'êtes pas administrateur et ne pouvez donc pas acceder à cette page']);
            }
        }
    }
}
