<?php

require 'vendor/autoload.php';

use Slim\Factory\AppFactory;

$app = AppFactory::create();

require __DIR__ . '/src/routes/users.php';
require __DIR__ . '/src/routes/messages.php';

$app->run();
