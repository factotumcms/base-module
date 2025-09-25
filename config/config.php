<?php

use Wave8\Factotum\Base\Enums\Disk;
use Wave8\Factotum\Base\Enums\Locale;

return [
    'module_name' => 'Base',
    'admin_default' => [
        'email' => env('ADMIN_DEFAULT_EMAIL', 'admin@gmail.com'),
        'password' => env('ADMIN_DEFAULT_PASSWORD', 'password'),
        'username' => env('ADMIN_DEFAULT_PASSWORD', 'admin'),
        'first_name' => env('ADMIN_DEFAULT_FIRST_NAME', 'Admin'),
        'last_name' => env('ADMIN_DEFAULT_LAST_NAME', 'Default'),
    ],

    'auth' => [
        'type' => 'basic', // Options: basic
        'basic_identifier' => 'email', // Options: email, username
    ],

    'locale' => [
        'default' => env('APP_LOCALE', Locale::EN),
    ],

    'media' => [
        'disk' => env('FILESYSTEM_DISK', Disk::PUBLIC), // Options: public, s3, local. (must be configured in config/filesystems.php)
        'base_path' => env('MEDIA_BASE_PATH', 'media'),
        'profile_picture_preset' => [
            'width' => 300,
            'height' => 300,
            'fit' => 'crop',
            'position' => 'center',
        ],
        'thumbnail_preset' => [
            'width' => 300,
            'height' => 300,
            'fit' => 'crop',
            'position' => 'center',
        ],
    ],
];
