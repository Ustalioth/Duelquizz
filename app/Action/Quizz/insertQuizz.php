<?php

namespace App\Action\Quizz;

use App\Core\Controller\AbstractController;
use App\Service\TokenManager;
use App\Service\UserManager;

class insertQuizz extends AbstractController
{
    public function __invoke()
    {
        $connexion = $this->getConnection();

        $request = $this->getRequest();
        $data = $request->getParsedBody();

        $fail = null;

        $sth = $connexion->prepare("INSERT INTO quizzes (mode, user1, startAt) VALUES (?,?,?)");
        $params = [$data['mode'], $data['user1'], $data['startAt']];
        if ($sth->execute($params)) {
            $sth->execute($params);
        } else {
            $fail = $sth->errorCode();
        }

        // $id = ($connexion->lastInsertId());

        // $questions = json_decode($data['questions']);

        // foreach ($questions as  $question) {
        //     $sth = $connexion->prepare("INSERT INTO questionxquizz (question,quizz) VALUES (?,?)");
        //     if ($sth->execute($question['id'], $id)) {
        //         $sth->execute($question['id'], $id);
        //     } else {
        //         $fail = $sth->errorCode();
        //     }
        // }

        var_dump(json_encode([
            "fail" => $fail
        ]));
        die;

        return json_encode([
            "fail" => $fail
        ]);
    }
}
