<?php
// config for wadephillips/
return [
    /*
     * The connection to your legacy ExpressionEngine2 forum.  Define the connection
     * settings for this database here.
     * You will need another database connection for your wordpress site, this connection
     * can be configured in the corcel.php config file.
     */
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
            /* If your EE forums have hidden or private forums that are only visible
             *  to specific user groups enter the user group ids of the non super admin groups here.
             * Categories and Forums only visible to a subset of these groups will be
             * hidden when they are transfered for review by a wordpress admin
             */
            'privateGroups' => ['6','9','11','14'],
        ],
    ],
    /*
     * Sets up the file systems for saving file attachments
     */
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
