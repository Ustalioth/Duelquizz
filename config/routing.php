<?php

use App\Action\Admin\AdminList;
use App\Action\Admin\Delete as DeleteAdmin;
use App\Action\Admin\Persist as PersistAdmin;
use App\Action\Home;
use App\Action\User;
use App\Core\Routing\Route;

return [
    new Route('/', Home::class, 'GET'),
    new Route('/user/{userName}', User::class, 'GET'),

    new Route('/admins', AdminList::class, 'GET'),
    new Route('/admin[/{adminId}]', PersistAdmin::class, ['GET', 'POST']),
    new Route('/delete-admin/{adminId}', DeleteAdmin::class, ['GET', 'DELETE']),
];

