<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('trending:fetch')->hourly()->withoutOverlapping();
Schedule::command('trending:fetch')->cron('0 */6 * * *')->timezone('Asia/Jakarta');
