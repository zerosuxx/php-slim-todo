<?php

use SlimSkeleton\AppBuilder;
use TodoApp\ConfigProvider;

return function(AppBuilder $appBuilder) {
    $appBuilder->addProvider(new ConfigProvider());
};