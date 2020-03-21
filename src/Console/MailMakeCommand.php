<?php

namespace ArtemSchander\L5Modular\Console;

use ArtemSchander\L5Modular\Traits\MakesMessage;
use ArtemSchander\L5Modular\Traits\MakesComponent;
use Illuminate\Foundation\Console\MailMakeCommand as BaseMailMakeCommand;
use ReflectionClass;

class MailMakeCommand extends BaseMailMakeCommand
{
    use MakesMessage;
    use MakesComponent;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:module:mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new mail class in a module';

    /**
     * The key of the component to be generated.
     */
    const KEY = 'mails';

    /**
     * The cli info that will be shown on --help.
     */
    const MODULE_OPTION_INFO = 'Generate a mailable in a certain module';

    /**
     * Name of the stub file
     */
    const STUB = 'mail.stub';

    /**
     * Returns a reflection of the extended class
     *
     * @return ReflectionClass
     */
    protected function getBaseClass()
    {
        return new ReflectionClass(BaseMailMakeCommand::class);
    }
}
