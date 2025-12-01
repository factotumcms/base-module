<?php

use Spatie\Image\Enums\CropPosition;
use Spatie\Image\Enums\Fit;
use Wave8\Factotum\Base\Enums\Disk;
use Wave8\Factotum\Base\Enums\Locale;
use Wave8\Factotum\Base\Enums\Media\MediaAction;
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
        'password_expiration_days' => env('PASSWORD_EXPIRATION_DAYS', 30),
        'password_prune_keep' => env('PASSWORD_PRUNE_KEEP', 5),
    ],

    'locale' => [
        'default' => env('APP_LOCALE', Locale::EN->value),
    ],

    'media' => [
        'disk' => env('FILESYSTEM_DISK', Disk::PUBLIC->value), // Options: public, s3, local. (must be configured in config/filesystems.php)
        'base_path' => env('MEDIA_BASE_PATH', 'media'),
        'conversions_path' => 'conversions',
        'presets' => [
            MediaPreset::USER_AVATAR->value => [
                'suffix' => '_avatar',
                'actions' => [
                    MediaAction::FIT->value => [
                        'method' => Fit::Crop->value,
                        'width' => 300,
                        'height' => 300,
                    ],
                    MediaAction::CROP->value => [
                        'width' => 300,
                        'height' => 300,
                        'position' => CropPosition::Center->value,
                    ],
                    MediaAction::OPTIMIZE->value => [],
                ],
            ],
            MediaPreset::THUMBNAIL->value => [
                'suffix' => '_thumb',
                'actions' => [
                    MediaAction::RESIZE->value => [
                        'width' => 300,
                        'height' => 300,
                    ],
                    MediaAction::FIT->value => [
                        'method' => Fit::Crop->value,
                        'width' => 300,
                        'height' => 300,
                    ],
                    MediaAction::CROP->value => [
                        'width' => 300,
                        'height' => 300,
                        'position' => CropPosition::Center->value,
                    ],
                    MediaAction::OPTIMIZE->value => [],
                ],
            ],
        ],
    ],

    'pagination' => [
        'type' => 'simple', // Options: simple, standard
        'per_page' => 15,
        'default_order_by' => 'id',
        'default_order_direction' => 'desc', // Options: asc, desc
    ],
];
