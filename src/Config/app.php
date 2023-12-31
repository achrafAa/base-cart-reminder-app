<?php

return [
    'APP_NAME' => $_ENV['APP_NAME'] ?? 'Achraf',
    'APP_URL' => $_ENV['APP_URL'] ?? 'localhost',
    'APP_ENV' => $_ENV['APP_ENV'] ?? 'production',

    'DB_HOST' => $_ENV['DB_HOST'] ?? 'localhost',
    'DB_DATABASE' => $_ENV['DB_DATABASE'] ?? 'database',
    'DB_USERNAME' => $_ENV['DB_USERNAME'] ?? 'user',
    'DB_PASSWORD' => $_ENV['DB_PASSWORD'] ?? 'password',
    'DB_DRIVER' => $_ENV['DB_CONNECTION'] ?? 'mysql',
    'DB_CHARSET' => 'utf8',
    'DB_COLLATION' => 'utf8_unicode_ci',
    'DB_PREFIX' => $_ENV['DB_PREFIX'] ?? '',

    'MAIL_HOST' => $_ENV['MAIL_HOST'] ?? 'mailhog',
    'MAIL_PORT' => $_ENV['MAIL_PORT'] ?? '1025',
    'MAIL_FROM_ADDRESS' => $_ENV['MAIL_FROM_ADDRESS'] ?? 'email@cart-service.com',

    'REDIS_SCHEME' => 'tcp',
    'REDIS_HOST' => 'redis',
    'REDIS_PORT' => 6379,

    'VIEWS_PATH' => BASE_PATH.'/views',
    'LOGS_PATH' => BASE_PATH.'/logs/app.log',
];
