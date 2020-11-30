<?php

namespace App\Action\Admin;

use App\Core\Controller\AbstractController;

class Persist extends AbstractController
{
    public function __invoke(int $id = null)
    {

        if (isset($_SESSION['isAdmin'])) {
            if ($_SESSION['isAdmin'] === true) {
                $connection = $this->getConnection();

                // if i have an admin in the params, i should get him
                $admin = null;
                $isCreation = true;

                if ($id !== null) {
                    $isCreation = false;

                    $sql = 'select * from admins WHERE id=?';
                    $statement = $connection->prepare($sql);
                    $statement->execute([$id]);

                    $admin = $statement->fetch(\PDO::FETCH_ASSOC);

                    if (false === $admin) {
                        throw new \Exception('Administrateur non trouvé!', 404);
                    }
                }

                $request = $this->getRequest();
                if ($request->getMethod() === 'POST') {
                    $formParams = $request->getParsedBody();

                    if (strlen($formParams['firstName']) > 100 || strlen($formParams['lastName']) > 100 || strlen($formParams['email']) > 100) {
                        return $this->render('admins/persist.html.twig', ['info' => 'Le nom, le prénom et le mot de passe doivent faire moins de 100 caractères.', 'isAdmin' => $_SESSION['isAdmin']]);
                    }

                    if (!filter_var($formParams['email'], FILTER_VALIDATE_EMAIL)) {
                        $admin['firstName'] = $formParams['firstName'];
                        $admin['lastName'] = $formParams['lastName'];
                        return $this->render('admins/persist.html.twig', ['info' => 'Email invalide', 'isAdmin' => $_SESSION['isAdmin'], 'admin' => $admin]);
                    }

                    if ($formParams['password'] !== $formParams['passwordConf']) {
                        $admin['firstName'] = $formParams['firstName'];
                        $admin['lastName'] = $formParams['lastName'];
                        $admin['email'] = $formParams['email'];
                        return $this->render('admins/persist.html.twig', ['info' => 'Le mot de passe et la confirmation de mot de passe ne correspondent pas', 'isAdmin' => $_SESSION['isAdmin']]);
                    }

                    $hashed = password_hash($formParams['password'], PASSWORD_DEFAULT);
                    $today = date('Y-m-d');

                    if ($isCreation) {
                        $sql = 'INSERT INTO admins(firstName,lastName,email,password,registerAt) VALUES(?,?,?,?,?)';
                        $args = [$formParams['firstName'], $formParams['lastName'], $formParams['email'], $hashed, $today];
                    } else {
                        $sql = 'UPDATE admins SET firstName=?, lastName=?, email=?, password=? WHERE id=?';
                        $args = [$formParams['firstName'], $formParams['lastName'], $formParams['email'], $hashed, $id];
                    }

                    $statement = $connection->prepare($sql);

                    if ($statement->execute($args)) {
                        header('Location: /admins');
                        exit(0);
                    }

                    throw new \Exception('Une erreur est survenue!');
                }

                return $this->render('admins/persist.html.twig', [
                    'admin' => $admin, 'isAdmin' => $_SESSION['isAdmin']
                ]);
            } else {
                return $this->render('admins/persist.html.twig', ['msg' => 'Vous n\'êtes pas administrateur et ne pouvez donc pas acceder à cette page']);
            }
        }
    }
}
