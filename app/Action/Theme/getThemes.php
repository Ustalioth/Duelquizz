<?php

namespace App\Action\Theme;

use App\Core\Controller\AbstractController;
use PDO;


class getThemes extends AbstractController
{
    public function __invoke()
    {

        if ($this->getUser() !== null) {
            $connexion = $this->getConnection();
            $sth = $connexion->prepare("SELECT * FROM themes");
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);

            return json_encode([
                "themes" => $result
            ]);
        } else {
            throw new \LogicException('Token absent!');
        }
    }
}
