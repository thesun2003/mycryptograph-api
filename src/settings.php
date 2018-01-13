<?php

if (file_exists(__DIR__ . '/local_settings.php')) {
    include_once __DIR__ . '/local_settings.php';
}

$app_settings = [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        // API
        'api' => [
            'poloniex' => [
                'public_key' => getenv('POLONIEX_API_KEY') ?? 'XXX',
                'private_key' => getenv('POLONIEX_SECRET') ?? 'XXX',
            ]
        ]
    ],
];

return $app_settings;
