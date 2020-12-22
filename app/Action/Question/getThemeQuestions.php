<?php

namespace App\Action\Question;

use App\Core\Controller\AbstractController;
use App\Service\TokenManager;
use App\Service\UserManager;

class getThemeQuestions extends AbstractController
{
    public function __invoke(int $id)
    {
        $connexion = $this->getConnection();
        $sth = $connexion->prepare("SELECT * FROM questions WHERE theme = ?");
        $sth->execute([$id]);
        $result = $sth->fetchAll();

        var_dump($result);
        die;

        return json_encode([
            "questions" => $result
        ]);
    }
}
