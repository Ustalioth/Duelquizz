<?php

namespace App\Action\Question;

use App\Core\Controller\AbstractController;

class Persist extends AbstractController
{
    public function __invoke(int $id = null)
    {

        if (isset($_SESSION['isAdmin'])) {

            if ($_SESSION['isAdmin'] === true) {
                $connection = $this->getConnection();

                $questions = null;
                $isCreation = true;

                if ($id !== null) {
                    $isCreation = false;

                    $question = $this->getQuestion([$id], $connection);
                }


                $request = $this->getRequest();


                if ($request->getMethod() === 'GET') {
                    if ($isCreation === false) {
                        $question = $this->getQuestion([$id], $connection);

                        $themes = $this->getAllthemes($connection);

                        $theme = $this->getQuestionTheme($connection, $question);

                        return $this->render('questions/persist.html.twig', [
                            'question' => $question, 'correspondingTheme' => $theme, 'themes' => $themes, 'isAdmin' => $_SESSION['isAdmin']
                        ]);
                    } else {
                        $sql = 'SELECT * FROM themes'; //On récupère tous les thèmes pr générer les options du select

                        $statement = $connection->query($sql);
                        $themes = $statement->fetchAll();

                        return $this->render('questions/persist.html.twig', [
                            'themes' => $themes, 'isAdmin' => $_SESSION['isAdmin']
                        ]);
                    }
                } else {
                    $formParams = $request->getParsedBody();

                    if ($isCreation) {
                        $sql = 'INSERT INTO questions(label, theme) VALUES(?,?)';
                        $args = [$formParams['label'], $formParams['theme']];
                    } else {
                        $sql = 'UPDATE questions SET label=?, theme=? WHERE id=?';
                        $args = [$formParams['label'], $formParams['theme'], $id];
                    }

                    $statement = $connection->prepare($sql);

                    if ($statement->execute($args)) {
                        header('Location: /questions');
                        exit(0);

                        throw new \Exception('Une erreur est survenue!');
                    }

                    $statement = $connection->prepare($sql);
                }
            } else {
                return $this->render('questions/persist.html.twig', ['msg' => 'Vous n\'êtes pas administrateur et ne pouvez donc pas acceder à cette page']);
            }
        } else {
            return $this->render('login.html.twig', ['msg' => 'Vous devez vous connecter pour accéder à cette page']);
        }
    }
}
