<?php

use App\Action\Admin\AdminList;
use App\Action\Admin\Delete as DeleteAdmin;
use App\Action\Admin\Persist as PersistAdmin;
use App\Action\Login;
use App\Action\Disconnect;
use App\Action\Home;
use App\Action\User;
use App\Core\Routing\Route;

return [
    new Route('/', Home::class, 'GET'),
    new Route('/user/{userName}', User::class, 'GET'),

    new Route('/admins', AdminList::class, 'GET'),
    new Route('/disconnect', Disconnect::class, 'GET'),
    new Route('/login', Login::class, ['GET', 'POST']),
    new Route('/admin[/{id}]', PersistAdmin::class, ['GET', 'POST']),
    new Route('/delete-admin/{id}', DeleteAdmin::class, ['GET', 'DELETE']),
];
