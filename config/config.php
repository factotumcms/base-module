<?php

use Spatie\Image\Enums\CropPosition;
use Spatie\Image\Enums\Fit;
use Wave8\Factotum\Base\Enums\Disk;
use Wave8\Factotum\Base\Enums\Locale;
use Wave8\Factotum\Base\Enums\Media\MediaPreset;

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
        'default' => env('APP_LOCALE', Locale::EN->value),
    ],

    'media' => [
        'disk' => env('FILESYSTEM_DISK', Disk::PUBLIC->value), // Options: public, s3, local. (must be configured in config/filesystems.php)
        'base_path' => env('MEDIA_BASE_PATH', 'media'),
        'conversions_path' => 'conversions',
        'presets' => [
            MediaPreset::PROFILE_PICTURE->value => [
                'suffix' => '_profile',
                'optimize' => true,
                'resize' => null,
                'fit' => [
                    'method' => Fit::Crop->value,
                    'width' => 300,
                    'height' => 300,
                ],
                'crop' => [
                    'width' => 300,
                    'height' => 300,
                    'position' => CropPosition::Center->value,
                ],
            ],
            MediaPreset::THUMBNAIL->value => [
                'suffix' => '_thumb',
                'optimize' => true,
                'resize' => [
                    'width' => 300,
                    'height' => 300,
                ],
                'fit' => [
                    'method' => Fit::Crop->value,
                    'width' => 300,
                    'height' => 300,
                ],
                'crop' => [
                    'width' => 300,
                    'height' => 300,
                    'position' => CropPosition::Center->value,
                ],
            ],
        ],

    ],

    'pagination' => [
        'type' => 'simple', // Options: simple, standard
        'per_page' => 15,
        'default_order_by' => 'id',
        'default_order_direction' => 'DESC', // Options: ASC, DESC
    ],
];
