<?php

namespace App\Action;

use App\Core\Controller\AbstractController;

class Login extends AbstractController
{
    public function __invoke()
    {

        $connection = $this->getConnection();

        $request = $this->getRequest();

        if (isset($_POST['email'])) {

            $formParams = $request->getParsedBody();

            $sql = $connection->prepare("SELECT * FROM admins WHERE email = :email");
            $sql->bindParam(':email', $_POST['email']);

            if ($sql->execute()) {

                if ($sql->rowCount() < 1) { //Aucun admin n'a été trouvé
                    $sql = $connection->prepare("SELECT * FROM users WHERE email = :email"); //On effectue donc la requête dans la table user
                    $sql->bindParam(':email', $_POST['email']);

                    if ($sql->execute()) {
                        $result = $sql->fetch();

                        if ($sql->rowCount() < 1) { //Adresse mail ne correspond à rien dans la BDD donc mauvaise adresse mail
                            return $this->render('login.html.twig', [
                                'msg' => 'Adresse mail incorrecte'
                            ]);
                        } else if ($sql->rowCount() === 1) { //Utilisateur trouvé
                            if (password_verify($_POST['password'], $result['password'])) { //Test mot de passe
                                $this->setSessionData($result['email'], $result['id'], $result['firstName'], $result['lastName'], false);


                                if (isset($_POST['remember'])) {
                                    $this->setAuthCookie($_SESSION['id'], $_SESSION['email'], $result['password']);
                                }

                                header('Location: /');
                                exit(0);
                            } else {
                                return $this->render('login.html.twig', [
                                    'msg' => 'Mot de passe incorrect'
                                ]);
                            }
                        } else { //Plus de un utilisateur trouvé, ça ne devrait pas arriver...
                            throw new \Exception('Une erreur est survenue!');
                        }
                    }
                } else if ($sql->rowCount() === 1) {
                    $result = $sql->fetch();

                    if (password_verify($_POST['password'], $result['password'])) {
                        $this->setSessionData($result['email'], $result['id'], $result['firstName'], $result['lastName'], true);


                        if (isset($_POST['remember'])) {
                            $this->setAuthCookie($_SESSION['id'], $_SESSION['email'], $result['password']);
                        }

                        header('Location: /');
                        exit(0);
                    } else {
                        return $this->render('login.html.twig', [
                            'msg' => 'Mot de passe incorrect'
                        ]);
                    }
                } else {
                    throw new \Exception('Une erreur est survenue!');
                }
            }
        } else {
            return $this->render('login.html.twig', [
                'msg' => 'Pas de POST'
            ]);
        }
    }
}
