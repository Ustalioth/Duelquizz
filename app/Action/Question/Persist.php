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

                        $possibleanswers = $this->getQuestionPossibleAnswers($connection, $question);

                        return $this->render('questions/persist.html.twig', [
                            'question' => $question, 'possibleanswers' => $possibleanswers, 'correspondingTheme' => $theme, 'themes' => $themes, 'isAdmin' => $_SESSION['isAdmin']
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
                        $correctExists = false;

                        for ($i = 1; $i < 5; $i++) {
                            if ($formParams['correct' . strval($i)] === '1') {
                                $correctExists = true;
                            }
                        }

                        if ($correctExists === false) {
                            throw new \Exception('Au moins une des réponses doit être correcte !');
                        }

                        $sql = 'INSERT INTO questions(label, theme) VALUES(?,?)';
                        $args = [$formParams['label'], $formParams['theme']];

                        $statement = $connection->prepare($sql);


                        if ($statement->execute($args)) {
                            $lastestQuestion = $this->getLatestQuestion($connection);

                            for ($i = 1; $i < 5; $i++) {
                                $sql = 'INSERT INTO possibleanswers(question, label, correct) VALUES(?,?,?)';
                                $args = [$lastestQuestion['id'],  $formParams['answer' . strval($i)], $formParams['correct' . strval($i)]];
                                $statement = $connection->prepare($sql);

                                if (!$statement->execute($args)) {
                                    throw new \Exception('Une erreur est survenue!');
                                }
                            }
                            header('Location: /questions');
                            exit(0);

                            throw new \Exception('Une erreur est survenue!');
                        }
                    } else {
                        $sql = 'UPDATE questions SET label=?, theme=? WHERE id=?';
                        $args = [$formParams['label'], $formParams['theme'], $id];
                    }


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
