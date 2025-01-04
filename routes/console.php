<?php

use App\Models\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('clean:expired-tokens-and-users', function () {
    $this->comment(Inspiring::quote());
})->purpose('Clean token expired')->everyFifteenMinutes();
