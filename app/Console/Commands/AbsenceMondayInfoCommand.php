<?php

namespace App\Console\Commands;

use App\Contracts\MessageServiceContract;
use App\Repository\AbsenceRepositoryInterface;
use Illuminate\Console\Command;

class AbsenceMondayInfoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:AbsenceMondayInfoCommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Info who is not there on the next Monday';

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
        $absenceRepository = app(AbsenceRepositoryInterface::class);
        $absentMonday = $absenceRepository->absentInDayRange(1, 3);

        $message = app(MessageServiceContract::class);
        $message->setAbsentMonday($absentMonday);
        $message->send();
    }
}
