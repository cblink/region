<?php


namespace Myischen\Region;


use Illuminate\Support\ServiceProvider;

class RegionServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->publishes([__DIR__ . '/publishable/database/' => database_path()]);
    }

}