<?php
// Webhook
// https://chat.googleapis.com/v1/spaces/AAAA8rdNvns/messages?key=AIzaSyDdI0hCZtE6vySjMm-WEfRq3CPzqKqqsHI&token=K6f1SsBqrFXnC9httEJc4wYOPpg52S5Xqyux3BpdWSg%3D

namespace App\Console\Commands;

use App\Providers\CalenderEventsServiceProvider;
use App\Vacation;
use Carbon\Carbon;
use ICal\ICal;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Calendar;
use phpDocumentor\Reflection\Types\False_;
use phpDocumentor\Reflection\Types\Null_;

class VacationData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:VacationData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // get the data
        $icalData = $this->getIcalData();
        ::parseCalendar($icalData);

    }

    private function getIcalData()
    {
        return ;
    }
}


