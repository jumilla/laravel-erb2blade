<?php namespace Jumilla\Erb2Blade;

/**
* package 'jumilla/erb2blade': Laravel4 Service Provider
*
* @author Fumio Furukawa <fumio.furukawa@gmail.com>
*/
class ServiceProvider extends \Illuminate\Support\ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
	}

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		// Add package commands
		$this->setupCommands([
			['name' => 'commands.view.erb2blade', 'class' => 'Jumilla\Erb2Blade\Commands\Erb2BladeCommand'],
		]);
	}

	/**
	 * setup package's commands.
	 *
	 * @param  $command array
	 * @return void
	 */
	function setupCommands($commands)
	{
		$names = [];

		foreach ($commands as $command) {
			$this->app[$command['name']] = $this->app->share(function($app) use($command) {
				return new $command['class']($app);
			});

			$names[] = $command['name'];
		}

		// Now register all the commands
		$this->commands($names);
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['commands.view.erb2blade'];
	}

}
