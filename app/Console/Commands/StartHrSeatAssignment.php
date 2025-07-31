<?php
namespace App\Console\Commands;

use App\Jobs\HrProjectSeatAssignment;
use Illuminate\Console\Command;

class StartHrSeatAssignment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hr:start-seat-assignment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start the HR project seat assignment job that runs every minute';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting HR Project Seat Assignment Job...');

        // Dispatch the first job
        HrProjectSeatAssignment::dispatch();

        $this->info('HR Project Seat Assignment Job has been started and will run every minute.');
        $this->info('The job will automatically reschedule itself every minute.');

        return Command::SUCCESS;
    }
}
