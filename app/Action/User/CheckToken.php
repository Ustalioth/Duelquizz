<?php

namespace App\Action\User;

use App\Core\Controller\AbstractController;
use App\Service\TokenManager;

class CheckToken extends AbstractController
{
    public function __invoke()
    {
        $tokenManager = new TokenManager();

        $request = $this->getRequest();
        $data = $request->getParsedBody();

        $token = $tokenManager->decode(
            $data['token']
        );

        $this->addHeader('Content-Type', 'application/json');

        if ($token['exp'] - time() < 0) {
            return json_encode(["res" => "Token expiré, veuillez vous reconnecter"]);
        } else {
            return json_encode(["res" => "OK"]);
        }
    }
}
