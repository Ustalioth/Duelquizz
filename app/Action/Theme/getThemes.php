<?php

namespace App\Action\Theme;

use App\Core\Controller\AbstractController;
use Nyholm\Psr7\Response;
use PDO;


class getThemes extends AbstractController
{
    public function __invoke()
    {
        if ($this->getUser() !== null) {
            $connexion = $this->getConnection();
            $request = $this->getRequest();
            $data = $request->getQueryParams();

            $sth = $connexion->prepare("SELECT * FROM themes");
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);

            if ($data['returnType'] === 'JSON') {
                $this->addHeader('Content-Type', 'application/json');
                return json_encode([
                    "themes" => $result
                ]);
            } else {
                $response = new Response($this->render('themes.xml.twig'));
                $this->addHeader('Content-Type', 'application/xml');
                return $response;
            }
        } else {
            throw new \LogicException('Token absent!');
        }
    }
}
