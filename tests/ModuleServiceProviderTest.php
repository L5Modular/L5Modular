<?php

namespace ArtemSchander\L5Modular\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Filesystem\Filesystem;
use ArrayAccess;
use Mockery;

use ArtemSchander\L5Modular\Tests\TestCase;
use ArtemSchander\L5Modular\ModuleServiceProvider;

/**
 * @author Artem Schander
 */
class ModuleServiceProviderTest extends TestCase
{
    private $finder;
    private $serviceProvider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->serviceProvider = new ModuleServiceProvider($this->app);
        $this->finder = $this->app['files'];
    }

    protected function tearDown(): void
    {
        if ($this->finder->isDirectory(base_path('app/Modules/FooBar'))) {
            $this->finder->deleteDirectory(base_path('app/Modules/FooBar'));
        }
        Mockery::close();
        parent::tearDown();
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('modules', [
            'generate' => [
                'controller' => true,
                'model' => true,
                'view' => true,
                'translation' => true,
                'routes' => true,
                'migration' => true,
                'seeder' => true,
                'factory' => true,
                'helpers' => true,
                'config' => true,
            ],
            'default' => [
                'routing' => [ 'web', 'api', 'simple' ],
                'structure' => [
                    'controllers' => 'Controllers',
                    'models' => 'Models',
                    'views' => 'resources/views',
                    'translations' => 'resources/lang',
                    'routes' => 'routes',
                    'migrations' => 'database/migrations',
                    'seeds' => 'database/seeds',
                    'factories' => 'database/factories',
                    'helpers' => '',
                ],
            ],
            'specific' => [
                // 'ExampleModule' => [
                //     'enabled' => false,
                //     'routing' => [ 'simple' ],
                //     'structure' => [
                //         'views' => 'Views',
                //         'translations' => 'Translations',
                //     ],
                // ],
            ],
        ]);
    }

    /** @test */
    public function it_can_be_constructed()
    {
        $this->assertInstanceOf(ModuleServiceProvider::class, $this->serviceProvider);
    }

    /** @test */
    public function should_register_the_package()
    {
        $this->app->setBasePath(__DIR__ . '/..');

        $app = Mockery::mock(ArrayAccess::class);
        $serviceProvider = new ModuleServiceProvider($app);

        $app->shouldReceive('singleton')
            ->times(22)
            ->andReturnNull();

        $app->shouldReceive('configPath')
            ->once()
            ->with('modules.php')
            ->andReturn('config/modules.php');

        $configRepository = Mockery::mock(ConfigRepository::class);

        if (version_compare(Application::VERSION, '7.19.0', '>=')) {
            $app->shouldReceive('make')
                ->once()
                ->with('config')
                ->andReturn($configRepository);
        }

        $configRepository->shouldReceive('set')
            ->once();
        $configRepository->shouldReceive('get')
            ->once()
            ->andReturn([]);

        $app->shouldReceive('offsetGet')
            ->zeroOrMoreTimes()
            ->with('config')
            ->andReturn($configRepository);

        $app->shouldReceive('configurationIsCached')
            ->zeroOrMoreTimes()
            ->andReturn(false);

        $result = $serviceProvider->register();
        $this->assertNull($result);
    }

    /** @test */
    public function should_boot_a_full_module()
    {
        $basePath = realpath($this->app['path.base']);
        $this->artisan('make:module', ['name' => 'foo-bar']);

        $app = Mockery::mock(ArrayAccess::class);
        $fileSystem = Mockery::mock(FileSystem::class);
        $serviceProvider = new ModuleServiceProvider($app);

        $configRepository = Mockery::mock(ConfigRepository::class);

        $configRepository->shouldReceive('get')
            ->with('modules.specific.FooBar', [])
            ->once()
            ->andReturn([]);

        $app->shouldReceive('offsetGet')
            ->zeroOrMoreTimes()
            ->with('config')
            ->andReturn($configRepository);

        $fileSystem->shouldReceive('exists')
            ->once()
            ->with($basePath . '/app/Modules/FooBar/config.php')
            ->andReturn(true);

        $config = [
            'enabled' => true,
            'routing' => [ 'web', 'api' ],
            'structure' => [
                'controllers' => 'Http/Controllers',
                'resources' => 'Http/Resources',
                'requests' => 'Http/Requests',
                'models' => 'Models',
                'mails' => 'Mail',
                'notifications' => 'Notifications',
                'events' => 'Events',
                'listeners' => 'Listeners',
                'observers' => 'Observers',
                'jobs' => 'Jobs',
                'rules' => 'Rules',
                'views' => 'resources/views',
                'translations' => 'resources/lang',
                'routes' => 'routes',
                'migrations' => 'database/migrations',
                'seeds' => 'database/seeds',
                'factories' => 'database/factories',
                'helpers' => '',
            ],
        ];

        $configRepository->shouldReceive('set')
            ->withArgs(['modules.specific.FooBar', $config])
            ->once();

        $configRepository->shouldReceive('get')
            ->with('FooBar')
            ->once()
            ->andReturn(false);

        $configRepository->shouldReceive('set')
            ->withArgs(['FooBar', $config])
            ->once();

        $fileSystem->shouldReceive('directories')
            ->once()
            ->andReturn([ 'FooBar' ]);

        $app->shouldReceive('routesAreCached')
            ->once()
            ->andReturn(false);

        $app->shouldReceive('getNamespace')
            ->once()
            ->andReturn('App/');

        $fileSystem->shouldReceive('exists')
            ->once()
            ->with($basePath . '/app/Modules/FooBar/routes/api.php')
            ->andReturn(true);

        $fileSystem->shouldReceive('exists')
            ->once()
            ->with($basePath . '/app/Modules/FooBar/routes/web.php')
            ->andReturn(true);

        $fileSystem->shouldReceive('exists')
            ->once()
            ->with($basePath . '/app/Modules/FooBar/routes/routes.php')
            ->andReturn(true);

        $fileSystem->shouldReceive('exists')
            ->once()
            ->with($basePath . '/app/Modules/FooBar/helpers.php')
            ->andReturn(true);

        $fileSystem->shouldReceive('isDirectory')
            ->once()
            ->with($basePath . '/app/Modules/FooBar/resources/views')
            ->andReturn(true);

        $app->shouldReceive('afterResolving')
            ->times(3);

        $app->shouldReceive('resolved')
            ->times(3);

        $fileSystem->shouldReceive('isDirectory')
            ->once()
            ->with($basePath . '/app/Modules/FooBar/resources/lang')
            ->andReturn(true);

        $fileSystem->shouldReceive('isDirectory')
            ->once()
            ->with($basePath . '/app/Modules/FooBar/database/migrations')
            ->andReturn(true);

        $fileSystem->shouldReceive('isDirectory')
            ->once()
            ->with($basePath . '/app/Modules/FooBar/database/factories')
            ->andReturn(true);

        $app->shouldReceive('make')
            ->once()
            ->with(Factory::class)
            ->andReturn($app);

        $app->shouldReceive('load')
            ->once()
            ->with($basePath . '/app/Modules/FooBar/database/factories');

        $result = $serviceProvider->boot($fileSystem);
        $this->assertNull($result);
    }

    /** @test */
    public function should_boot_a_module_with_a_simple_route_file_only()
    {
        $this->app['config']->set('modules.default.structure.routes', '');
        $this->app['config']->set('modules.default.routing', [ 'simple' ]);
        $this->app['config']->set('modules.generate', [
            'routes' => true,
        ]);

        $basePath = realpath($this->app['path.base']);
        $this->artisan('make:module', ['name' => 'foo-bar']);

        $app = Mockery::mock(ArrayAccess::class);
        $fileSystem = Mockery::mock(FileSystem::class);
        $serviceProvider = new ModuleServiceProvider($app);

        $configRepository = Mockery::mock(ConfigRepository::class);

        $configRepository->shouldReceive('get')
            ->once()
            ->andReturn([]);

        $app->shouldReceive('offsetGet')
            ->zeroOrMoreTimes()
            ->with('config')
            ->andReturn($configRepository);

        $fileSystem->shouldReceive('exists')
            ->once()
            ->with($basePath . '/app/Modules/FooBar/config.php')
            ->andReturn(false);

        $fileSystem->shouldReceive('directories')
            ->once()
            ->andReturn([ 'FooBar' ]);

        $app->shouldReceive('routesAreCached')
            ->once()
            ->andReturn(false);

        $app->shouldReceive('getNamespace')
            ->once()
            ->andReturn('App/');

        $fileSystem->shouldNotReceive('exists')
            ->with($basePath . '/app/Modules/FooBar/routes/api.php');

        $fileSystem->shouldNotReceive('exists')
            ->with($basePath . '/app/Modules/FooBar/routes/web.php');

        $fileSystem->shouldReceive('exists')
            ->once()
            ->with($basePath . '/app/Modules/FooBar/routes.php')
            ->andReturn(true);

        $fileSystem->shouldReceive('exists')
            ->with($basePath . '/app/Modules/FooBar/helpers.php');

        $fileSystem->shouldReceive('isDirectory')
            ->once()
            ->with($basePath . '/app/Modules/FooBar/resources/views')
            ->andReturn(false);

        // $app->shouldReceive('afterResolving')
        //     ->times(3);
        //
        // $app->shouldReceive('resolved')
        //     ->times(3);

        $fileSystem->shouldReceive('isDirectory')
            ->once()
            ->with($basePath . '/app/Modules/FooBar/resources/lang')
            ->andReturn(false);

        $fileSystem->shouldReceive('isDirectory')
            ->once()
            ->with($basePath . '/app/Modules/FooBar/database/migrations')
            ->andReturn(false);

        $fileSystem->shouldReceive('isDirectory')
            ->once()
            ->with($basePath . '/app/Modules/FooBar/database/factories')
            ->andReturn(false);

        $app->shouldNotReceive('make')
            ->with(Factory::class);

        $app->shouldNotReceive('load')
            ->with($basePath . '/app/Modules/FooBar/database/factories');

        $result = $serviceProvider->boot($fileSystem);
        $this->assertNull($result);
    }
}
