<?php

require_once realpath('vendor/autoload.php');

const ROOT_DIR = __DIR__;
const APP_DIR = '/Supabase';

$router = require __DIR__ . '/bootstrap/app.php';

$router->dispatch(parse_url(
	$_SERVER['REQUEST_URI'],
	PHP_URL_PATH
));
