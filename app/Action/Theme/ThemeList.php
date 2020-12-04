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
                $currentPage = $_GET['page'] ?? 1;

                $q = "SELECT COUNT(id) AS numberposts FROM themes";

                $state = $connection->query($q);
                $data = $state->fetch(\PDO::FETCH_ASSOC);

                $number_posts = $data['numberposts'];

                $perPage = 1;
                $numberPages = ceil($number_posts / $perPage);

                $sql = 'SELECT * FROM themes LIMIT ' . $perPage . 'OFFSET ' . ($currentPage - 1) * $perPage;

                $statement = $connection->prepare($sql);
                $statement->execute();
                $themes = $statement->fetchAll();
                var_dump($themes);die;
                return $this->render('themes/list.html.twig', ['themes' => $themes, 'isAdmin' => $_SESSION['isAdmin'], 'numberPages' => $numberPages]);

            } else {
                return $this->render('themes/list.html.twig', ['msg' => 'Vous n\'êtes pas administrateur et ne pouvez donc pas acceder à cette page']);
            }
        }
    }
}
