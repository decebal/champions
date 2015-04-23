<?php

require('vendor/autoload.php');

$config = [
    'di' => require 'config/di.php',
];

$container = new League\Container\Container($config);