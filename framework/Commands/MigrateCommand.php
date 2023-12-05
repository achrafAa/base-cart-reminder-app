<?php

namespace Achraf\framework\Commands;

use App\Models\Migration;
use DirectoryIterator;
use Illuminate\Database\Capsule\Manager as DBCapsule;
use Monolog\Level;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'db:migrate', description: 'Migrate database')]
class MigrateCommand extends Command
{
    protected function configure(): void
    {
        $this->addOption(
            'rollback',
            null,
            InputOption::VALUE_NONE,
            'If set, the task will rollback the last migration'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
                $migrationTableExists = DBCapsule::select('SHOW TABLES LIKE "migrations"');
                if(!empty($migrationTableExists)){
                    $batch = Migration::query()->max('batch') + 1 ?? 1;
                }else{
                    $this->createMigrationTable();
                    $batch = 1;
                    $output->writeln('Migrations table created successfully!');
                }

            if ($input->getOption('rollback')) {
                if ($batch === 1) {
                    $output->writeln('Nothing to rollback!');

                    return Command::FAILURE;
                }

                return $this->down($input, $output, $batch);
            }

            return $this->up($input, $output, $batch);
        } catch (\Exception $exception) {
            $output->writeln($exception);
            logToFile('error', sprintf(' %s: %s', $exception->getMessage(), $exception->getTraceAsString()));
            return Command::FAILURE;
        }
    }

    public function getClassesInDirectory($directory): array
    {
        $classes = [];
        foreach (new DirectoryIterator($directory) as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }
            if ($fileInfo->isDir()) {
                $classes = array_merge($classes, $this->getClassesInDirectory($fileInfo->getPathname()));
            } else {
                if ($fileInfo->getExtension() === 'php') {
                    $classes[] = $fileInfo->getBasename('.php');
                }
            }
        }
        sort($classes);
        return $classes;
    }

    public function up(InputInterface $input, OutputInterface $output, int $batch): int
    {
        $output->writeln('Migrating database...');
        $output->writeln('this might take few minutes... (drink some water)');
        $classes = $this->getClassesInDirectory(BASE_PATH.'/src/Database/Migrations/');
        $migrated = Migration::query()->pluck('migration')->toArray();
        foreach ($classes as $class) {
            if (in_array($class, $migrated)) {
                continue;
            }
            $output->writeln('Migrating '.$class.'...');
            $migration = include BASE_PATH.'/src/Database/Migrations/'.$class.'.php';
            if (! method_exists($migration, 'up')) {
                $output->writeln('Method up() not found in '.$class.'!');

                return Command::FAILURE;
            }
            $migration->up();
            Migration::create([
                'migration' => $class,
                'batch' => $batch,
            ])->save();
        }
        $output->writeln('Database migrated successfully!');

        return Command::SUCCESS;
    }

    private function down(InputInterface $input, OutputInterface $output, mixed $batch)
    {
        $output->writeln('Rolling back database...');
        $output->writeln('this might take few minutes... (drink some water)');
        $classes = Migration::query()
            ->where('batch', $batch)
            ->get()
            ->pluck('migration')
            ->toArray();

        foreach ($classes as $class) {
            $output->writeln('Rolling back '.$class.'...');
            $migration = include BASE_PATH.'/src/Database/Migrations/'.$class.'.php';
            if (! method_exists($migration, 'down')) {
                $output->writeln('Method down() not found in '.$class.'!');

                return Command::FAILURE;
            }
            $migration->down();
            Migration::query()->where('migration', $class)->delete();
        }
        $output->writeln('Database rolled back successfully!');

        return Command::SUCCESS;
    }

    public function createMigrationTable(): void
    {
        $migration = include BASE_PATH.'/src/Database/create_migration_table.php';
        $migrationTableExists = DBCapsule::select('SHOW TABLES LIKE "migrations"');
        if(! empty($migrationTableExists)){
            return;
        }
        $migration->up();
    }
}
