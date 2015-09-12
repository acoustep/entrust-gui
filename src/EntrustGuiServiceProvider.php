<?php namespace Acoustep\EntrustGui;

use Acoustep\EntrustGui\Console\Commands\GenerateModels;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Config;

/**
 * EntrustGuiServiceProvider
 *
 * @author Mitch Stanley <acoustep+entrustgui@fastmail.co.uk>
 */
class EntrustGuiServiceProvider extends ServiceProvider
{


    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot and configure the application paths
     *
     * @return void
     */
    public function boot()
    {

        $this->loadViewsFrom(realpath(__DIR__.'/../views'), 'entrust-gui');
        $this->setupRoutes($this->app->router);
        $this->loadTranslationsFrom(realpath(__DIR__.'/../translations'), 'entrust-gui');


        // This is for conig
        $this->publishes(
            [__DIR__.'/../config/entrust-gui.php' => config_path('entrust-gui.php')],
            'config'
        );

        // This is for views
        $this->publishes(
            [__DIR__.'/../views' => base_path('resources/views/vendor/entrust-gui')],
            'views'
        );

        // This is for translations
        $this->publishes(
            [__DIR__.'/../translations' => base_path('resources/lang/vendor/entrust-gui')],
            'translations'
        );
        // Register commands
        $this->commands('command.entrust-gui.models');
    }

    /**
     * Define the routes for the application.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function setupRoutes(Router $router)
    {
        $router->group(
            ['namespace' => 'Acoustep\EntrustGui\Http\Controllers'],
            function ($router) {
                  include __DIR__.'/Http/routes.php';
            }
        );
    }

    /**
    * Register the service provider.
    *
    * @return void
    */
    public function register()
    {
        $this->app->register('Zizaco\Entrust\EntrustServiceProvider');
        $this->app->register('Prettus\Repository\Providers\RepositoryServiceProvider');
        $this->app->bind(
            'Acoustep\EntrustGui\Repositories\UserRepository',
            'Acoustep\EntrustGui\Repositories\UserRepositoryEloquent'
        );
        $this->app->bind(
            'Acoustep\EntrustGui\Repositories\RoleRepository',
            'Acoustep\EntrustGui\Repositories\RoleRepositoryEloquent'
        );
        $this->app->bind(
            'Acoustep\EntrustGui\Repositories\PermissionRepository',
            'Acoustep\EntrustGui\Repositories\PermissionRepositoryEloquent'
        );

        $this->registerCommands();
    }
    /**
    * Register commands.
    *
    * @return void
    */
    protected function registerCommands()
    {
        $this->registerGenerateModelsCommand();
    }

    /**
    * Register the export models command.
    *
    * @return void
    */
    protected function registerGenerateModelsCommand()
    {
        $this->app->singleton('command.entrust-gui.models', function ($app) {
            return new GenerateModels();
        });
    }

    /**
    * List of commands provided by the package.
    *
    * @return void
    */
    public function provides()
    {
        return ['command.entrust-gui.models'];
    }
}
