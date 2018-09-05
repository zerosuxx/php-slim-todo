<?php

use Slim\Container;

return (function() {
    $config = include __DIR__ . '/config.php';

    $container = new Container($config);

    return $container;
})();