<?php

namespace ArtemSchander\L5Modular\Console;

use ArtemSchander\L5Modular\Traits\MakesComponent;
use Illuminate\Database\Console\Migrations\MigrateMakeCommand as BaseMigrateMakeCommand;
use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Illuminate\Support\Str;

class MigrateMakeCommand extends BaseMigrateMakeCommand
{
    use MakesComponent;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'make:module:migration {name : The name of the migration}
        {--create= : The table to be created}
        {--table= : The table to migrate}
        {--module= : Generate a migration in a certain module}
        {--path= : The location where the migration file should be created}
        {--fullpath : Output the full path of the migration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new migration file in a module';

    /**
     * Create a new migration install command instance.
     *
     * @param  \Illuminate\Database\Migrations\MigrationCreator  $creator
     * @param  \Illuminate\Support\Composer  $composer
     * @return void
     */
    public function __construct(FileSystem $files, MigrationCreator $creator, Composer $composer)
    {
        $this->files = $files;
        parent::__construct($creator, $composer);
    }

    /**
     * Write the migration file to disk.
     *
     * @param  string  $name
     * @param  string  $table
     * @param  bool  $create
     * @return string
     */
    protected function writeMigration($name, $table, $create)
    {
        if (! $this->files->isDirectory($this->getMigrationPath())) {
            $this->files->makeDirectory($this->getMigrationPath(), 0755, true);
        }

        $file = $this->creator->create($name, $this->getMigrationPath(), $table, $create);

        if (! $this->option('fullpath')) {
            $file = pathinfo($file, PATHINFO_FILENAME);
        }

        if (! $this->option('quiet')) {
            $this->line("<info>Created Migration:</info> {$file}");
        }
    }

    /**
     * Get migration path (either specified by '--path' option or default location).
     *
     * @return string
     */
    protected function getMigrationPath()
    {
        $migrationPath = $this->laravel['path'] . '/Modules/' . Str::studly($this->module) . '/' . $this->getConfiguredFolder('migrations');

        if (! is_null($targetPath = $this->input->getOption('path'))) {
            return $migrationPath . '/' . $targetPath;
        }

        return $migrationPath;
    }
}
