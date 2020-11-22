<?php

namespace App\Action\Admin;

use App\Core\Controller\AbstractController;

class AdminList extends AbstractController
{
    public function __invoke()
    {

        if (isset($_SESSION['isAdmin'])) {
            if ($_SESSION['isAdmin'] === true) {
                $connection = $this->getConnection();

                $sql = 'select * from admins';
                $statement = $connection->query($sql);

                $admins = $statement->fetchAll(\PDO::FETCH_ASSOC);

                return $this->render('admins/list.html.twig', ['admins' => $admins, 'isAdmin' => $_SESSION['isAdmin']]);
            } else {
                return $this->render('admins/list.html.twig', ['msg' => 'Vous n\'êtes pas administrateur et ne pouvez donc pas acceder à cette page']);
            }
        }
    }
}
