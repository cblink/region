<?php


namespace Cblink\Region;


use Illuminate\Support\ServiceProvider;

class RegionServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->publishes([__DIR__ . '/publishable/database/' => database_path()]);
        $this->publishes([__DIR__ . '/publishable/config/' => config_path()]);
    }

}