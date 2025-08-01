<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Webhook WhatsApp (sem autenticação e sem CSRF)
Route::post('/webhook/whatsapp', [App\Http\Controllers\WebhookWhatsAppController::class, 'handle']);