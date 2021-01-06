<?php

namespace App\Action\Quizz;

use App\Core\Controller\AbstractController;
use App\Service\QuestionManager;

class quizzHandleSecondPlayer extends AbstractController
{
    public function __invoke()
    {

        if ($this->getUser() !== null) {
            $connexion = $this->getConnection();
            $QuestionManager = new QuestionManager();
            $request = $this->getRequest();
            $data = $request->getQueryParams();

            $fail = null;

            $user2 = $data['user2'];
            $idQuizz = $data['idQuizz'];

            $sth = $connexion->prepare("UPDATE quizzes SET user2 = ? WHERE id = ?");

            if ($sth->execute([$user2, $idQuizz])) {
                $questions = $QuestionManager->getQuizzQuestions($idQuizz);

                $possibleanswers = [];

                foreach ($questions as $key => $question) {
                    $possibleanswers[$key] = $QuestionManager->getQuestionPossibleAnswers($question['id']);
                }
            } else {
                $fail = $sth->errorCode();
            }

            $this->addHeader('Content-Type', 'application/json');

            if ($fail !== null) {
                return json_encode(["fail" => $fail]);
            }

            return json_encode(["questions" => $questions, "possibleanswers" => $possibleanswers]);
        } else {
            throw new \LogicException('Token absent!');
        }
    }
}
