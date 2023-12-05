<?php

namespace App\Commands;

use App\Jobs\CreateReminderNotificationsJob;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:push-cart-reminders-notifications', description: 'push cart reminders notifications')]
class PushReminderNotificationsCommand extends Command
{
    private CreateReminderNotificationsJob $createReminderNotificationsJob;

    public function __construct(CreateReminderNotificationsJob $createReminderNotificationsJob)
    {
        $this->createReminderNotificationsJob = $createReminderNotificationsJob;
        parent::__construct();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $output->writeln('Creating reminder notifications...');
            $this->createReminderNotificationsJob->dispatch();
            $output->writeln('Reminder notifications dispatched successfully!');

            return Command::SUCCESS;
        } catch (\Exception $exception) {
            $output->writeln($exception);

            return Command::FAILURE;
        }
    }
}