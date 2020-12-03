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

                $themes = $this->getAllthemes($connection);

                if (isset($question)) {
                    $theme = $this->getQuestionTheme($connection, $question);
                }

                $request = $this->getRequest();

                if ($request->getMethod() === 'GET') {
                    if ($isCreation === false) {
                        $question = $this->getQuestion([$id], $connection);

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

                    $correctAnswers = 0;

                    for ($i = 1; $i < 5; $i++) {
                        if (isset($formParams['correct' . strval($i)])) {
                            if ($formParams['correct' . strval($i)] === '1') {
                                $correctAnswers++;
                            }
                        }
                    }

                    if ($correctAnswers !== 1) {
                        return $this->getQuestionParamsAndRender($formParams, 'Une et une seule réponse doit être correcte !', $themes, $theme, $id);
                    }

                    $emptyParams = false;

                    if ($formParams['label'] === '' || strlen($formParams['theme']) === '') {
                        $emptyParams = true;
                    }

                    for ($i = 1; $i < 5; $i++) {
                        if ($formParams['answer' . strval($i)] === '') {
                            $emptyParams = true;
                        }
                    }

                    if ($emptyParams === true) {
                        return $this->getQuestionParamsAndRender($formParams, 'Merci de remplir tous les champs', $themes, $theme, $id);
                    }

                    if ($isCreation) {

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
                        throw new \Exception('Une erreur est survenue!');
                    } else {
                        $statement = $connection->prepare('UPDATE questions SET label=?, theme=? WHERE id=?');
                        $args = [$formParams['label'], $formParams['theme'], $id];

                        $possibleanswers = $this->getQuestionPossibleAnswers($connection, $question);

                        if ($statement->execute($args)) {
                            $i = 1;
                            foreach ($possibleanswers as $possibleanswer) {
                                $sql = 'UPDATE possibleanswers SET label = ?, correct = ? WHERE id = ?';
                                $args = [$formParams['answer' . strval($i)], $formParams['correct' . strval($i)], $possibleanswer['id']];
                                $statement = $connection->prepare($sql);

                                if (!$statement->execute($args)) {
                                    throw new \Exception('Une erreur est survenue!');
                                }

                                $i++;
                            }
                        }

                        header('Location: /questions');
                        exit(0);

                        throw new \Exception('Une erreur est survenue!');
                    }
                }
            } else {
                return $this->render('questions/persist.html.twig', ['msg' => 'Vous n\'êtes pas administrateur et ne pouvez donc pas acceder à cette page']);
            }
        } else {
            return $this->render('login.html.twig', ['msg' => 'Vous devez vous connecter pour accéder à cette page']);
        }
    }
}
