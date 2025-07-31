<?php
namespace App\Console\Commands;

use App\Jobs\HrAssignSeatForAttendance;
use App\Models\HrAttend;
use Illuminate\Console\Command;

class AssignSeatsForExistingAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hrd:assign-seats-for-existing {--project-id= : Specific project ID to process}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign seats for existing attendance records that don\'t have seat assignments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting seat assignment for existing attendance records...');

        $query = HrAttend::with(['project', 'time', 'user'])
            ->where('attend_delete', false)
            ->whereHas('project', function ($q) {
                $q->where('project_seat_assign', true)
                    ->where('project_active', true)
                    ->where('project_delete', false);
            })
            ->whereDoesntHave('seat');

        // Filter by specific project if provided
        if ($projectId = $this->option('project-id')) {
            $query->where('project_id', $projectId);
            $this->info("Processing only project ID: {$projectId}");
        }

        $attendanceRecords = $query->get();

        if ($attendanceRecords->isEmpty()) {
            $this->info('No attendance records found that need seat assignment.');
            return;
        }

        $this->info("Found {$attendanceRecords->count()} attendance records that need seat assignment.");

        $bar = $this->output->createProgressBar($attendanceRecords->count());
        $bar->start();

        foreach ($attendanceRecords as $attendance) {
            // Dispatch seat assignment job
            HrAssignSeatForAttendance::dispatch($attendance->id);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Seat assignment jobs have been queued for processing.');
        $this->info('Check the queue worker to see the progress.');
    }
}
