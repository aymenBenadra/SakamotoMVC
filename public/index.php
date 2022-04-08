<?php

use Core\Router;
use Core\Helpers\Request;
use Dotenv\Dotenv;

require_once '../vendor/autoload.php';

// Load Environment Variables
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

Router::load('../app/config/routes.php')->direct(new Request);
