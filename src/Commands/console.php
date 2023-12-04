<?php

use App\Commands\makeMigration;
use App\Commands\migrate;
use Symfony\Component\Console\Application;


require_once BASE_PATH . '/vendor/autoload.php';
require_once BASE_PATH . '/framework/Bootstrap.php';

$application = new Application();
$application->add(new migrate());
$application->add(new makeMigration());

try {
    $application->run();
} catch (Exception $e) {
    echo $e->getMessage();
}