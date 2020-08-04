<?php

namespace App\Console\Commands;

use App\Contracts\ParseCalendarContract;
use App\Facade\IcsData;
use App\Repository\AbsenceRepositoryInterface;
use App\Contracts\MessageServiceContract;
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
     * @return int
     */
    public function handle()
    {
        $rawData = IcsData::get();
        $calender = app(ParseCalendarContract::class);
        $events = $calender->parsedCalendar($rawData);

        $absenceRepository = app(AbsenceRepositoryInterface::class);
        foreach ($events as $event) {
            $absenceRepository->create($event);
        }

        $currentlyAbsent = $absenceRepository->currentlyAbsent();
        $nextWeek = $absenceRepository->absentNextWeek();

        $message = app(MessageServiceContract::class);
        $message->setCurrentlyAbsent($currentlyAbsent);
        $message->setNextWeek($nextWeek);
        $message->send();
    }
}
