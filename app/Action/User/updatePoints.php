<?php

namespace App\Action\User;

use App\Core\Controller\AbstractController;
use App\Serializer\ObjectSerializer;

class UpdatePoints extends AbstractController
{
    public function __invoke()
    {
        if ($this->getUser() !== null) {
            $user = $this->getUser();
            $request = $this->getRequest();
            $data = $request->getQueryParams();

            $connexion = $this->getConnection();

            $sth = $connexion->prepare("UPDATE users SET points = points + ? WHERE id = ?");

            $this->addHeader('Content-Type', 'application/json');

            if ($sth->execute([$data['points'], $user->getId()])) {
                return true;
            } else {
                return json_encode(["erreurSQL" => $sth->errorCode()]);
            }
        } else {
            throw new \LogicException('Token absent!');
        }
    }
}
