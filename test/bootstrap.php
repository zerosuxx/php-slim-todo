<?php

use Test\SlimTestCase;

require_once dirname(__DIR__) . '/config/bootstrap.php';

SlimTestCase::setGlobalApp(require_once dirname(__DIR__) . '/config/app.php');