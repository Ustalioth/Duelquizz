<?php

namespace App\Service;

use App\Core\Connection\Connection;
use App\Entity\Question;
use PDO;

class QuestionManager
{
    function getThemeQuestions($id)
    {
        $connexion = Connection::getInstance();
        $sth = $connexion->prepare("SELECT * FROM questions WHERE theme = ?");
        $sth->execute([$id]);
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);

        return json_encode([
            "questions" => $result
        ]);
    }

    function getQuizzQuestions($id)
    {
        $connexion = Connection::getInstance();
        $sth = $connexion->prepare("SELECT * FROM questions q LEFT OUTER JOIN question_quizz as qq ON q.id = qq.question WHERE qq.quizz = ?");
        $sth->execute([$id]);
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    function getQuestionPossibleAnswers($id)
    {
        $connexion = Connection::getInstance();
        $sth = $connexion->prepare("SELECT * FROM possibleanswers WHERE question = ?");
        $sth->execute([$id]);
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
}
