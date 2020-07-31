<?php

namespace App\Console\Commands;

use App\Facade\IcsData;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\AbsenceController;
use App\Repository\IcsDataRepository;
use App\Repository\MessageRepository;
use App\Repository\ParseCalendarRepository;
use Illuminate\Console\Command;

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
        $rawData = IcsData::get();

        $calendarParser = new ParseCalendarRepository();
        $calendarParser->setRawCalendar($rawData);
        $events = $calendarParser->parsedCalendar();

        $employeesController = new EmployeesController();
        $absenceController = new absenceController();

        foreach ($events as $event) {
            $employeesController->store($event);
        }

        foreach ($events as $event) {
            $absenceController->store($event);
        }

        $currentlyAbsent = $absenceController->onAbsence();
        $nextWeek = $absenceController->onAbsenceNextWeek();

        $message = new MessageRepository($currentlyAbsent, $nextWeek); // Contract
        $message->send();
    }
}
