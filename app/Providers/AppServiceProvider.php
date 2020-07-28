<?php

namespace App\Providers;

use App\Leave\GetIcsData;
use App\Leave\ParseCalendar;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ParseCalendar::class, function($app){
            // contract
            return new ParseCalendar();
        }) ;

        $this->app->singleton(GetIcsData::class, function($app) {
            return new GetIcsData();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
