<?php

use App\Commands\makeMigration;
use App\Commands\migrate;
use Symfony\Component\Console\Application;

require_once BASE_PATH . '/src/App.php';

$application = new Application();
$application->add(new migrate());
$application->add(new makeMigration());

try {
    $application->run();
} catch (Exception $e) {
    echo $e->getMessage();
}
