<?php

use App\Controllers\SiteController;

// actually the $router operators (:: and ->) are interchangeables
$router->get('/', [SiteController::class, 'home']);
$router::get('/about', [SiteController::class, 'about']);
$router::get('/posts', [SiteController::class, 'posts']);
$router::post('/users', [SiteController::class, 'create']);
$router::put('/users', [SiteController::class, 'update']);
$router::delete('/users', [SiteController::class, 'delete']);
$router::get('/search', [SiteController::class, 'search']);
