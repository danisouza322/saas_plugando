<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Asaas API Configuration
    |--------------------------------------------------------------------------
    */

    // API Key do Asaas
    'api_key' => env('ASAAS_API_KEY'),

    // Ambiente (production ou sandbox)
    'environment' => env('ASAAS_ENVIRONMENT', 'sandbox'),

    // URLs da API
    'urls' => [
        'production' => 'https://api.asaas.com/v3',
        'sandbox' => 'https://sandbox.asaas.com/api/v3',
    ],

    // Webhook token para validação
    'webhook_token' => env('ASAAS_WEBHOOK_TOKEN'),

    // Configurações padrão
    'defaults' => [
        'billing_type' => env('ASAAS_DEFAULT_BILLING_TYPE', 'BOLETO'),
        'due_days' => env('ASAAS_DEFAULT_DUE_DAYS', 5),
    ],
];
