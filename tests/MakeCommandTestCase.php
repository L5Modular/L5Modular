<?php

namespace ArtemSchander\L5Modular\Tests;

use ArtemSchander\L5Modular\ModuleServiceProvider;
use ArtemSchander\L5Modular\Traits\ConfiguresFolder;

class MakeCommandTestCase extends \Orchestra\Testbench\TestCase
{
    use ConfiguresFolder;

    /**
     * @var string
     */
    protected $modulePath;

    /**
     * The name of the module.
     */
    protected $moduleName = 'FooBar';

    protected function getPackageProviders($app)
    {
        return [ModuleServiceProvider::class];
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->modulePath = $this->app['path'].'/Modules/'.$this->moduleName;
    }

    protected function tearDown(): void
    {
        $this->app['files']->deleteDirectory($this->modulePath);
        parent::tearDown();
    }
}