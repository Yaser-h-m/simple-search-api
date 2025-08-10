<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
    $this->info('Inspired!'. Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('inspire')->everyMinute();

Schedule::call(function () {
    Artisan::call('inspire');
})->everyMinute();