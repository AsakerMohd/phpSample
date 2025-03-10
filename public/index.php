<?php

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use MyApp\Routes;

// Create Slim App
$app = AppFactory::create();

// Include routes
Routes::register($app);

// Run Slim (this processes the incoming HTTP request)
$app->run();
