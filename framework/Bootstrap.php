<?php

use Achraf\framework\Config\Config;
use Achraf\framework\Mailer\Mailer;
use Achraf\framework\Queue\Queue;
use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as DBCapsule;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger as MonologLogger;
use PHPMailer\PHPMailer\PHPMailer;
use Predis\Client as RedisClient;
use Twig\Loader\FilesystemLoader;

error_reporting(E_ALL);
set_error_handler('errorHandler');

// load .env file
if (! file_exists(BASE_PATH.'/.env')) {
    throw new Exception('Missing .env file in'.BASE_PATH.'/.env');
}
Dotenv::createImmutable(BASE_PATH)->load();

// bind dependencies
app()->bind(Config::class, fn () => new Config(require BASE_PATH.'/src/Config/app.php'));
app()->bind(DBCapsule::class, function () {
    $capsule = new DBCapsule();
    $capsule->addConnection([
        'driver' => $_ENV['DB_CONNECTION'] ?? 'mysql',
        'host' => $_ENV['DB_HOST'] ?? 'localhost',
        'database' => $_ENV['DB_DATABASE'] ?? 'database',
        'username' => $_ENV['DB_USERNAME'] ?? 'user',
        'password' => $_ENV['DB_PASSWORD'] ?? 'password',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
    ]);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
});
app()->bind(
    RedisClient::class,
    fn () => new RedisClient([
        'scheme' => config('REDIS_SCHEME'),
        'host' => config('REDIS_HOST'),
        'port' => config('REDIS_PORT'),
    ])
);
app()->bind(Mailer::class, fn () => new Mailer(new PHPMailer(true)));
app()->bind(MonologLogger::class, fn () => new MonologLogger('app'));
app()->bind(StreamHandler::class, fn () => new StreamHandler(config('LOGS_PATH'), Level::Debug));
app()->bind(FilesystemLoader::class, fn () => new FilesystemLoader(config('VIEWS_PATH')));
