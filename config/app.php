<?php

use Slim\App;

return (function() {
    $container = require __DIR__ . '/container.php';
    $routes = require __DIR__ . '/routes.php';
    $app = new App($container);
    $routes($app);
    return $app;
})();