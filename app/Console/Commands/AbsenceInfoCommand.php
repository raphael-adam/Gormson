<?php

namespace App\Console\Commands;


use App\Contracts\MessageServiceContract;
use App\Repository\AbsenceRepositoryInterface;
use Illuminate\Console\Command;

class AbsenceInfoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:AbsenceInfoCommand';

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
    //private $events;
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
        $absenceRepository = app(AbsenceRepositoryInterface::class);
        $currentlyAbsent = $absenceRepository->currentlyAbsent();
        $nextWeek = $absenceRepository->absentInDayRange(0, 7);


        $message = app(MessageServiceContract::class);
        $message->setCurrentlyAbsent($currentlyAbsent);
        $message->setAbsentNextWeek($nextWeek);
        $message->send();
    }
}
