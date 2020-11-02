<?php

namespace App\Action;

class User
{
    public function __invoke(string $userName)
    {
        return 'Bonjour ' . $userName;
    }
}

