<?php

return [
    'paths' => [
        'migrations' => 'database/migrations',
    ],
    'environments' => [
        'development' => [
            'adapter' => 'sqlite',
            'database' => 'database',
            'name' => 'database/development',
            'charset' => 'utf8',
        ],
    ],
];
