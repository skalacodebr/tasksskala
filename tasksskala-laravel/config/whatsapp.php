<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WhatsApp API Configuration
    |--------------------------------------------------------------------------
    |
    | Configurações para envio de mensagens via API do WhatsApp
    |
    */

    'api_url' => env('WHATSAPP_API_URL', 'https://api-ssl.evochat.com/v1/send_message'),
    'api_key' => env('WHATSAPP_API_KEY', '8dc028df-9b13-498f-9b10-53a0a372a1f4'),
    'default_phone' => env('WHATSAPP_DEFAULT_PHONE', '5519991169089'),
    
    /*
    |--------------------------------------------------------------------------
    | Relatórios
    |--------------------------------------------------------------------------
    |
    | Configurações específicas para relatórios
    |
    */
    
    'reports' => [
        'daily' => [
            'enabled' => env('WHATSAPP_DAILY_REPORT_ENABLED', true),
            'time' => env('WHATSAPP_DAILY_REPORT_TIME', '18:00'),
            'timezone' => env('WHATSAPP_DAILY_REPORT_TIMEZONE', 'America/Sao_Paulo'),
            'recipients' => explode(',', env('WHATSAPP_DAILY_REPORT_RECIPIENTS', '5551998926847,5551993156359,5551998727280,5551996821350')),
        ],
    ],
];