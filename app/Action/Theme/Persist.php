<?php

namespace App\Action\Theme;

use App\Core\Controller\AbstractController;

class Persist extends AbstractController
{
    public function __invoke(int $id = null)
    {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['isAdmin'])) {
            if ($_SESSION['isAdmin'] === true) {
                $connection = $this->getConnection();

                $theme = null;
                $isCreation = true;

                if ($id !== null) {
                    $isCreation = false;

                    $sql = 'select * from themes WHERE id=?';
                    $statement = $connection->prepare($sql);
                    $statement->execute([$id]);

                    $theme = $statement->fetch(\PDO::FETCH_ASSOC);

                    if (false === $theme) {
                        throw new \Exception('Theme non trouvé!', 404);
                    }
                }

                $request = $this->getRequest();
                if ($request->getMethod() === 'POST') {
                    $formParams = $request->getParsedBody();

                    if ($isCreation) {
                        $sql = 'INSERT INTO themes(name) VALUES(?)';
                        $args = [$formParams['name']];
                    } else {
                        $sql = 'UPDATE themes SET name=?';
                        $args = [$formParams['name']];
                    }

                    $statement = $connection->prepare($sql);

                    if ($statement->execute($args)) {
                        header('Location: /themes');
                        exit(0);
                    }

                    throw new \Exception('Une erreur est survenue!');
                }

                return $this->render('themes/persist.html.twig', [
                    'theme' => $theme
                ]);
            } else {
                return $this->render('themes/persist.html.twig', ['msg' => 'Vous n\'êtes pas administrateur et ne pouvez donc pas acceder à cette page']);
            }
        }
    }
}
