<?php

namespace App\Providers;


use App\Contracts\ParseCalendarContract;
use App\Http\Controllers\AbsenceRepository;
use App\Repository\AbsenceRepositoryInterface;
use App\Service\MessageService;
use App\Contracts\MessageServiceContract;
use App\Service\ParseCalendar;
use App\Service\IcsDataService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('IcsData', IcsDataService::class);
        $this->app->bind(AbsenceRepositoryInterface::class, AbsenceRepository::class);
        $this->app->bind(MessageServiceContract::class, MessageService::class);
        $this->app->bind(ParseCalendarContract::class, ParseCalendar::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
