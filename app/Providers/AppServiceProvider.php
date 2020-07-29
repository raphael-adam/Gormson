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
        $this->app->bind(IcsDataRepositoryContract::class, IcsDataRepository::class);
        $this->app->bind(ParseCalendarContract::class, ParseCalendarRepository::class);
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
