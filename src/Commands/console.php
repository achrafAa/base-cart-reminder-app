<?php

use Achraf\framework\Commands\MakeMigrationCommand;
use Achraf\framework\Commands\MigrateCommand;
use Achraf\framework\Commands\SeedCommand;
use App\Commands\PushReminderNotificationsCommand;
use Symfony\Component\Console\Application;

require_once BASE_PATH.'/src/App.php';

$application = new Application();
$application->add(new MigrateCommand());
$application->add(new MakeMigrationCommand());
$application->add(new SeedCommand());
$application->add(app()->get(PushReminderNotificationsCommand::class));

try {
    $application->run();
} catch (Exception $e) {
    echo $e->getMessage();
}
