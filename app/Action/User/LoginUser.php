<?php

namespace App\Action\User;

use App\Core\Controller\AbstractController;
use App\Service\TokenManager;
use App\Service\UserManager;

class LoginUser extends AbstractController
{
    public function __invoke()
    {
        $tokenManager = new TokenManager();
        $userManager = new UserManager();

        $request = $this->getRequest();
        $data = $request->getParsedBody();

        $user = $userManager->findOneByEmail($data['email']);

        if (!$user) {
            throw new \Exception('Identifiants non valides!', 404);
        }

        if (!password_verify($data['password'], $user->getPassword())) {
            throw new \Exception('Identifiants non valides!', 404);
        }

        $token = $tokenManager->encode([
            "iss" => "http://rest",
            "aud" => "http://rest",
            "iat" => time(),
            "exp" => time() + 86400,
            'email' => $data['email']
        ]);

        $this->addHeader('Content-Type', 'application/json');

        return json_encode([
            'token' => $token
        ]);
    }
}
