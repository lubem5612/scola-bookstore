<?php

return [
    'auth_model' => \Transave\ScolaBookstore\Http\Models\User::class,
    'app_env' => env('APP_ENV', 'development'),

    'role' => [
        1 => 'superAdmin',
        2 => 'admin',
        3 => 'publisher',
        4 => 'user'
    ],

    'user_type' => [
        1 => 'reviewer',
        2 => 'normal'
    ],

    'route' => [
        'prefix' => 'bookstore',
        'middleware' => 'api',
    ],

    'default_disk' => env('DEFAULT_DISK', 'local'),

    'storage_prefix' => env('STORAGE_PREFIX', 'bookstore'),


    'azure' => [
        'storage_url' => 'https://'.env('AZURE_STORAGE_NAME').'.blob.core.windows.net/'.env('AZURE_STORAGE_CONTAINER'),
        'id' => '.windows.net',
    ],

    's3' => [
        'storage_url' => 'https://'.env('AWS_BUCKET').'.s3.'.env('AWS_DEFAULT_REGION').'.amazonaws.com',
        'id' => 'amazonaws.com',
    ],

    'local' => [
        'storage_url' => '',
        'id' => '',
    ],

     'percentage_share' => env('BOOK_PERCENTAGE_SHARE', 50),
];


