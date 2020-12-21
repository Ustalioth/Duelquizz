<?php

namespace App\Action\Theme;

use App\Core\Controller\AbstractController;
use App\Service\TokenManager;
use App\Service\UserManager;

class getThemes extends AbstractController
{
    public function __invoke()
    {
        $connexion = $this->getConnection();
        $sth = $connexion->prepare("SELECT * FROM themes");
        $sth->execute();
        $result = $sth->fetchAll();

        return json_encode([
            "themes" => $result
        ]);
    }
}
