<?php

namespace ArtemSchander\L5Modular\Console;

use ArtemSchander\L5Modular\Traits\MakesMessage;
use ArtemSchander\L5Modular\Traits\MakesComponent;
use Illuminate\Foundation\Console\NotificationMakeCommand as BaseNotificationMakeCommand;
use ReflectionClass;

class NotificationMakeCommand extends BaseNotificationMakeCommand
{
    use MakesMessage;
    use MakesComponent;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:module:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new notification class in a module';

    /**
     * The key of the component to be generated.
     */
    const KEY = 'notifications';

    /**
     * The cli info that will be shown on --help.
     */
    const MODULE_OPTION_INFO = 'Generate a notification in a certain module';

    /**
     * Name of the stub file
     *
     * @var string
     */
    const STUB = 'notification.stub';

    /**
     * Returns a reflection of the extended class
     *
     * @return ReflectionClass
     */
    protected function getBaseClass()
    {
        return new ReflectionClass(BaseNotificationMakeCommand::class);
    }
}
