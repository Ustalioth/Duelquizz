<?php

namespace App\Action;

use App\Core\Controller\AbstractController;

class Home extends AbstractController
{
    public function __invoke()
    {

        if (isset($_COOKIE['auth']) && !isset($_SESSION['id'])) {
            $connection = $this->getConnection();

            $auth = explode('-----', $_COOKIE['auth']);

            $sql = $connection->prepare('SELECT * FROM admins WHERE id=:id');
            $sql->bindParam(':id', $auth[0]);

            if ($sql->execute() && $sql->rowCount() === 1) {

                $result = $sql->fetch();

                $key = sha1($result['email'] . $result['password']);
                if ($key === $auth[1]) { //admin retrouvé et cookie valide, on connecte l'admin
                    $this->setSessionData($result['email'], $result['id'], $result['firstName'], $result['lastName'], true);
                    $this->setAuthCookie($_SESSION['id'], $_SESSION['email'], $_SESSION['password']); // On set à nouveau le cookie pour ajouter 3 jours à sa date de validité
                } else {
                    // Cookie invalide peut-être que l'id correspond à un user et un admin
                    // On cherche aussi dans la table user
                    $sql = $connection->prepare('SELECT * FROM users WHERE id=:id');
                    $sql->bindParam(':id', $auth[0]);

                    if ($sql->execute() && $sql->rowCount() === 1) { //user correspond aussi à l'id
                        $result = $sql->fetch();

                        $key = sha1($result['email'] . $result['password']);
                        if ($key === $auth[1]) { //user retrouvé et cookie valide, on connecte l'user
                            $this->setSessionData($result['email'], $result['id'], $result['firstName'], $result['lastName'], true);
                            $this->setAuthCookie($_SESSION['id'], $_SESSION['email'], $_SESSION['password']); // On set à nouveau le cookie pour ajouter 3 jours à sa date de validité
                        } else {
                            $this->setAuthCookie(); // Combo mail/mdp de l'user invalide cookie invalide supprimé
                        }
                    } else { //aucun user avec cet id retrouvé
                        $this->setAuthCookie(); // Combo mail/mdp de l'admin invalide cookie invalide supprimé
                    }
                }
            } else { // On cherche dans la table user vu qu'aucun admin n'a été trouvé avec l'id
                $sql = $connection->prepare('SELECT * FROM users WHERE id=:id');
                $sql->bindParam(':id', $auth[0]);

                if ($sql->execute() && $sql->rowCount() === 1) {

                    $result = $sql->fetch();

                    $key = sha1($result['email'] . $result['password']);
                    if ($key === $auth[1]) {
                        $this->setSessionData($result['email'], $result['id'], $result['firstName'], $result['lastName'], true);
                        $this->setAuthCookie($_SESSION['id'], $_SESSION['email'], $_SESSION['password']); // On set à nouveau le cookie pour ajouter 3 jours à sa date de validité
                    } else {
                        $this->setAuthCookie(); // Cookie invalide donc on le supprime
                    }
                }
            }
        }

        if (!isset($_SESSION['id'])) {
            return $this->render('login.html.twig', $this->getRequest()->getQueryParams());
        }

        return $this->render('home.html.twig', ['isAdmin' => $_SESSION['isAdmin'], 'firstname' => $_SESSION['firstName'], 'lastname' => $_SESSION['lastName']]);
    }
}
