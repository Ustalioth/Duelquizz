<?php

namespace App\Action\Admin;

use App\Core\Controller\AbstractController;

class AdminList extends AbstractController
{
    public function __invoke()
    {
        $connection = $this->getConnection();

        $sql = 'select * from admins';
        $statement = $connection->query($sql);

        $admins = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $this->render('admins/list.html.twig', ['admins' => $admins]);
    }
}
