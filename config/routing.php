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
use App\Action\Theme\getThemes as getThemes;
use App\Action\User\User;
use App\Action\User\UserList;
use App\Action\User\LoginUser;
use App\Action\Quizz\QuizzList;
use App\Action\Quizz\insertQuizz;
use App\Action\Question\QuestionList;
use App\Action\Question\Persist as PersistQuestion;
use App\Action\Question\Delete as DeleteQuestion;
use App\Action\Question\getThemeQuestions;
use App\Action\Quizz\Search;
use App\Core\Routing\Route;

return [
    new Route('/', Home::class, 'GET'),
    new Route('/user/{userName}', User::class, 'GET'),
    new Route('/themes', ThemeList::class, 'GET'),
    new Route('/admins', AdminList::class, 'GET'),
    new Route('/questions', QuestionList::class, 'GET'),
    new Route('/question[/{id}]', PersistQuestion::class, ['GET', 'POST']),
    new Route('/delete-question/{id}', DeleteQuestion::class, ['GET', 'DELETE']),
    new Route('/users', UserList::class, 'GET'),
    new Route('/quizzes', QuizzList::class, ['POST', 'GET']),
    new Route('/disconnect', Disconnect::class, 'GET'),
    new Route('/login', Login::class, ['GET', 'POST']),
    new Route('/admin[/{id}]', PersistAdmin::class, ['GET', 'POST']),
    new Route('/delete-admin/{id}', DeleteAdmin::class, ['GET', 'DELETE']),
    new Route('/theme[/{id}]', PersistTheme::class, ['GET', 'POST']),
    new Route('/delete-theme/{id}', DeleteTheme::class, ['GET', 'DELETE']),


    //ROUTES DE L'API
    new Route('/api/user/login', LoginUser::class, ['POST']),
    new Route('/api/user/themes', getThemes::class, ['GET']),
    new Route('/api/user/themeQuestions/{id}', getThemeQuestions::class, ['GET']),
    new Route('/api/user/insertQuizz', insertQuizz::class, ['POST']),
];
