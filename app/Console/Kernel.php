<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\GenerateSitemap;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        GenerateSitemap::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('sitemap:generate')->daily();
    }
}
