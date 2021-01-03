<?php

namespace App\Action\User;

use App\Core\Controller\AbstractController;
use PDO;

class getPosition extends AbstractController
{
    public function __invoke(int $id)
    {
        $connexion = $this->getConnection();
        $sth = $connexion->prepare("SELECT * FROM users ORDER BY points");
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $key => $value) {
            if($value['id'] === strval($id)){
                $position = $key;
            }
        }

        $this->addHeader('Content-Type', 'application/json');

        return json_encode(["position" => $position + 1, "outOf" => count($result)]);
    }
}
