<?php

use App\Core\Router;
use Dotenv\Dotenv;

// support for .env variables
$dotenv = Dotenv::createImmutable(ROOT_DIR);
$dotenv->safeLoad();

// router
$router = Router::getInstance();

// routes
require ROOT_DIR . '/src/routes.php';

return $router;
