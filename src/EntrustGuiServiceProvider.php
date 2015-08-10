<?php namespace	Acoustep\EntrustGui;

/**
 * 
 * @author Mitch Stanley <acoustep+entrustgui@fastmail.co.uk>
 */

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class EntrustGuiServiceProvider extends ServiceProvider{


	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	public function boot()
	{

		$this->loadViewsFrom(realpath(__DIR__.'/../views'), 'entrust-gui');
		$this->setupRoutes($this->app->router);
    $this->loadTranslationsFrom(realpath(__DIR__.'/../translations'), 'entrust-gui');


		// this  for conig
    $this->publishes([
      __DIR__.'/../config/entrust-gui.php' => config_path('entrust-gui.php'),
    ], 'config');

		// this  for conig
    $this->publishes([
      __DIR__.'/../views' => base_path('resources/views/vendor/entrust-gui'),
    ], 'views');
	}

	/**
	 * Define the routes for the application.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function setupRoutes(Router $router)
	{
		$router->group(['namespace' => 'Acoustep\EntrustGui\Http\Controllers'], function($router)
		{
			require __DIR__.'/Http/routes.php';
		});
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
    $this->app->bind('Acoustep\EntrustGui\Repositories\UserRepository','Acoustep\EntrustGui\Repositories\UserRepositoryEloquent');
    $this->app->bind('Acoustep\EntrustGui\Repositories\RoleRepository','Acoustep\EntrustGui\Repositories\RoleRepositoryEloquent');
  }
}

