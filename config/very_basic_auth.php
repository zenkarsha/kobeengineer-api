<?php

return [
    'user'              => env('ADMIN_USERNAME', 'admin'),
    'password'          => env('ADMIN_PASSWORD', '0000'),
    'envs'              => [
        'local',
        'dev',
        'development',
        'staging',
        'production',
        'testing'
    ],
    'error_message'     => 'Permission denied (550)'
];
