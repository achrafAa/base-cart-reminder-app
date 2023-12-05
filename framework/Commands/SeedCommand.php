<?php

namespace Achraf\framework\Commands;

use App\Database\DatabaseSeeder;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'db:seed', description: 'Seed database')]
class SeedCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $output->writeln('Seeding database...');
            $output->writeln('this will take few minutes... (drink some water)');
            $seeder = new DatabaseSeeder();
            $seeder->run();
            $output->writeln('Database seeded successfully!');

            return Command::SUCCESS;
        } catch (\Exception $exception) {
            $output->writeln($exception);

            return Command::FAILURE;
        }
    }
}
