<?php

namespace Jumilla\Erb2Blade;

/**
 * package 'jumilla/erb2blade': Laravel4 Service Provider.
 *
 * @author Fumio Furukawa <fumio@jumilla.me>
 */
class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Add package commands
        $this->setupCommands([
            'commands.view.erb2blade' => 'Jumilla\Erb2Blade\Console\Erb2BladeCommand',
        ]);
    }

    /**
     * Setup package's commands.
     *
     * @param  array  $commands
     * @return void
     */
    public function setupCommands(array $commands)
    {
        $names = [];

        foreach ($commands as $name => $class) {
            $this->app->singleton($name, function ($app) use ($class) {
                return new $class($app);
            });

            $names[] = $name;
        }

        // Now register all the commands
        $this->commands($names);
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
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
