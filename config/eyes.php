<?php

return [

    'browsers' => [
        'phantom' => [
            'enabled' => false,
            'port' => 8910,
            'bin' => '/usr/local/bin/phantomjs',
            'screenshots_folder' => storage_path('app/.eyes/phantom/screenshots'),
            'console_log_folder' => storage_path('app/.eyes/phantom/logs'),
        ],

        'chrome' => [
            'enabled' => false,
            'port' => 9515,
            'screenshots_folder' => storage_path('app/.eyes/chrome/screenshots'),
            'console_log_folder' => storage_path('app/.eyes/chrome/logs'),
        ]

    ]
];