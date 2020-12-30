<?php

namespace App\Action\User;

use App\Core\Controller\AbstractController;
use App\Service\TokenManager;
use App\Service\UserManager;

class CheckToken extends AbstractController
{
    public function __invoke()
    {
        $tokenManager = new TokenManager();
        $userManager = new UserManager();

        $request = $this->getRequest();
        $data = $request->getParsedBody();

        $token = $tokenManager->decode(
            $data['token']
        );

        $user = $userManager->findOneByEmail($token['email']);

        $returned['email'] = $user->getEmail();
        $returned['firstName'] = $user->getFirstName();
        $returned['lastName'] = $user->getLastName();
        $returned['points'] = $user->getPoints();
        $returned['id'] = $user->getId();

        $this->addHeader('Content-Type', 'application/json');

        return json_encode(["user" => $returned]);
    }
}
