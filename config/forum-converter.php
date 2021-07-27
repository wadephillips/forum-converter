<?php
// config for wadelphillips/
return [
    'database' => [
        'connections' => [
            'legacy' => [
                'driver' => 'mysql',
                'url' => env('DATABASE_URL'),
                'host' => env('LEGACY_DB_HOST', '127.0.0.1'),
                'port' => env('LEGACY_DB_PORT', '3306'),
                'database' => env('LEGACY_DB_DATABASE', 'forge'),
                'username' => env('LEGACY_DB_USERNAME', 'forge'),
                'password' => env('LEGACY_DB_PASSWORD', ''),
                'unix_socket' => env('LEGACY_DB_SOCKET', ''),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => env('LEGACY_DB_PREFIX', ''),
                'prefix_indexes' => true,
                'strict' => true,
                'engine' => null,
                'options' => extension_loaded('pdo_mysql') ? array_filter([
                    PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
                ]) : [],
            ],
        ],
    ],
    'forum' => [
        'legacy' => [
            'privateGroups' => ['6','9','11','14'],
        ],
    ],
    'filesystems' => [
        'disks' => [
            'legacy_forum_attachment' => [
                'driver' => 'local',
                'root' => env('LEGACY_FORUM_ATTACHMENTS_PATH'),
            ],
            'wp_forum_attachment' => [
                'driver' => 'local',
                'root' => env('WP_FORUM_ATTACHMENTS_PATH'),
            ],
        ],
    ],
];
