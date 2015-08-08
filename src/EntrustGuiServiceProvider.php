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
    ]);

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

  public function register()
  {
    $this->app->bind('Acoustep\EntrustGui\Repositories\UserRepository','Acoustep\EntrustGui\Repositories\UserRepositoryEloquent');
    $this->app->bind('Acoustep\EntrustGui\Repositories\RoleRepository','Acoustep\EntrustGui\Repositories\RoleRepositoryEloquent');
  }
}

