<?php
require '../vendor/autoload.php';

if (function_exists('opcache_reset')) {
    opcache_reset();
}

use Slim\Factory\AppFactory;

$app = AppFactory::create();

require '../src/Presentation/Routes/routes.php';

$app->run();
