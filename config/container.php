<?php

use Slim\Container;

return (function() {
    $config = include __DIR__ . '/config.php';
    $dependencies = include __DIR__ . '/dependencies.php';

    $container = new Container($config);
    $dependencies($container);

    return $container;
})();