<?php

namespace App\Core\Controller;

use App\Core\Connection\Connection;
use App\Core\Template\TemplateEngine;
use PDO;
use Psr\Http\Message\ServerRequestInterface;

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
}
