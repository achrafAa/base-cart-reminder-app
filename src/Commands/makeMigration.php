<?php

namespace App\Commands;

use Illuminate\Database\Capsule\Manager as DBCapsule;
use Illuminate\Database\Schema\Blueprint;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'make:migration', description: 'make database migration')]

class makeMigration Extends Command
{
    protected function Configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Whats the name of your migration?');
    }
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
            $name = $input->getArgument('name');
            $className = ucfirst($name);
            try {
                $migrationTemplate = <<<EOT
<?php   
namespace App\Database\Migrations;

use Illuminate\Database\Capsule\Manager as DBCapsule;
use Illuminate\Database\Schema\Blueprint;

return new class
{
    public function up()
    {
        DBCapsule::schema()->create("$name", function (Blueprint \$table) {
            \$table->id();
            \$table->timestamps();
        });
    }
    public function down()
    {
        DBCapsule::schema()->dropIfExists("$name");
    }
};   
EOT;
                $filename = BASE_PATH . '/src/Database/Migrations/'.date('Y_m_d_His') . '_'. $className . '.php';

                if (file_exists($filename)) {
                    $output->writeln("Migration $className already exists!");
                    return Command::FAILURE;
                }
                file_put_contents($filename, $migrationTemplate);
                $output->writeln('Migration created successfully!');
                return Command::SUCCESS;
            } catch (\Exception $exception) {
                $output->writeln($exception);
                return Command::FAILURE;
            }

    }






}