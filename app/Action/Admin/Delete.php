<?php

namespace App\Action\Admin;

use App\Core\Controller\AbstractController;

class Delete extends AbstractController
{
    public function __invoke(int $id)
    {
        $connection = $this->getConnection();

        $sql = 'select * from admins WHERE id=?';
        $statement = $connection->prepare($sql);
        $statement->execute([$id]);

        $admin = $statement->fetch(\PDO::FETCH_ASSOC);

        if (false === $admin) {
            throw new \Exception('Administrateur non trouvé!', 404);
        }

        $sql = 'DELETE FROM admins WHERE id=:id';
        $statement = $connection->prepare($sql);
        $statement->bindParam('id', $id, \PDO::PARAM_INT);

        if ($statement->execute()) {
            if ($this->getRequest()->getMethod() === 'GET') {
                header('Location: /admins');
                exit(0);
            }

            exit(0);
        }

        throw new \Exception('Une erreur est survenue!');
    }
}
