<?php

namespace App\Action\Answer;

use App\Core\Controller\AbstractController;

class PersistAnswer extends AbstractController
{
    public function __invoke()
    {
        if ($this->getUser() !== null) {
            $connexion = $this->getConnection();
            $request = $this->getRequest();
            $data = $request->getBody()->getContents();

            parse_str($data, $myData);

            var_dump($myData);
            die;

            $myData = array_map('intval', $myData);

            if ($myData['user'] === 1) {
                $sth = $connexion->prepare("UPDATE question_quizz SET user1 = ? WHERE quizz = ? AND question = ?");
            } else {
                $sth = $connexion->prepare("UPDATE question_quizz SET user2 = ? WHERE quizz = ? AND question = ?");
            }

            if ($sth->execute([$myData['isRight'], $myData['quizz'], $myData['question']])) { //isRight = 0 si mauvaise réponse et isRight = 1 si bonne réponse
                return json_encode(['ok' => 'ok']);
            } else {
                return $sth->errorCode();
            }
        } else {
            throw new \LogicException('Token absent!');
        }
    }
}
