<?php

use App\Action\Home;
use App\Action\User;
use App\Core\Routing\Route;

return [
    new Route('/', Home::class, 'GET'),
    new Route('/user/{userName}', User::class, 'GET'),
];

