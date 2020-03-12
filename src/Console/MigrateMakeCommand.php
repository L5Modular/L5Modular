<?php

namespace ArtemSchander\L5Modular\Console;

use ArtemSchander\L5Modular\Traits\ConfiguresFolder;
use ArtemSchander\L5Modular\Traits\HasModuleOption;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MigrateMakeCommand extends Command
{
    use ConfiguresFolder, HasModuleOption;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'make:module:migration {name : The name of the migration}
        {--create= : The table to be created}
        {--table= : The table to migrate}
        {--module= : Generate a migration in a certain module}
        {--realpath : Indicate any provided migration file paths are pre-resolved absolute paths}
        {--fullpath : Output the full path of the migration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new migration file in a module';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->initModuleOption();

        if (!$this->module) {
            return false;
        }

        $options = $this->input->getOptions();
        $options['path'] = $this->laravel['path'] . '/Modules/' . $this->module . '/' . $this->getConfiguredFolder('migrations');

        $this->call('make:migration', $options);
    }
}
