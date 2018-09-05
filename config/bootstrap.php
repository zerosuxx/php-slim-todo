<?php

use Dotenv\Dotenv;

require_once dirname(__DIR__) . '/vendor/autoload.php';

(new Dotenv(__DIR__ . '/environment', '.env.' . getenv('APPLICATION_ENV')))->load();