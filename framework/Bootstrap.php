<?php

use Achraf\framework\Config\Config;
use Achraf\framework\Database\Database;
use Dotenv\Dotenv;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger as MonologLogger;
use Predis\Client as RedisClient;
use Twig\Loader\FilesystemLoader;

// load .env file
if (!file_exists(BASE_PATH.'/.env')) {
    throw new Exception('Missing .env file in'.BASE_PATH.'/.env');
}
Dotenv::createImmutable(BASE_PATH)->load();

// bind dependencies
app()->bind(Config::class, fn() => new Config(require BASE_PATH.'/src/Config/app.php'));
app()->get(Database::class)->boot();
app()->bind(RedisClient::class, fn() => new RedisClient([
    'scheme' => config('REDIS_SCHEME'),
    'host' => config('REDIS_HOST'),
    'port' => config('REDIS_PORT'),
])
);
app()->bind(MonologLogger::class, fn() => new MonologLogger('app'));
app()->bind(StreamHandler::class, fn() => new StreamHandler(config('LOGS_PATH'), Level::Debug));
app()->bind(FilesystemLoader::class, fn() => new FilesystemLoader(config('VIEWS_PATH')));
