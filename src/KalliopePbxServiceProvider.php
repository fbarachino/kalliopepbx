<?php

namespace fbarachino\kalliopepbx;
use Illuminate\Support\ServiceProvider;

class KalliopePbxServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish the configuration file
        $this->publishes([
            __DIR__.'/config/kalliopepbx.php' => config_path('kalliopepbx.php'),
        ], 'kalliopepbx-config');
    }
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/kalliopepbx.php', 'kalliopepbx');
    }
}