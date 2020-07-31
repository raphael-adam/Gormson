<?php

namespace App\Providers;

use App\Contracts\IcsDataRepositoryContract;
use App\Repository\IcsDataRepository;

use App\Contracts\ParseCalendarContract;
use App\Repository\ParseCalendarRepository;
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
        $this->app->bind('IcsData', IcsDataRepository::class); // ToDo rename to service

        $this->app->bind('ParseCalendar', ParseCalendarRepository::class); // ToDo rename to service

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
