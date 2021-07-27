<?php

namespace ArtemSchander\L5Modular\Tests\Commands;

use ArtemSchander\L5Modular\Tests\TestCase;

/**
 * @author Artem Schander
 */
class ModuleMakeCommandTest extends TestCase
{
    /**
     * @var string
     */
    private $modulePath;

    public function setUp(): void
    {
        parent::setUp();
        $this->modulePath = base_path('app/Modules/FooBar');
        $this->finder = $this->app['files'];
    }

    protected function tearDown(): void
    {
        $this->finder->deleteDirectory($this->modulePath);
        parent::tearDown();
    }

    /** @test */
    public function should_generate_a_module()
    {
        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $this->assertDirectoryExists($this->modulePath);
    }

    /** @test */
    public function should_not_generate_a_module_with_duplicate_name()
    {
        mkdir($this->modulePath);
        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $this->assertDirectoryExists($this->modulePath);
    }

    /** @test */
    public function should_generate_a_module_using_kebap_case()
    {
        $code = $this->artisan('make:module', ['name' => 'foo-bar']);
        $this->assertSame(0, $code);

        $this->assertDirectoryExists($this->modulePath);
    }

    /** @test */
    public function should_generate_a_module_with_controller()
    {
        $this->app['config']->set('modules.default.structure.controllers', 'Controllers');
        $this->app['config']->set('modules.generate', [
            'controller' => true,
        ]);
        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $file = $this->modulePath . '/Controllers/FooBarController.php';
        $this->assertFileExists($file);

        $content = file_get_contents($file);
        $this->assertNotFalse(strpos($content, 'namespace App\\Modules\\FooBar\\Controllers;'));
        $this->assertNotFalse(strpos($content, 'class FooBarController extends Controller'));
    }

    /** @test */
    public function should_generate_a_module_with_controller_in_custom_location()
    {
        $this->app['config']->set('modules.default.structure.controllers', 'Http/Controllers');
        $this->app['config']->set('modules.generate', [
            'controller' => true,
        ]);
        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $file = $this->modulePath . '/Http/Controllers/FooBarController.php';
        $this->assertFileExists($file);

        $content = file_get_contents($file);
        $this->assertNotFalse(strpos($content, 'namespace App\\Modules\\FooBar\\Http\\Controllers;'));
        $this->assertNotFalse(strpos($content, 'class FooBarController extends Controller'));
    }

    /** @test */
    public function should_generate_a_module_without_controller()
    {
        $this->app['config']->set('modules.default.structure.controllers', 'Controllers');
        $this->app['config']->set('modules.generate', [
            'controller' => false,
        ]);
        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $file = $this->modulePath . '/Controllers/FooBarController.php';
        $this->assertFileNotExists($file);

        $dir = $this->modulePath . '/Controllers';
        $this->assertDirectoryNotExists($dir);
    }

    /** @test */
    public function should_generate_a_module_with_model()
    {
        $this->app['config']->set('modules.default.structure.models', 'Models');
        $this->app['config']->set('modules.generate', [
            'model' => true,
        ]);
        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $file = $this->modulePath . '/Models/FooBar.php';
        $this->assertFileExists($file);

        $content = file_get_contents($file);
        $this->assertNotFalse(strpos($content, 'namespace App\\Modules\\FooBar\\Models;'));
        $this->assertNotFalse(strpos($content, 'class FooBar extends Model'));
    }

    /** @test */
    public function should_generate_a_module_with_model_in_custom_location()
    {
        $this->app['config']->set('modules.default.structure.models', 'Entities');
        $this->app['config']->set('modules.generate', [
            'model' => true,
        ]);
        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $file = $this->modulePath . '/Entities/FooBar.php';
        $this->assertFileExists($file);

        $content = file_get_contents($file);
        $this->assertNotFalse(strpos($content, 'namespace App\\Modules\\FooBar\\Entities;'));
        $this->assertNotFalse(strpos($content, 'class FooBar extends Model'));
    }

    /** @test */
    public function should_generate_a_module_without_model()
    {
        $this->app['config']->set('modules.default.structure.models', 'Models');
        $this->app['config']->set('modules.generate', [
            'model' => false,
        ]);
        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $file = $this->modulePath . '/Models/FooBar.php';
        $this->assertFileNotExists($file);

        $dir = $this->modulePath . '/Models';
        $this->assertDirectoryNotExists($dir);
    }

    /** @test */
    public function should_generate_a_module_with_view()
    {
        $this->app['config']->set('modules.default.structure.views', 'resources/views');
        $this->app['config']->set('modules.generate', [
            'view' => true,
        ]);
        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $file = $this->modulePath . '/resources/views/welcome.blade.php';
        $this->assertFileExists($file);
    }

    /** @test */
    public function should_generate_a_module_with_view_in_custom_location()
    {
        $this->app['config']->set('modules.default.structure.views', 'views');
        $this->app['config']->set('modules.generate', [
            'view' => true,
        ]);
        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $file = $this->modulePath . '/views/welcome.blade.php';
        $this->assertFileExists($file);
    }

    /** @test */
    public function should_generate_a_module_without_view()
    {
        $this->app['config']->set('modules.default.structure.views', 'resources/views');
        $this->app['config']->set('modules.generate', [
            'view' => false,
        ]);
        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $file = $this->modulePath . '/resources/views/welcome.blade.php';
        $this->assertFileNotExists($file);

        $dir = $this->modulePath . '/resources/views';
        $this->assertDirectoryNotExists($dir);
    }

    /** @test */
    public function should_generate_a_module_with_translation()
    {
        $this->app['config']->set('modules.default.structure.translations', 'resources/lang');
        $this->app['config']->set('modules.generate', [
            'translation' => true,
        ]);

        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $file = $this->modulePath . '/resources/lang/en/example.php';
        $this->assertFileExists($file);
    }

    /** @test */
    public function should_generate_a_module_with_translation_in_custom_location()
    {
        $this->app['config']->set('modules.default.structure.translations', 'translations');
        $this->app['config']->set('modules.generate', [
            'translation' => true,
        ]);

        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $file = $this->modulePath . '/translations/en/example.php';
        $this->assertFileExists($file);
    }

    /** @test */
    public function should_generate_a_module_without_translation()
    {
        $this->app['config']->set('modules.default.structure.translations', 'resources/lang');
        $this->app['config']->set('modules.generate', [
            'translation' => false,
        ]);

        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $file = $this->modulePath . '/resources/lang/en.php';
        $this->assertFileNotExists($file);

        $dir = $this->modulePath . '/resources/lang';
        $this->assertDirectoryNotExists($dir);
    }

    /** @test */
    public function should_generate_a_module_with_routes()
    {
        $this->app['config']->set('modules.default.structure.routes', 'routes');
        $this->app['config']->set('modules.generate', [
            'routes' => true,
        ]);

        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $dir = $this->modulePath . '/routes';
        $this->assertDirectoryExists($dir);
    }

    /** @test */
    public function should_generate_a_module_with_routes_in_custom_location()
    {
        $this->app['config']->set('modules.default.structure.routes', 'routing');
        $this->app['config']->set('modules.generate', [
            'routes' => true,
        ]);

        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $dir = $this->modulePath . '/routing';
        $this->assertDirectoryExists($dir);
    }

    /** @test */
    public function should_generate_a_module_without_routes()
    {
        $this->app['config']->set('modules.default.structure.routes', 'routes');
        $this->app['config']->set('modules.generate', [
            'routes' => false,
        ]);

        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $dir = $this->modulePath . '/routes';
        $this->assertDirectoryNotExists($dir);
    }

    /** @test */
    public function should_generate_a_module_with_web_routes()
    {
        $this->app['config']->set('modules.default.structure.routes', 'routes');
        $this->app['config']->set('modules.default.routing', [ 'web' ]);
        $this->app['config']->set('modules.generate', [
            'routes' => true,
        ]);

        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $file = $this->modulePath . '/routes/web.php';
        $this->assertFileExists($file);
    }

    /** @test */
    public function should_generate_a_module_without_web_routes()
    {
        $this->app['config']->set('modules.default.structure.routes', 'routes');
        $this->app['config']->set('modules.default.routing', [ 'invalid' ]);
        $this->app['config']->set('modules.generate', [
            'routes' => true,
        ]);

        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $file = $this->modulePath . '/routes/web.php';
        $this->assertFileNotExists($file);
    }

    /** @test */
    public function should_generate_a_module_with_api_routes()
    {
        $this->app['config']->set('modules.default.structure.routes', 'routes');
        $this->app['config']->set('modules.default.routing', [ 'api' ]);
        $this->app['config']->set('modules.generate', [
            'routes' => true,
        ]);

        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $file = $this->modulePath . '/routes/api.php';
        $this->assertFileExists($file);
    }

    /** @test */
    public function should_generate_a_module_without_api_routes()
    {
        $this->app['config']->set('modules.default.structure.routes', 'routes');
        $this->app['config']->set('modules.default.routing', [ 'invalid' ]);
        $this->app['config']->set('modules.generate', [
            'routes' => true,
        ]);

        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $file = $this->modulePath . '/routes/api.php';
        $this->assertFileNotExists($file);
    }

    /** @test */
    public function should_generate_a_module_with_simple_routes()
    {
        $this->app['config']->set('modules.default.structure.routes', '');
        $this->app['config']->set('modules.default.routing', [ 'simple' ]);
        $this->app['config']->set('modules.generate', [
            'routes' => true,
        ]);

        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $file = $this->modulePath . '/routes.php';
        $this->assertFileExists($file);
    }

    /** @test */
    public function should_generate_a_module_without_simple_routes()
    {
        $this->app['config']->set('modules.default.structure.routes', '');
        $this->app['config']->set('modules.default.routing', [ 'invalid' ]);
        $this->app['config']->set('modules.generate', [
            'routes' => true,
        ]);

        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $file = $this->modulePath . '/routes.php';
        $this->assertFileNotExists($file);
    }

    /** @test */
    public function should_generate_a_module_with_migration()
    {
        $this->app['config']->set('modules.default.structure.migrations', 'database/migrations');
        $this->app['config']->set('modules.generate', [
            'migration' => true,
        ]);

        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $dir = $this->modulePath . '/database/migrations';
        $this->assertDirectoryExists($dir);
    }

    /** @test */
    public function should_generate_a_module_with_migration_in_custom_location()
    {
        $this->app['config']->set('modules.default.structure.migrations', 'migrations');
        $this->app['config']->set('modules.generate', [
            'migration' => true,
        ]);

        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $dir = $this->modulePath . '/migrations';
        $this->assertDirectoryExists($dir);
    }

    /** @test */
    public function should_generate_a_module_without_migration()
    {
        $this->app['config']->set('modules.default.structure.migrations', 'database/migrations');
        $this->app['config']->set('modules.generate', [
            'migration' => false,
        ]);

        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $dir = $this->modulePath . '/database/migrations';
        $this->assertDirectoryNotExists($dir);
    }

    /** @test */
    public function should_generate_a_module_with_seeder()
    {
        $this->app['config']->set('modules.default.structure.seeds', 'database/seeds');
        $this->app['config']->set('modules.generate', [
            'seeder' => true,
        ]);
        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $file = $this->modulePath . '/database/seeds/FooBarSeeder.php';
        $this->assertFileExists($file);
    }

    /** @test */
    public function should_generate_a_module_with_seeder_in_custom_location()
    {
        $this->app['config']->set('modules.default.structure.seeds', 'seeds');
        $this->app['config']->set('modules.generate', [
            'seeder' => true,
        ]);
        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $file = $this->modulePath . '/seeds/FooBarSeeder.php';
        $this->assertFileExists($file);
    }

    /** @test */
    public function should_generate_a_module_without_seeder()
    {
        $this->app['config']->set('modules.default.structure.seeds', 'database/seeds');
        $this->app['config']->set('modules.generate', [
            'seeder' => false,
        ]);
        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $file = $this->modulePath . '/database/seeds/FooBarSeeder.php';
        $this->assertFileNotExists($file);

        $dir = $this->modulePath . '/database/seeds';
        $this->assertDirectoryNotExists($dir);
    }

    /** @test */
    public function should_generate_a_module_with_factory()
    {
        $this->app['config']->set('modules.default.structure.factories', 'database/factories');
        $this->app['config']->set('modules.generate', [
            'factory' => true,
        ]);
        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $file = $this->modulePath . '/database/factories/FooBarFactory.php';
        $this->assertFileExists($file);
    }

    /** @test */
    public function should_generate_a_module_with_factory_in_custom_location()
    {
        $this->app['config']->set('modules.default.structure.factories', 'factories');
        $this->app['config']->set('modules.generate', [
            'factory' => true,
        ]);
        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $file = $this->modulePath . '/factories/FooBarFactory.php';
        $this->assertFileExists($file);
    }

    /** @test */
    public function should_generate_a_module_without_factory()
    {
        $this->app['config']->set('modules.default.structure.factories', 'database/factories');
        $this->app['config']->set('modules.generate', [
            'factory' => false,
        ]);
        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $file = $this->modulePath . '/database/factories/FooBarSeeder.php';
        $this->assertFileNotExists($file);

        $dir = $this->modulePath . '/database/factories';
        $this->assertDirectoryNotExists($dir);
    }

    /** @test */
    public function should_generate_a_module_with_rule()
    {
        $this->app['config']->set('modules.default.structure.rules', 'Rules');
        $this->app['config']->set('modules.generate', [
            'rule' => true,
        ]);
        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $file = $this->modulePath . '/Rules/FooBar.php';
        $this->assertFileExists($file);

        $content = file_get_contents($file);
        $this->assertNotFalse(strpos($content, 'namespace App\\Modules\\FooBar\\Rules;'));
        $this->assertNotFalse(strpos($content, 'class FooBar implements Rule'));
    }

    /** @test */
    public function should_generate_a_module_with_rule_in_custom_location()
    {
        $this->app['config']->set('modules.default.structure.rules', 'Validation/Rules');
        $this->app['config']->set('modules.generate', [
            'rule' => true,
        ]);
        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $file = $this->modulePath . '/Validation/Rules/FooBar.php';
        $this->assertFileExists($file);

        $content = file_get_contents($file);
        $this->assertNotFalse(strpos($content, 'namespace App\\Modules\\FooBar\\Validation\\Rules;'));
        $this->assertNotFalse(strpos($content, 'class FooBar implements Rule'));
    }

    /** @test */
    public function should_generate_a_module_without_rule()
    {
        $this->app['config']->set('modules.default.structure.rules', 'Rules');
        $this->app['config']->set('modules.generate', [
            'rule' => false,
        ]);
        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $file = $this->modulePath . '/Rules/FooBar.php';
        $this->assertFileNotExists($file);

        $dir = $this->modulePath . '/Rules';
        $this->assertDirectoryNotExists($dir);
    }

    /** @test */
    public function should_generate_a_module_with_config()
    {
        $this->app['config']->set('modules.generate', [
            'config' => true,
        ]);
        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $file = $this->modulePath . '/config.php';
        $this->assertFileExists($file);
    }

    /** @test */
    public function should_generate_a_module_without_config()
    {
        $this->app['config']->set('modules.generate', [
            'config' => false,
        ]);
        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $file = $this->modulePath . '/config.php';
        $this->assertFileNotExists($file);
    }

    /** @test */
    public function should_generate_a_module_with_helpers()
    {
        $this->app['config']->set('modules.default.structure.helpers', '');
        $this->app['config']->set('modules.generate', [
            'helpers' => true,
        ]);
        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $file = $this->modulePath . '/helpers.php';
        $this->assertFileExists($file);
    }

    /** @test */
    public function should_generate_a_module_with_helpers_in_custom_location()
    {
        $this->app['config']->set('modules.default.structure.helpers', 'misc');
        $this->app['config']->set('modules.generate', [
            'helpers' => true,
        ]);
        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $file = $this->modulePath . '/misc/helpers.php';
        $this->assertFileExists($file);
    }

    /** @test */
    public function should_generate_a_module_without_helpers()
    {
        $this->app['config']->set('modules.default.structure.helpers', 'misc');
        $this->app['config']->set('modules.generate', [
            'helpers' => false,
        ]);
        $code = $this->artisan('make:module', ['name' => 'FooBar']);
        $this->assertSame(0, $code);

        $file = $this->modulePath . '/misc/helpers.php';
        $this->assertFileNotExists($file);

        $dir = $this->modulePath . '/misc';
        $this->assertDirectoryNotExists($dir);
    }
}
