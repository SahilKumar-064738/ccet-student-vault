<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Clean up old OTPs
Schedule::command('model:prune', ['--model' => 'App\\Models\\Otp'])->daily();

// Database backup
Schedule::command('backup:run')->daily()->at('02:00');

// Clean up old activity logs (keep last 90 days)
Schedule::call(function () {
    \App\Models\ActivityLog::where('created_at', '<', now()->subDays(90))->delete();
})->daily()->at('03:00');
