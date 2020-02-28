<?php

namespace Illuminate\Foundation\Console;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;

class EventGenerateCommand extends Command
{
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $providers = $this->laravel->getProviders(EventServiceProvider::class);

        foreach ($providers as $provider) {
            foreach ($provider->listens() as $event => $listeners) {
                $this->makeEventAndListeners($event, $listeners);
            }
        }

        $this->info('Events and listeners generated successfully!');
    }

    /**
     * Make the event and listeners for the given event.
     *
     * @param  string  $event
     * @param  array  $listeners
     * @return void
     */
    protected function makeEventAndListeners($event, $listeners)
    {
        if (! Str::contains($event, '\\')) {
            return;
        }
        
        if (Str::contains($event, 'Modules')) {
            $module = Str::before('\\' ,Str::after($event, 'Modules'));
        }

        $this->callSilent('make:event', array_filter([
            'name' => $event,
            '--module' => $module ?? null,
        ]));

        $this->makeListeners($event, $listeners);
    }

    /**
     * Make the listeners for the given event.
     *
     * @param  string  $event
     * @param  array  $listeners
     * @return void
     */
    protected function makeListeners($event, $listeners)
    {
        foreach ($listeners as $listener) {
            $listener = preg_replace('/@.+$/', '', $listener);

            if (Str::contains($event, 'Modules')) {
                $module = Str::before('\\' ,Str::after($event, 'Modules'));
            }

            $this->callSilent('make:listener', array_filter([
                'name' => $listener,
                '--event' => $event,
                '--module' => $module ?? null,
            ]));
        }
    }
}
