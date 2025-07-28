<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Agendar relatório diário do WhatsApp
if (config('whatsapp.reports.daily.enabled')) {
    Schedule::command('whatsapp:send-daily-report')
        ->dailyAt(config('whatsapp.reports.daily.time', '18:00'))
        ->timezone(config('whatsapp.reports.daily.timezone', 'America/Sao_Paulo'))
        ->runInBackground()
        ->withoutOverlapping()
        ->appendOutputTo(storage_path('logs/whatsapp-daily-report.log'));
}
