<?php

use Dotenv\Dotenv;

define('APP_ROOT', dirname(__DIR__));

require_once APP_ROOT . '/vendor/autoload.php';

(new Dotenv(__DIR__ . '/environment', '.env.' . getenv('APPLICATION_ENV')))->load();