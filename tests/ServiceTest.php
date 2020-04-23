<?php

namespace ArtemSchander\L5Modular\Tests;

use Mockery;

use ArtemSchander\L5Modular\Tests\TestCase;
use ArtemSchander\L5Modular\ModuleServiceProvider;

use ArtemSchander\L5Modular\Facades\L5Modular;

/**
 * @author Artem Schander
 */
class ServiceTest extends TestCase
{
    private $config;
    private $finder;

    protected function setUp(): void
    {
        parent::setUp();
        new ModuleServiceProvider($this->app);
        $this->finder = $this->app['files'];
        $this->config = $this->app['config'];
    }

    protected function tearDown(): void
    {
        if ($this->finder->isDirectory(base_path('app/Modules/FooBar'))) {
            $this->finder->deleteDirectory(base_path('app/Modules/FooBar'));
        }
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_checks_if_a_module_exists()
    {
        $this->assertFalse(L5Modular::exists('FooBar'));
        $this->artisan('make:module', ['name' => 'foo-bar']);
        $this->assertTrue(L5Modular::exists('FooBar'));
    }

    /** @test */
    public function it_checks_if_a_module_is_enabled()
    {
        $this->assertFalse(L5Modular::enabled('FooBar'));
        $this->artisan('make:module', ['name' => 'foo-bar']);

        $this->assertTrue(L5Modular::enabled('FooBar'));
        $this->config->set('FooBar.enabled', false);
        $this->assertFalse(L5Modular::enabled('FooBar'));
    }

    /** @test */
    public function it_checks_if_a_module_is_disabled()
    {
        $this->assertTrue(L5Modular::disabled('FooBar'));
        $this->artisan('make:module', ['name' => 'foo-bar']);

        $this->assertFalse(L5Modular::disabled('FooBar'));
        $this->config->set('modules.specific.FooBar.enabled', false);
        $this->assertTrue(L5Modular::disabled('FooBar'));
    }

    /** @test */
    public function it_enables_a_module()
    {
        $this->artisan('make:module', ['name' => 'foo-bar']);
        L5Modular::enable('FooBar');

        $this->assertTrue($this->config->get('modules.specific.FooBar.enabled'));
        $this->assertTrue($this->config->get('FooBar.enabled'));
    }

    /** @test */
    public function it_disables_a_module()
    {
        $this->artisan('make:module', ['name' => 'foo-bar']);
        $this->config->set('modules.specific.FooBar.enabled', false);
        $this->config->set('FooBar.enabled', false);

        L5Modular::disable('FooBar');

        $this->assertFalse($this->config->get('modules.specific.FooBar.enabled'));
        $this->assertFalse($this->config->get('FooBar.enabled'));
    }
}
