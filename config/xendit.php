<?php

return [
    'secret_key' => env('XENDIT_SECRET_KEY'),
    'webhook_token' => env('XENDIT_WEBHOOK_TOKEN'),
    // Single source of truth for public URL: APP_URL
    'callback_url' => rtrim((string) env('APP_URL', 'http://localhost:8000'), '/') . '/api/webhooks/xendit',
    'base_url' => env('XENDIT_BASE_URL', 'https://api.xendit.co'),
];
