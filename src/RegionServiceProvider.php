<?php


namespace Myischen\Region;

use Myischen\Region\Console\RegionUpdate;
use Illuminate\Support\ServiceProvider;

class RegionServiceProvider extends ServiceProvider
{

    public function boot()
{
    if ($this->app->runningInConsole()) {
        $this->commands([
            RegionUpdate::class,
        ]);
    }
}

    public function register()
    {
        $this->publishes([__DIR__ . '/publishable/database/' => database_path()]);
    }

}
