<?php

use Slim\App;

return (function() {
    $container = require __DIR__ . '/container.php';
    $providers = require __DIR__ . '/providers.php';

    $app = new App($container);
    $providers($app);

    return $app;
})();