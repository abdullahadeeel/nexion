<?php

namespace App\Console;

use Nexion\Console\Kernel as ConsoleKernel;
use Nexion\Console\Commands\ServeCommand;
use Nexion\Console\Commands\MigrateCommand;
use Nexion\Console\Commands\MakeControllerCommand;
use Nexion\Console\Commands\MakeModelCommand;
use Nexion\Console\Commands\MakeMigrationCommand;

class Kernel extends ConsoleKernel
{
    /**
     * The artisan commands provided by your application.
     */
    protected array $commands = [
        ServeCommand::class,
        MigrateCommand::class,
        MakeControllerCommand::class,
        MakeModelCommand::class,
        MakeMigrationCommand::class,
    ];

    public function handle($input, $output = null): int
    {
        foreach ($this->commands as $command) {
            $this->console->add($this->app->make($command));
        }

        return parent::handle($input, $output);
    }
}
