<?php

return [

    'screenshots_folder' => storage_path('app/.eyes/screenshots'),
    'console_log_folder' => storage_path('app/.eyes/logs'),

    'drivers' => [
        'phantom' => [
            'enabled' => false,
            'port' => 8910,
            'bin' => '/usr/local/bin/phantomjs',
        ],

        'chrome' => [
            'enabled' => false,
            'port' => 9515,
        ]
    ],


];