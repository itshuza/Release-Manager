<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // run a scheduled job to create archives for all projects daily at 2 AM
        $schedule->call(function () {
            \App\Models\Project::all()->each(function ($project) {
                $version = 'auto-' . now()->format('Ymd-His');
                \Artisan::call('archive:create', [
                    'project_id' => $project->id,
                    'version'    => $version
                ]);
            });
        })->dailyAt('02:00');
    }
}
