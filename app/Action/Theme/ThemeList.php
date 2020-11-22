<?php

namespace App\Action\Theme;

use App\Core\Controller\AbstractController;

class ThemeList extends AbstractController
{
    public function __invoke()
    {

        if (isset($_SESSION['isAdmin'])) {
            if ($_SESSION['isAdmin'] === true) {
                $connection = $this->getConnection();

                $sql = 'select * from themes';
                $statement = $connection->query($sql);

                $themes = $statement->fetchAll(\PDO::FETCH_ASSOC);

                return $this->render('themes/list.html.twig', ['themes' => $themes]);
            } else {
                return $this->render('themes/list.html.twig', ['msg' => 'Vous n\'êtes pas administrateur et ne pouvez donc pas acceder à cette page']);
            }
        }
    }
}
