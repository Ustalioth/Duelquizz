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
            $data = $request->getBody()->getContents();

            parse_str($data, $myData);

            //var_dump($data);
            var_dump($myData);
            die;

            $connexion = $this->getConnection();

            $sth = $connexion->prepare("UPDATE users SET points = points + ? WHERE id = ?");

            if ($sth->execute([$myData['points'], $user->getId()])) {
                return true;
            } else {
                return json_encode(["erreurSQL" => $sth->errorCode()]);
            }


            $this->addHeader('Content-Type', 'application/json');
        } else {
            throw new \LogicException('Token absent!');
        }
    }
}
