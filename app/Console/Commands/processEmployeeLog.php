<?php

namespace App\Console\Commands;

use App\Services\AttendService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class processEmployeeLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process Attendance Log Form FingerPrint Device';

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
        $descriptorSpecs = [["pipe", "r"], ["pipe", "w"], ["file", base_path('error.txt'), "a"]];
        $process = proc_open(base_path('venv/bin/python3') . " " . base_path('fingerprint.py'), $descriptorSpecs, $pipes);

        if (is_resource($process)) {
            $logs = json_decode(stream_get_contents($pipes[1])) ?? [];
            fclose($pipes[1]);
        }

        proc_close($process);

        $bar = $this->output->createProgressBar(count($logs));
        $bar->start();
        foreach ($logs as $log) {
            AttendService::processEmployeeLog(intval($log->user_id), Carbon::parse($log->timestamp));
            $bar->advance();
            Log::channel('app')->info("$log->user_id - $log->timestamp");
        }
        $bar->finish();
        echo PHP_EOL;
        Log::channel('app')->info("=====================");
        $this->info('The command was successful!');
    }
}
