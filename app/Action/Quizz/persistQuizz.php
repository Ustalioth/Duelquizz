<?php

namespace App\Action\Quizz;

use App\Core\Controller\AbstractController;
use App\Service\QuestionManager;
use PDO;

class persistQuizz extends AbstractController
{
    public function __invoke()
    {
        if ($this->getUser() !== null) {
            $connexion = $this->getConnection();
            $QuestionManager = new QuestionManager();
            $request = $this->getRequest();
            $data = $request->getParsedBody();

            $fail = null;

            $sth = $connexion->prepare("INSERT INTO quizzes (mode, user1, startAt) VALUES (?,?,?)");
            $params = [$data['mode'], $data['user1'], date("Y-m-d H:i:s")];


            if ($sth->execute($params)) {

                $idQuizz = ($connexion->lastInsertId());

                $sth = $connexion->prepare("SELECT * FROM questions WHERE theme = ?");
                $params = [$data['themeId']];

                if ($sth->execute($params)) {
                    $questions = $sth->fetchAll(PDO::FETCH_ASSOC);

                    $randIndex = array_rand($questions, 4);
                }

                $randQuestions = [];

                for ($i = 0; $i < count($randIndex); $i++) {
                    array_push($randQuestions, $questions[$randIndex[$i]]);
                }

                foreach ($randQuestions as $key => $question) {

                    $sth = $connexion->prepare("INSERT INTO question_quizz (question,quizz) VALUES (?,?)");
                    if (!$sth->execute([$question['id'], $idQuizz])) {
                        $fail = $sth->errorCode();
                    }
                }
            } else {
                $fail = $sth->errorCode();
            }

            $questions = $QuestionManager->getQuizzQuestions($idQuizz);
            $possibleanswers = [];
            foreach ($questions as $key => $question) {
                $possibleanswers[$key] = $QuestionManager->getQuestionPossibleAnswers($question['id']);
            }

            $this->addHeader('Content-Type', 'application/json');

            if ($fail !== null) {
                return json_encode(["fail" => $fail]);
            }

            return json_encode(["idQuizz" => $idQuizz, "questions" => $questions, "possibleanswers" => $possibleanswers]);
        } else {
            throw new \LogicException('Token absent!');
        }
    }
}
