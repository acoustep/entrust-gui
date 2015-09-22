<?php namespace Acoustep\EntrustGui\Tests;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Router;
use Orchestra\Testbench\TestCase as BaseTestCase;
use ReflectionClass;

/**
 * Class     TestCase
 *
 * @package  Arcanedev\LogViewer\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class TestCase extends BaseTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var array */

    protected static $locales = ['en'];

    /* ------------------------------------------------------------------------------------------------
     |  Main functions
     | ------------------------------------------------------------------------------------------------
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Bench Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get package providers.
     *
     * @param  Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            'Acoustep\\EntrustGui\\EntrustGuiServiceProvider'
        ];
    }

    /**
     * Get package aliases.
     *
     * @param  Application  $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  Application  $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['path.storage'] = __DIR__ . '/fixtures';
         $app['config']->set('database.default', 'testbench');
          $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
          ]);
        $app['config']->set('auth.model', 'Acoustep\EntrustGui\Models\User');
        $app['config']->set('entrust.role', 'Acoustep\EntrustGui\Models\Role');
        $app['config']->set('entrust.permission', 'Acoustep\EntrustGui\Models\Permission');
        $app['config']->set('entrust-gui.layout', 'entrust-gui::app');
        $app['config']->set('entrust-gui.route-prefix', 'entrust-gui');
        $app['config']->set('entrust-gui.confirmable', false);

        $this->registerRoutes($app['router']);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Custom assertions
     | ------------------------------------------------------------------------------------------------
     */


    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get Illuminate Filesystem
     *
     * @return \Illuminate\Filesystem\Filesystem
     */
    public function illuminateFile()
    {
        return app('files');
    }

    /**
     * Get Filesystem utility
     *
     * @return \Arcanedev\LogViewer\Utilities\Filesystem
     */
    protected function filesystem()
    {
        return app('acoustep.entrust-gui.filesystem');
    }

    /**
     * Get translator repository
     *
     * @return \Illuminate\Translation\Translator
     */
    protected function trans()
    {
        return $this->app['translator'];
    }

    /**
     * Get config repository
     *
     * @return \Illuminate\Config\Repository
     */
    protected function config()
    {
        $this->app['config']['auth']['model'] = 'Acoustep\EntrustGui\Models\User';
        $this->app['config']['entrust']['role'] = 'Acoustep\EntrustGui\Models\Role';
        $this->app['config']['entrust']['role'] = 'Acoustep\EntrustGui\Models\Permission';
        return $this->app['config'];
    }

    /**
     * Get config path
     *
     * @return string
     */
    protected function getConfigPath()
    {
        return realpath(config_path());
    }

    /**
     * Register all routes
     *
     * @param  Router  $router
     */
    private function registerRoutes(Router $router)
    {
        $router->group([
            'namespace' => 'Acoustep\\EntrustGui\\Http\\Controllers'
        ], function(Router $router) {
            $router->group([
              'prefix' => 'entrust-gui',
              'middleware' => null,
            ], function(Router $router) {
              $router->get('users', ['uses' => 'UsersController@index', 'as' => 'entrust-gui::users.index']);
              $router->get('users/create', ['uses' => 'UsersController@create', 'as' => 'entrust-gui::users.create']);
              $router->post('users', ['uses' => 'UsersController@store', 'as' => 'entrust-gui::users.store']);
              $router->get('users/{id}/edit', ['uses' => 'UsersController@edit', 'as' => 'entrust-gui::users.edit']);
              $router->put('users/{id}', ['uses' => 'UsersController@update', 'as' => 'entrust-gui::users.update']);
              $router->delete('users/{id}', ['uses' => 'UsersController@destroy', 'as' => 'entrust-gui::users.destroy']);

              $router->get('roles', ['uses' => 'RolesController@index', 'as' => 'entrust-gui::roles.index']);
              $router->get('roles/create', ['uses' => 'RolesController@create', 'as' => 'entrust-gui::roles.create']);
              $router->post('roles', ['uses' => 'RolesController@store', 'as' => 'entrust-gui::roles.store']);
              $router->get('roles/{id}/edit', ['uses' => 'RolesController@edit', 'as' => 'entrust-gui::roles.edit']);
              $router->put('roles/{id}', ['uses' => 'RolesController@update', 'as' => 'entrust-gui::roles.update']);
              $router->delete('roles/{id}', ['uses' => 'RolesController@destroy', 'as' => 'entrust-gui::roles.destroy']);

              $router->get('permissions', ['uses' => 'PermissionsController@index', 'as' => 'entrust-gui::permissions.index']);
              $router->get(
                  'permissions/create',
                  [
                      'uses' => 'PermissionsController@create',
                      'as' => 'entrust-gui::permissions.create'
                  ]
              );
              $router->post('permissions', ['uses' => 'PermissionsController@store', 'as' => 'entrust-gui::permissions.store']);
              $router->get(
                  'permissions/{id}/edit',
                  [
                      'uses' => 'PermissionsController@edit',
                      'as' => 'entrust-gui::permissions.edit'
                  ]
              );
              $router->put(
                  'permissions/{id}',
                  [
                      'uses' => 'PermissionsController@update',
                      'as' => 'entrust-gui::permissions.update'
                  ]
              );
              $router->delete(
                  'permissions/{id}',
                  [
                      'uses' => 'PermissionsController@destroy',
                      'as' => 'entrust-gui::permissions.destroy'
                  ]
              );
            });
        });
    }
}
