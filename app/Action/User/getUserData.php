<?php

namespace App\Action\User;

use App\Core\Controller\AbstractController;
use App\Serializer\ObjectSerializer;

class getUserData extends AbstractController
{
    public function __invoke()
    {
        if ($this->getUser() !== null) {
            $user = $this->getUser();
            $serializer = new ObjectSerializer;

            return $serializer->toJson($user);
        } else {
            throw new \LogicException('Token absent!');
        }
    }
}
