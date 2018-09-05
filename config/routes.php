<?php

use Slim\App;

return function(App $app) {
    $app->get('/healthcheck', \TodoApp\Action\HealthCheckAction::class);
};