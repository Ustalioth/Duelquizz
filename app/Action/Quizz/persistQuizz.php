<?php

namespace App\Action\Quizz;

use App\Core\Controller\AbstractController;
use App\Service\QuizzManager;
use App\Serializer\ObjectSerializer;
use PDO;

class persistQuizz extends AbstractController
{
    public function __invoke()
    {
        $connexion = $this->getConnection();
        $QuizzManager = new QuizzManager();
        $serializer = new ObjectSerializer();

        $request = $this->getRequest();
        $data = $request->getParsedBody();

        $fail = null;

        $sth = $connexion->prepare("INSERT INTO quizzes (mode, user1, startAt) VALUES (?,?,?)");
        $params = [$data['mode'], $data['user1'], date("Y-m-d H:i:s")];
          
        
        if ($sth->execute($params)) {

            $idQuizz = ($connexion->lastInsertId());

            $sth = $connexion->prepare("SELECT * FROM questions WHERE theme = ?");
            $params = [$data['themeId']];

            if($sth->execute($params)){
                $questions = $sth->fetchAll(PDO::FETCH_ASSOC);

                $randIndex = array_rand($questions, 4);
            }

            $randQuestions = [];

            for ($i=0; $i < count($randIndex); $i++) { 
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

        $quizz = $QuizzManager->findOneById($idQuizz);

        $this->addHeader('Content-Type', 'application/json');

        if($fail === null){
            return json_encode(["fail" => $fail]);
        }

        return $serializer->toJson($quizz);
        //return json_encode(["idQuizz" => $idQuizz]);
    }
}
