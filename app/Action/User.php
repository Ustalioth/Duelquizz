<?php

namespace App\Action;

class User
{
    public function __invoke(string $userName)
    {
        //TODO: refactor this class using twig template
        return 'Bonjour ' . $userName;
    }
}

