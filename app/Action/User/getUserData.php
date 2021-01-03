<?php

namespace App\Action\User;

use App\Core\Controller\AbstractController;
use App\Serializer\ObjectSerializer;

class getUserData extends AbstractController
{
    public function __invoke()
    {
        if ($this->getUser() !== null) {
            $request = $this->getRequest();
            $data = $request->getQueryParams();

            $user = $this->getUser();
            $serializer = new ObjectSerializer;

            if (isset($data['returnType']) && $data['returnType'] === "XML") {
                $this->addHeader('Content-Type', 'application/xml');
                return $this->render('users/users.xml.twig', ['user' => $user]);
            } else {
                $this->addHeader('Content-Type', 'application/json');
                return $serializer->toJson($user);
            }
        } else {
            throw new \LogicException('Token absent!');
        }
    }
}
