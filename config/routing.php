<?php

use App\Action\Login;
use App\Action\Home;
use App\Action\Disconnect;
use App\Action\Admin\AdminList;
use App\Action\Admin\Delete as DeleteAdmin;
use App\Action\Admin\Persist as PersistAdmin;
use App\Action\Theme\ThemeList;
use App\Action\Theme\Delete as DeleteTheme;
use App\Action\Theme\Persist as PersistTheme;
use App\Action\User;
use App\Action\UserList;
use App\Core\Routing\Route;

return [
    new Route('/', Home::class, 'GET'),
    new Route('/user/{userName}', User::class, 'GET'),
    new Route('/themes', ThemeList::class, 'GET'),
    new Route('/admins', AdminList::class, 'GET'),
    new Route('/users', UserList::class, 'GET'),
    new Route('/disconnect', Disconnect::class, 'GET'),
    new Route('/login', Login::class, ['GET', 'POST']),
    new Route('/admin[/{id}]', PersistAdmin::class, ['GET', 'POST']),
    new Route('/delete-admin/{id}', DeleteAdmin::class, ['GET', 'DELETE']),
    new Route('/theme[/{id}]', PersistTheme::class, ['GET', 'POST']),
    new Route('/delete-theme/{id}', DeleteTheme::class, ['GET', 'DELETE']),
];
