<?php

namespace App\Action\Admin;

use App\Core\Controller\AbstractController;

class Persist extends AbstractController
{
    public function __invoke(int $adminId = null)
    {
        $connection = $this->getConnection();

       // if i have an admin in the params, i should get him
        $admin = null;
        $isCreation = true;

        if ($adminId !== null) {
            $isCreation = false;

            $sql = 'select * from admins WHERE id=?';
            $statement = $connection->prepare($sql);
            $statement->execute([$adminId]);

            $admin = $statement->fetch(\PDO::FETCH_ASSOC);

            if (false === $admin) {
                throw new \Exception('Administrateur non trouvé!', 404);
            }
        }

        $request = $this->getRequest();
        if ($request->getMethod() === 'POST') {
            $formParams = $request->getParsedBody();

            if ($isCreation) {
                $sql = 'INSERT INTO admins(username) VALUES(?)';
                $args = [$formParams['username']];
            } else {
                $sql = 'UPDATE admins SET username=? WHERE id=?';
                $args = [$formParams['username'], $adminId];
            }

            $statement = $connection->prepare($sql);

            if ($statement->execute($args)) {
                header('Location: /admins');
                exit(0);
            }

            throw new \Exception('Une erreur est survenue!');
        }

        return $this->render('admins/persist.html.twig', [
            'admin' => $admin
        ]);
    }
}
