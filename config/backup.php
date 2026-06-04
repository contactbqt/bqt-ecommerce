<?php

return [

    'backup' => [

        /*
         * The name of this application. You can use this name to monitor
         * the backups of this application. So make sure it is unique.
         */
        'name' => env('APP_NAME', 'laravel-backup'),

        'source' => [

            'files' => [

                /*
                 * The list of directories and files that will be included in the backup.
                 */
                'include' => [
                    // base_path(),
                ],

                /*
                 * These directories and files will be excluded from the backup.
                 */
                'exclude' => [
                    base_path('vendor'),
                    base_path('node_modules'),
                ],

                /*
                 * Determines if symbols links should be followed.
                 */
                'follow_links' => false,

                /*
                 * Determines if it should avoid backing up files that were already
                 * backed up by a previous backup run.
                 */
                'ignore_unreadable_directories' => false,

                /*
                 * The maximum age of a file in days that will be included in the backup.
                 */
                'relative_path' => null,
            ],

            /*
             * The names of the connections to the databases that should be backed up.
             * MySQL, PostgreSQL, SQLite and Mongo are supported.
             *
             * The 'db-dump' block below contains options for the database dumps.
             */
            'databases' => [
                'mysql',
            ],
        ],

        // 'database_dump_compressor' => Spatie\DbDumper\Compressors\GzipCompressor::class,
        'database_dump_compressor' => null,

        'database_dump_file_extension' => '',

        'destination' => [

            /*
             * The filename prefix used for the backup zip file.
             */
            'filename_prefix' => '',

            /*
             * The disk names on which the backups will be stored.
             */
            'disks' => [
                'backup',
            ],
        ],

        /*
         * The directory where the temporary files will be stored.
         */
        'temporary_directory' => storage_path('app/backup'),

        /*
         * The password to be used for archive encryption.
         * Set to `null` to disable encryption.
         */
        'password' => null,

        /*
         * The encryption algorithm to be used for archive encryption.
         * You can set it to `Spatie\Backup\Tasks\Backup\Zip::ENCRYPTION_WZAES256`.
         */
        'encryption' => 'default',

        'tries' => 1,

        'retry_delay' => 2,

        'database_dump_filename_base' => env('BACKUP_DATABASE_DUMP_FILENAME_BASE', 'database'),

        'database_dump_file_timestamp_format' => 'Y-m-d-H-i-s',
    ],

    /*
     * You can get notified when specific events occur. Out of the box, only mail
     * and slack notifications are supported.
     */
    'notifications' => [

        'notifications' => [
            \Spatie\Backup\Notifications\Notifications\BackupHasFailedNotification::class => ['mail'],
            \Spatie\Backup\Notifications\Notifications\UnhealthyBackupWasFoundNotification::class => ['mail'],
            \Spatie\Backup\Notifications\Notifications\CleanupHasFailedNotification::class => ['mail'],
            \Spatie\Backup\Notifications\Notifications\BackupWasSuccessfulNotification::class => ['mail'],
            \Spatie\Backup\Notifications\Notifications\HealthyBackupWasFoundNotification::class => ['mail'],
            \Spatie\Backup\Notifications\Notifications\CleanupWasSuccessfulNotification::class => ['mail'],
        ],

        /*
         * Here you can specify the notifiable to which the notifications should be sent. The default
         * notifiable will use the variables specified in this config file.
         */
        'notifiable' => \Spatie\Backup\Notifications\Notifiable::class,

        'mail' => [
            'to' => 'your@example.com',

            'from' => [
                'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
                'name' => env('MAIL_FROM_NAME', 'Example'),
            ],
        ],

        'slack' => [
            'webhook_url' => '',

            /*
             * If this is set to null the default channel of the webhook will be used.
             */
            'channel' => null,

            'username' => null,

            'icon' => null,
        ],

        'discord' => [
            'webhook_url' => '',

            /*
             * If this is set to null the default channel of the webhook will be used.
             */
            'channel' => null,

            'username' => null,

            'avatar_url' => null,
        ],
    ],

    /*
     * Here you can specify which backups should be monitored.
     * If a backup does not meet the specified requirements the
     * UnhealthyBackupWasFound event will be fired.
     */
    'monitor_backups' => [
        [
            'name' => env('APP_NAME', 'laravel-backup'),
            'disks' => ['local'],
            'health_checks' => [
                \Spatie\Backup\Tasks\Monitor\HealthChecks\MaximumAgeInDays::class => 1,
                \Spatie\Backup\Tasks\Monitor\HealthChecks\MaximumStorageInMegabytes::class => 5000,
            ],
        ],
    ],

    'cleanup' => [
        /*
         * The strategy that will be used to cleanup old backups. The default strategy
         * will keep all backups for a certain amount of time. After that time period
         * it will only keep a daily backup for a certain amount of time. After that
         * period it will only keep weekly backups for a certain amount of time and
         * so on.
         *
         * No matter how many backups are removed, the last backup will always be kept.
         */
        'strategy' => \Spatie\Backup\Tasks\Cleanup\Strategies\DefaultStrategy::class,

        'default_strategy' => [

            /*
             * The number of days for which all backups must be kept.
             */
            'keep_all_backups_for_days' => 7,

            /*
             * The number of days for which daily backups must be kept.
             */
            'keep_daily_backups_for_days' => 16,

            /*
             * The number of days for which weekly backups must be kept.
             */
            'keep_weekly_backups_for_weeks' => 8,

            /*
             * The number of days for which monthly backups must be kept.
             */
            'keep_monthly_backups_for_months' => 4,

            /*
             * The number of yearly backups that must be kept.
             */
            'keep_yearly_backups_for_years' => 2,

            /*
             * After cleaning up backups remove the already deleted backup zip files
             * from every storage disk.
             */
            'delete_oldest_backups_when_using_more_megabytes_than' => 5000,
        ],
    ],
];
