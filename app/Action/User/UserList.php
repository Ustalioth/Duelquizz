<?php

namespace App\Action\User;

use App\Core\Controller\AbstractController;
use PDO;

class UserList extends AbstractController
{
    public function __invoke()
    {

        if (isset($_SESSION['isAdmin'])) {
            if ($_SESSION['isAdmin'] === true) {
                $connection = $this->getConnection();
                $currentPage = $_GET['page'] ?? 1;

                $q = "SELECT COUNT(id) AS numberposts FROM users";

                $state = $connection->query($q);
                $data = $state->fetch(\PDO::FETCH_ASSOC);

                $number_posts = $data['numberposts'];

                $perPage = 1;
                $numberPages = ceil($number_posts / $perPage);


                $sql = 'SELECT * FROM users LIMIT ' . $perPage . ' OFFSET ' . ($currentPage - 1) * $perPage;
                $statement = $connection->prepare($sql);
                $statement->execute();
                $users = $statement->fetchAll(PDO::FETCH_ASSOC);

                return $this->render('users/list.html.twig', ['users' => $users, 'isAdmin' => $_SESSION['isAdmin'], 'numberPages' => $numberPages]);
            } else {
                return $this->render('users/list.html.twig', ['msg' => 'Vous n\'êtes pas administrateur et ne pouvez donc pas acceder à cette page']);
            }
        }
    }
}
