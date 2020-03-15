<?php

namespace ArtemSchander\L5Modular\Tests\Commands;

use ArtemSchander\L5Modular\Tests\MakeCommandTestCase;

/**
 * @author Artem Schander
 */
class ModuleListCommandTest extends MakeCommandTestCase
{
    /** @test */
    public function Should_ShowListOfModule()
    {
        $this->artisan('make:module', ['name' => 'Foo'])
            ->assertExitCode(0);

        $this->artisan('make:module', ['name' => 'Bar'])
            ->assertExitCode(0);

        $this->app['config']->set('modules.specific.Bar.enabled', false);

        $this->artisan('module:list')->assertExitCode(0);
    }
}
