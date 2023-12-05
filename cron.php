<?php

define('BASE_PATH', __DIR__);
require BASE_PATH.'/src/Commands/console.php';

use Cron\Cron;
use Cron\Job\ShellJob;
use Cron\Resolver\ArrayResolver;
use Cron\Schedule\CrontabSchedule;

$cron = new Cron();

$resolver = new ArrayResolver();
$jobs = [
    [
        'command' => 'php wizard app:push-cart-reminders-notifications',
        'schedule' => '* * * * *',
    ],
];
foreach ($jobs as $job) {
    $shellJob = new ShellJob();
    $shellJob->setCommand($job['command']);
    $shellJob->setSchedule(new CrontabSchedule($job['schedule']));
    $resolver->addJob($shellJob);
}

$cron->setResolver($resolver);

$cron->run();
