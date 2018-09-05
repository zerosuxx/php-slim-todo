<?php

use Slim\App;
use TodoApp\ConfigProvider;

return function(App $app) {
    new ConfigProvider($app);
};