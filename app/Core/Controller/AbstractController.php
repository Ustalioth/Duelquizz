<?php

namespace App\Core\Controller;



use App\Core\Connection\Connection;
use App\Core\Template\TemplateEngine;
use App\Entity\User;
use App\Service\TokenManager;
use App\Service\UserManager;
use Error;
use PDO;
use Psr\Http\Message\ServerRequestInterface;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


abstract class AbstractController
{
    protected ServerRequestInterface $request;

    protected function render(string $templatePath, array $params = []): string
    {
        $engine = TemplateEngine::instance();

        return $engine->render($templatePath, $params);
    }

    protected function getConnection(): PDO
    {
        return Connection::getInstance();
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    public function setRequest(ServerRequestInterface $request): AbstractController
    {
        $this->request = $request;

        return $this;
    }

    public function setSessionData($email, $id, $firstName, $lastName, $isAdmin)
    {
        $_SESSION['email'] = $email;
        $_SESSION['id'] = $id;
        $_SESSION['firstName'] = $firstName;
        $_SESSION['lastName'] = $lastName;
        $_SESSION['isAdmin'] = $isAdmin;
    }

    public function setAuthCookie($id = null, $email = null, $password = null)
    {
        if ($id !== null) {
            setcookie('auth', $id . '-----' . sha1($email . $password), time() + 3600 * 24 * 3, '', '', false, true);
        } else {
            setcookie('auth', '', time() + 3600 * 24 * 3, '', '', false, true); // Pas de valeur donc on supprime le cookie (qui était invalide)
        }
    }

    public function getQuestion($id, $connection)
    {
        $sql = 'select * from questions WHERE id=?';
        $statement = $connection->prepare($sql);
        $statement->execute($id);

        $questions = $statement->fetch(\PDO::FETCH_ASSOC);

        if (false === $questions) {
            throw new \Exception('Question non trouvée!', 404);
        } else {
            return $questions;
        }
    }

    public function getAllthemes($connection)
    {
        $sql = 'SELECT * FROM themes';

        $statement = $connection->query($sql);
        $themes = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $themes;
    }

    public function getQuestionTheme($connection, $question)
    {
        $sql = 'SELECT * FROM themes WHERE themes.id = ?'; //On récupère le thème lié à une question
        $args = [$question['theme']];
        $statement = $connection->prepare($sql);

        if ($statement->execute($args)) {
            $theme = $statement->fetch();
            return $theme;
        } else {
            throw new \Exception('Theme correspondant introuvable!');
        }
    }

    public function getQuestionPossibleAnswers($connection, $question, $delete = false)
    {
        if ($delete === true) {
            $sql = 'DELETE FROM possibleanswers WHERE possibleanswers.question = ?'; //On récupère le thème lié à une question
            $args = [$question['id']];
            $statement = $connection->prepare($sql);
        } else {
            $sql = 'SELECT * FROM possibleanswers WHERE possibleanswers.question = ?'; //On récupère le thème lié à une question
            $args = [$question['id']];
            $statement = $connection->prepare($sql);
        }


        if ($statement->execute($args) && $delete === false) {
            $possibleanswers = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $possibleanswers;
        } else if ($statement->execute($args) && $delete === true) {
            return true;
        }
        throw new \Exception('Erreur SQL !');
    }

    public function getLatestQuestion($connection)
    {
        $statement  = $connection->prepare('SELECT * FROM questions ORDER BY id DESC LIMIT 1'); //On récupère le thème lié à une question

        if ($statement->execute()) {
            $question = $statement->fetch();
            return $question;
        } else {
            throw new \Exception('Theme correspondant introuvable!');
        }
    }

    public function getQuestionParamsAndRender($formParams, $msg, $themes, $theme, $id)
    {
        $question['label'] = $formParams['label'];
        $question['id'] = $id;
        $theme['id'] = $formParams['theme'];
        $possibleanswers = [];
        for ($i = 1; $i < 5; $i++) {
            $possibleanswers[$i]['label'] = $formParams['answer' . $i];
            $possibleanswers[$i]['correct'] = $formParams['correct' . strval($i)];
        }

        return $this->render('questions/persist.html.twig', ['info' => $msg, 'question' => $question, 'theme' => $theme, 'possibleanswers' => $possibleanswers, 'isAdmin' => true, 'correspondingTheme' => $theme, 'correspondingTheme' => $theme, 'themes' => $themes]);
    }

    public function addHeader(string $name, string $value)
    {
        header(sprintf('%s: %s', $name, $value));
    }

    public function getUser(): ?User
    {
        $request = $this->getRequest();

        if ($request->hasHeader('Authorization')) {
            $token = $request->getHeader('Authorization')[0];
            $data = (new TokenManager())->decode($token);

            if ($data['exp'] > time()) {
                return (new UserManager())->findOneByEmail($data['email']);
            } else {
                throw new \LogicException('Token Expiré!');
            }
        }

        return null;
    }

    public function sendMail(User $user)
    {
        ob_start();
        // Instantiation and passing true enables exceptions
        $mail = new PHPMailer(true);
        try {
            $mail->CharSet = 'UTF-8';
            $mail->SMTPDebug = 3; //Set PHPMailer to use SMTP.
            $mail->isSMTP(); //Set SMTP host name
            $mail->Host = "smtp.gmail.com"; //Set this to true if SMTP host requires authentication to send email
            $mail->SMTPAuth = true; //Provide username and password
            $mail->Username = "rfrancois6.ServerSTMP@gmail.com";
            $mail->Password = "noreply@STMP"; //If SMTP requires TLS encryption then set it
            $mail->SMTPSecure = "tls"; //Set TCP port to connect to
            $mail->Port = 587; //Recipients
            $mail->setFrom('rfrancois6.ServerSTMP@gmail.com', 'DuelQuizz');
            $mail->addAddress($user->getEmail(), $user->getFirstName() . ' ' . $user->getLastName()); // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Bienvenue sur DuelQuizz !';
            $mail->Body = '<header><h1>Bienvenue sur DuelQuizz !</h1></header><p>Grâce à votre inscription vous avez accès au quizz solo et duel. Amusez-vous bien !</p>';
            $mail->AltBody = 'Bienvenue sur DuelQuizz !Grâce à votre inscription vous avez accès au quizz solo et duel. Amusez-vous bien !';
            $mail->send();
            echo 'Message has been sent'; // redirection page d'accueil//
            //header();
            ob_get_clean();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: . $mail->ErrorInfo";
        }
    }
}
