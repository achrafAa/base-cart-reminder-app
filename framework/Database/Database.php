<?php

namespace Achraf\framework\Database;

use Illuminate\Database\Capsule\Manager as Capsule;

class Database
{
    /**
     * Boot Eloquent ORM.
     * @return void
     */
    public function boot(): void
    {
        $capsule = new Capsule();
        $capsule->addConnection([
            'driver' => config('DB_DRIVER') ?? 'mysql',
            'host' => config('DB_HOST') ?? 'localhost',
            'database' => config('DB_DATABASE') ?? 'database',
            'username' => config('DB_USERNAME') ?? 'user',
            'password' => config('DB_PASSWORD') ?? 'password',
            'charset' => config('DB_CHARSET') ?? 'utf8',
            'collation' => config('DB_COLLATION') ?? 'utf8_unicode_ci',
            'prefix' => config('DB_PREFIX') ?? '',
        ]);
        $capsule->bootEloquent();
    }
}
