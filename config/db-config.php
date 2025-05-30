<?php

use Config\Environment;

require __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/environment/environment.php';
$environment = new Environment();
$environment->loadEnv(__DIR__ . '/../.env');
$env = 'asdad';
return [
    'database' => [
        'host' => getenv('DB_HOST'),
        'port' => getenv('DB_PORT'),
        'dbname' => getenv('DB_NAME'),
        'user' => getenv('DB_USER'),
        'password' => getenv('DB_PASSWORD')
    ]
];
