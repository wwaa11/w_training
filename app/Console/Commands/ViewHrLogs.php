<?php
namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ViewHrLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hr:logs
                            {--month= : Filter by month (YYYY-MM format)}
                            {--action= : Filter by action type}
                            {--user= : Filter by user ID or name}
                            {--project= : Filter by project ID or name}
                            {--limit=50 : Number of log entries to show}
                            {--json : Output in JSON format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'View HR action logs with filtering options';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Set timezone to Asia/Bangkok
        date_default_timezone_set('Asia/Bangkok');

        $month   = $this->option('month') ?: Carbon::now()->setTimezone('Asia/Bangkok')->format('Y-m');
        $action  = $this->option('action');
        $user    = $this->option('user');
        $project = $this->option('project');
        $limit   = (int) $this->option('limit');
        $json    = $this->option('json');

        $logFile = storage_path("logs/HR_Log_{$month}.log");

        if (! File::exists($logFile)) {
            $this->error("Log file not found: {$logFile}");
            return 1;
        }

        $logs = $this->parseLogFile($logFile, $action, $user, $project, $limit);

        if ($json) {
            $this->output->write(json_encode($logs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        } else {
            $this->displayLogs($logs);
        }

        return 0;
    }

    /**
     * Parse log file and apply filters
     */
    private function parseLogFile($logFile, $action = null, $user = null, $project = null, $limit = 50)
    {
        $lines = File::lines($logFile);
        $logs  = [];
        $count = 0;

        foreach ($lines as $line) {
            if ($count >= $limit) {
                break;
            }

            try {
                // Remove Laravel's timestamp prefix if present
                $jsonStart = strpos($line, '{');
                if ($jsonStart === false) {
                    continue;
                }

                $jsonPart = substr($line, $jsonStart);
                $logData  = json_decode($jsonPart, true);
                if (! $logData) {
                    continue;
                }

                // Apply filters
                if ($action && $logData['action'] !== $action) {
                    continue;
                }

                if ($user && ! $this->matchesUser($logData, $user)) {
                    continue;
                }

                if ($project && ! $this->matchesProject($logData, $project)) {
                    continue;
                }

                $logs[] = $logData;
                $count++;
            } catch (\Exception $e) {
                continue;
            }
        }

        return array_reverse($logs); // Show newest first
    }

    /**
     * Check if log entry matches user filter
     */
    private function matchesUser($logData, $user)
    {
        $userLower = strtolower($user);
        return (
            (isset($logData['data']['user_id']) && $logData['data']['user_id'] == $user) ||
            (isset($logData['data']['user_name']) && stripos($logData['data']['user_name'], $userLower) !== false) ||
            (isset($logData['data']['userid']) && stripos($logData['data']['userid'], $userLower) !== false) ||
            (isset($logData['user_name']) && stripos($logData['user_name'], $userLower) !== false) ||
            (isset($logData['userid']) && stripos($logData['userid'], $userLower) !== false)
        );
    }

    /**
     * Check if log entry matches project filter
     */
    private function matchesProject($logData, $project)
    {
        $projectLower = strtolower($project);
        return (
            (isset($logData['data']['project_id']) && $logData['data']['project_id'] == $project) ||
            (isset($logData['data']['project_name']) && stripos($logData['data']['project_name'], $projectLower) !== false)
        );
    }

    /**
     * Display logs in a formatted table
     */
    private function displayLogs($logs)
    {
        if (empty($logs)) {
            $this->info('No log entries found matching the criteria.');
            return;
        }

        $headers = ['Timestamp', 'Action', 'User', 'Project', 'Details'];
        $rows    = [];

        foreach ($logs as $log) {
            $timestamp = Carbon::parse($log['timestamp'])->setTimezone('Asia/Bangkok')->format('Y-m-d H:i:s');
            $action    = $log['action'];
            $user      = $log['user_name'] ?? 'Unknown';
            $project   = $log['data']['project_name'] ?? 'N/A';

            // Create a summary of details
            $details = $this->summarizeDetails($log['data']);

            $rows[] = [$timestamp, $action, $user, $project, $details];
        }

        $this->table($headers, $rows);
        $this->info("Showing " . count($logs) . " log entries.");
    }

    /**
     * Create a summary of log details
     */
    private function summarizeDetails($data)
    {
        $summary = [];

        if (isset($data['time_title'])) {
            $summary[] = "Time: " . $data['time_title'];
        }

        if (isset($data['seat_number'])) {
            $summary[] = "Seat: " . $data['seat_number'];
        }

        if (isset($data['group'])) {
            $summary[] = "Group: " . $data['group'];
        }

        if (isset($data['affected_count'])) {
            $summary[] = "Count: " . $data['affected_count'];
        }

        if (isset($data['export_type'])) {
            $summary[] = "Export: " . $data['export_type'];
        }

        if (isset($data['import_type'])) {
            $summary[] = "Import: " . $data['import_type'];
        }

        return implode(', ', $summary) ?: 'No additional details';
    }
}
