<?php

namespace App\Console\Commands;

use App\Contracts\MessageServiceContract;
use App\Repository\AbsenceRepositoryInterface;
use Illuminate\Console\Command;

class AbsenceUpdateInfoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:AbsenceUpdateInfoCommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending if updates occured';

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
        $updates = $absenceRepository->absenceUpdated();

        $message = app(MessageServiceContract::class);
        $message->setAbsentUpdate($updates);
        $message->send();

    }
}
