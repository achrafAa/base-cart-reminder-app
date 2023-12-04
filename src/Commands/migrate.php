<?php

namespace App\Commands;

use App\Models\Migration;
use DirectoryIterator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Illuminate\Database\Capsule\Manager as DBCapsule;

#[AsCommand(name: 'db:migrate', description: 'Migrate database')]
class migrate Extends Command
{
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

            try {


                if (DBCapsule::schema()->hasTable('migration')) {
                    $batch = Migration::query()->max('batch') + 1 ?? 1;
                }else{
                    $output->writeln('Creating migrations table...');
                    $batch = 1;
                    $output->writeln('Migrations table created successfully!');
                }

                $output->writeln('Migrating database...');
                $output->writeln('this might take few minutes... (drink some water)');
                $classes = $this->getClassesInDirectory(BASE_PATH . '/src/Database/Migrations');
                foreach ($classes as $class) {
                    $output->writeln('Migrating ' . $class . '...');
                    $migration = include BASE_PATH . '/src/Database/Migrations/'. $class . '.php';
                    $migration->up();
                    Migration::create([
                        'migration' => $class,
                        'batch' => $batch
                    ])->save();
                }
                $output->writeln('Database migrated successfully!');
                return Command::SUCCESS;
            } catch (\Exception $exception) {
                $output->writeln($exception);
                return Command::FAILURE;
            }

    }

    /**
     * @param $directory
     * @return array
     */
    function getClassesInDirectory($directory): array
    {
        $classes = [];
        foreach (new DirectoryIterator($directory) as $fileInfo) {
            if($fileInfo->isDot()) continue;
            if($fileInfo->isDir()) {
                $classes = array_merge($classes, $this->getClassesInDirectory($fileInfo->getPathname()));
            } else {
                if($fileInfo->getExtension() === 'php') {
                    $classes[] = $fileInfo->getBasename('.php');
                }
            }
        }
        return $classes;
    }





}