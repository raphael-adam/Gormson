<?php

namespace App\Console\Commands;

use App\Http\Controllers\LeaveController;
use App\Http\Controllers\EmployeesController;
use App\Repository\IcsDataRepository;
use App\Repository\MessageRepository;
use App\Repository\ParseCalendarRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class MessageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:MessageCommand';

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

        $calendarData = new IcsDataRepository();
        $rawData = $calendarData->get();

        $calendarParser = new ParseCalendarRepository();
        $calendarParser->setRawCalendar($rawData);
        $events = $calendarParser->parsedCalendar();

        $leaveController = new LeaveController();
        $employeesController = new EmployeesController();

        foreach ($events as $event) {
            $employeesController->store($event);
            $leaveController->store($event);
        }

        $onLeave = $leaveController->onLeave();
        $nextWeek = $leaveController->onLeaveNextWeek();

        $message = new MessageRepository($onLeave, $nextWeek);
        $message->send();
    }
}
