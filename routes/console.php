<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;

// Run queue worker
Schedule::command('queue:work --stop-when-empty')
    ->everyMinute()
    ->withoutOverlapping();

// Run CSV import every 5 minutes
Schedule::command('vds:importcsv')
    ->everyFiveMinutes()
    ->withoutOverlapping();
